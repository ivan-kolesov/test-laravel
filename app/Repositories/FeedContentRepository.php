<?php declare(strict_types = 1);

namespace App\Repositories;

use App\Http\Requests\ContentRequest;
use App\Models\Feed;
use App\Models\FeedContent;
use App\Models\Rss\Item;
use Carbon\Carbon;
use DB;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;

class FeedContentRepository
{
    protected $feedContent;

    public function __construct(FeedContent $feedContent)
    {
        $this->feedContent = $feedContent;
    }

    public function store(Feed $feed, Item $item): void
    {
        $this->feedContent = $this->createFromItem($item);
        $this->feedContent->feed_id = $feed->id;

        $this->feedContent->save();
    }

    public function massStore(Feed $feed, Collection $items): void
    {
        $feedContents = [];
        foreach ($items as $item) {
            /** @var Item $item */
            $feedContent = $this->createFromItem($item);
            $feedContent->feed_id = $feed->id;
            $feedContent->setCreatedAt(Carbon::parse($item->getDate()));

            $feedContents[] = $feedContent->toArray();
        }

        $tableName = $this->feedContent->getTable();
        DB::table($tableName)->insert($feedContents);
    }

    public function getByPermanentLinks(int $feedId, Collection $items): Collection
    {
        $permanentLinks = $items
            ->map(function (Item $item) {
                return $item->getPermalink();
            })
            ->toArray();

        return $this->feedContent
            ->where('feed_id', $feedId)
            ->whereIn('permalink', $permanentLinks)
            ->get();
    }

    public function markRead(int $id, bool $status): void
    {
        $this->feedContent = $this->feedContent->find($id);

        $this->feedContent->read = $status;

        $this->feedContent->save();
    }

    public function getSimplePaginated(ContentRequest $contentRequest): Paginator
    {
        if ($contentRequest->from_date !== null) {
            $this->feedContent = $this->feedContent->where('created_at', '<', $contentRequest->get('from_date'));
        }

        if ($contentRequest->feed_id !== null) {
            $this->feedContent = $this->feedContent->where('feed_id', $contentRequest->feed_id);
        }

        $page = $contentRequest->page ?? 1;

        return $this->feedContent
            ->where('read', $contentRequest->get('read') ?? false)
            ->latest()
            ->simplePaginate(ContentRequest::PER_PAGE, ['*'], 'page', $page);
    }

    protected function createFromItem(Item $item): FeedContent
    {
        $feedContent = new FeedContent();
        $feedContent->title = $item->getTitle();
        $feedContent->description = $item->getDescription();
        $feedContent->content = $item->getContent();
        $feedContent->permalink = $item->getPermalink();

        return $feedContent;
    }
}