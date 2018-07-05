<?php declare(strict_types = 1);

namespace App\Repositories;

use App\Http\Requests\PostRequest;
use App\Models\Feed;
use App\Models\FeedPost;
use App\Models\Rss\Item;
use Carbon\Carbon;
use DB;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;

class FeedPostRepository
{
    protected $feedPost;

    public function __construct(FeedPost $feedPost)
    {
        $this->feedPost = $feedPost;
    }

    public function store(Feed $feed, Item $item): void
    {
        $this->feedPost = $this->createFromItem($item);
        $this->feedPost->feed_id = $feed->id;

        $this->feedPost->save();
    }

    public function massStore(Feed $feed, Collection $items): void
    {
        $posts = [];
        foreach ($items as $item) {
            /** @var Item $item */
            $post = $this->createFromItem($item);
            $post->feed_id = $feed->id;
            $post->setCreatedAt(Carbon::parse($item->getDate()));

            $posts[] = $post->toArray();
        }

        $tableName = $this->feedPost->getTable();
        DB::table($tableName)->insert($posts);
    }

    public function getByPermanentLinks(int $feedId, Collection $items): Collection
    {
        $permanentLinks = $items
            ->map(function (Item $item) {
                return $item->getPermalink();
            })
            ->toArray();

        return $this->feedPost
            ->where('feed_id', $feedId)
            ->whereIn('permalink', $permanentLinks)
            ->get();
    }

    public function markRead(int $id, bool $status): void
    {
        $post = $this->feedPost->find($id);

        if ($post instanceof FeedPost) {
            $post->read = $status;

            $post->save();
        }
    }

    public function getSimplePaginated(PostRequest $postRequest): Paginator
    {
        if ($postRequest->from_date !== null) {
            $this->feedPost = $this->feedPost->where('created_at', '<', $postRequest->get('from_date'));
        }

        if ($postRequest->feed_id !== null) {
            $this->feedPost = $this->feedPost->where('feed_id', $postRequest->feed_id);
        }

        $page = $postRequest->page ?? 1;

        return $this->feedPost
            ->where('read', $postRequest->get('read') ?? false)
            ->latest()
            ->simplePaginate(PostRequest::PER_PAGE, ['*'], 'page', $page);
    }

    protected function createFromItem(Item $item): FeedPost
    {
        $post = new FeedPost();
        $post->title = $item->getTitle();
        $post->description = $item->getDescription();
        $post->content = $item->getContent();
        $post->permalink = $item->getPermalink();

        return $post;
    }
}