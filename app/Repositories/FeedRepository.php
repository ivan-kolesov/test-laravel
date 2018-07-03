<?php declare(strict_types = 1);

namespace App\Repositories;

use App\Http\Requests\ContentRequest;
use App\Models\Feed;
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

    public function getAll(): Collection
    {
        return $this->feed->get();
    }

    public function store(array $inputs): bool
    {
        return $this->saveFeed(new Feed(), $inputs);
    }

    public function update(array $inputs): bool
    {
        return $this->saveFeed($this->feed, $inputs);
    }

    public function remove(int $id): bool
    {
        return $this->feed->find($id)->delete();
    }

    protected function saveFeed(Feed $feed, array $inputs): bool
    {
        $feed->url = $inputs['url'];
        $feed->name = $inputs['name'];

        return $feed->save();
    }
}