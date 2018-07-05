<?php declare(strict_types = 1);

namespace App\Console\Commands;

use App\Adapters\AdapterInterface;
use App\Models\Feed;
use App\Models\FeedPost;
use App\Models\Rss\Item;
use App\Repositories\FeedPostRepository;
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

    public function handle(FeedPostRepository $feedPostRepository)
    {
        Feed::all()
            ->each(function (Feed $feed) use ($feedPostRepository) {
                $class = new ReflectionClass(config('app.feedAdapter'));
                /** @var AdapterInterface $adapter */
                $adapter = $class->newInstanceArgs([$feed->getUrl()]);

                $rssFeed = $adapter->getFeed();

                foreach ($rssFeed->getItems()->chunk(self::FEED_BATCH_SIZE) as $items) {
                    $items = $this->getNonExistFeedPostItems($feedPostRepository, $feed->id, $items);

                    $feedPostRepository->massStore($feed, $items);
                }
            });

        return 0;
    }

    private function getNonExistFeedPostItems(
        FeedPostRepository $feedPostRepository,
        int $feedId,
        Collection $items
    ): Collection {
        $existFeedPostLinks = $feedPostRepository->getByPermanentLinks($feedId, $items)
            ->map(function (FeedPost $post) {
                return $post->permalink;
            })
            ->flip();

        return $items->filter(function (Item $post) use ($existFeedPostLinks) {
            return !$existFeedPostLinks->has($post->getPermalink());
        });
    }
}