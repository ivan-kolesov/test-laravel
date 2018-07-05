<?php declare(strict_types = 1);

namespace App\Console\Commands;

use App\Adapters\AdapterInterface;
use App\Models\Feed;
use App\Models\FeedContent;
use App\Models\Rss\Item;
use App\Repositories\FeedContentRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use ReflectionClass;

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
            ->each(function (Feed $feed) use ($feedContentRepository) {
                $class = new ReflectionClass(config('app.feedAdapter'));
                /** @var AdapterInterface $adapter */
                $adapter = $class->newInstanceArgs([$feed->getUrl()]);

                $rssFeed = $adapter->getFeed();

                foreach ($rssFeed->getItems()->chunk(self::FEED_BATCH_SIZE) as $items) {
                    $items = $this->getNonExistFeedContentItems($feedContentRepository, $feed->id, $items);

                    $feedContentRepository->massStore($feed, $items);
                }
            });

        return 0;
    }

    private function getNonExistFeedContentItems(
        FeedContentRepository $feedContentRepository,
        int $feedId,
        Collection $items
    ): Collection {
        $existFeedContentLinks = $feedContentRepository->getByPermanentLinks($feedId, $items)
            ->map(function (FeedContent $feedContent) {
                return $feedContent->permalink;
            })
            ->flip();

        return $items->filter(function (Item $post) use ($existFeedContentLinks) {
            return !$existFeedContentLinks->has($post->getPermalink());
        });
    }
}