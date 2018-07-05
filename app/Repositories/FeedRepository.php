<?php declare(strict_types = 1);

namespace App\Repositories;

use App\Models\Feed;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class FeedRepository
{
    protected $feed;

    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
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
        $feed = $this->getById($id);

        if ($feed !== null) {
            return $feed->delete();
        }

        return false;
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