<?php declare(strict_types = 1);

namespace App\Repositories;

use App\Http\Requests\ContentRequest;
use App\Models\Feed;
use App\Models\FeedContent;
use Carbon\Carbon;
use DB;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;
use SimplePie_Item;

class FeedContentRepository
{
    protected $feedContent;

    public function __construct(FeedContent $feedContent)
    {
        $this->feedContent = $feedContent;
    }

    public function store(Feed $feed, SimplePie_Item $item): void
    {
        $this->feedContent = $this->createFromItem($item);
        $this->feedContent->feed_id = $feed->id;

        $this->feedContent->save();
    }

    public function massStore(Feed $feed, array $items): void
    {
        $feedContents = [];
        foreach ($items as $item) {
            /** @var SimplePie_Item $item */
            $feedContent = $this->createFromItem($item);
            $feedContent->feed_id = $feed->id;
            $feedContent->setCreatedAt(Carbon::parse($item->get_date()));

            $feedContents[] = $feedContent->toArray();
        }

        $tableName = $this->feedContent->getTable();
        DB::table($tableName)->insert($feedContents);
    }

    public function getByPermanentLinks(array $items): Collection
    {
        $permanentLinks = array_map(function (SimplePie_Item $item) {
            return $item->get_permalink();
        }, $items);

        return $this->feedContent->whereIn('permalink', $permanentLinks)->get();
    }

    public function markRead(int $id, bool $status): void
    {
        $this->feedContent = $this->feedContent->find($id);

        $this->feedContent->read = $status;

        $this->feedContent->save();
    }

    public function getSimplePaginated(ContentRequest $contentRequest): Paginator
    {
        if ($contentRequest->get('from_date') !== null) {
            $this->feedContent = $this->feedContent->where('created_at', '<', $contentRequest->get('from_date'));
        }

        $page = $contentRequest->page ?? 1;

        return $this->feedContent
            ->where('feed_id', $contentRequest->feed_id)
            ->where('read', $contentRequest->get('read') ?? false)
            ->latest()
            ->simplePaginate(ContentRequest::PER_PAGE, ['*'], 'page', $page);
    }

    protected function createFromItem(SimplePie_Item $item): FeedContent
    {
        $feedContent = new FeedContent();
        $feedContent->title = $item->get_title();
        $feedContent->description = $item->get_description();
        $feedContent->content = $item->get_content();
        $feedContent->permalink = $item->get_permalink();

        return $feedContent;
    }
}