<?php declare(strict_types = 1);

namespace App\Repositories;

use App\Http\Requests\ContentRequest;
use App\Models\Feed;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class FeedRepository
{
    protected $feed;

    public function __construct(Feed $feedContent)
    {
        $this->feed = $feedContent;
    }

    public function getContentByFeedId(Collection $feeds, int $id): Collection
    {
        $feed = $feeds->first(function (Feed $feed) use ($id) {
            return (int)$feed->id === $id;
        });

        return $feed instanceof Feed
            ? $feed->content()->latest()->take(ContentRequest::PER_PAGE)->get()
            : collect();
    }

    public function getById(int $id): ?Feed
    {
        return $this->feed->find($id);
    }

    public function getAll(): Collection
    {
        return $this->feed->get();
    }

    public function store(array $inputs): bool
    {
        try {
            return $this->saveFeed(new Feed(), $inputs);
        } catch (QueryException $exception) {
            return false;
        }
    }

    public function update(Feed $feed, array $inputs): bool
    {
        return $this->saveFeed($feed, $inputs);
    }

    public function remove(int $id): bool
    {
        return $this->getById($id)->delete();
    }

    protected function saveFeed(Feed $feed, array $inputs): bool
    {
        $feed->url = $inputs['url'];
        if (array_key_exists('name', $inputs)) {
            $feed->name = $inputs['name'];
        }

        return $feed->save();
    }
}