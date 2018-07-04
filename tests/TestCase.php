<?php

namespace Tests;

use App\Http\Requests\FeedRequest;
use App\Models\Feed;
use App\Repositories\FeedRepository;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected const FEED_URL = 'https://www.engadget.com/rss.xml';

    protected function createTestFeed(): Feed
    {
        $feedRepository = new FeedRepository(new Feed());
        $feedRequest = new FeedRequest();
        $feedRequest->merge(['url' => self::FEED_URL]);
        $feedRepository->store($feedRequest->all());

        return $feedRepository->getById(1);
    }
}