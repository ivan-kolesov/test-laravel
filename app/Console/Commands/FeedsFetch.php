<?php declare(strict_types = 1);

namespace App\Console\Commands;

use App\Models\Feed;
use App\Models\FeedContent;
use App\Repositories\FeedContentRepository;
use Feeds;
use Illuminate\Console\Command;
use SimplePie_Item;

class FeedsFetch extends Command
{
    private const FEED_BATCH_SIZE = 10;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feeds:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store feeds';

    public function handle(FeedContentRepository $feedContentRepository)
    {
        Feed::all()
            ->each(function (Feed $feedModel) use ($feedContentRepository) {
                $feed = Feeds::make([$feedModel->getUrl()]);

                $allItems = $feed->get_items();
                foreach (array_chunk($allItems, self::FEED_BATCH_SIZE) as $items) {
                    $items = $this->getNonExistFeedContentItems($feedContentRepository, $items);

                    $feedContentRepository->massStore($feedModel, $items);
                }
        });

        return 0;
    }

    private function getNonExistFeedContentItems(FeedContentRepository $feedContentRepository, array $items): array
    {
        $existFeedContentLinks = $feedContentRepository->getByPermanentLinks($items)
            ->map(function (FeedContent $feedContent) {
                return $feedContent->permalink;
            })
            ->flip();

        return array_filter($items, function (SimplePie_Item $item) use ($existFeedContentLinks) {
            return !$existFeedContentLinks->has($item->get_permalink());
        });
    }
}
