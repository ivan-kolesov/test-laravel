<?php declare(strict_types = 1);

namespace Tests\Unit;

use App\Http\Requests\FeedRequest;
use App\Http\Requests\FeedUpdateRequest;
use App\Models\Feed;
use App\Repositories\FeedRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FeedTest extends TestCase
{
    use RefreshDatabase;

    private const FEED_URL = 'https://www.engadget.com/rss.xml';
    private const FEED_INVALID_URL = 'engadgetcom/rss.xml';

    public function testFeedRequestOnValidUrl(): void
    {
        $feedRequest = new FeedRequest();
        $feedRequest->merge(['url' => static::FEED_INVALID_URL]);

        $validator = validator($feedRequest->all(), $feedRequest->rules());
        $this->assertTrue($validator->fails());
    }

    public function testCreateFeed(): void
    {
        $feed = $this->createTestFeed();
        $this->assertEquals(static::FEED_URL, $feed->getUrl());
    }

    public function testUpdateFeed(): void
    {
        $feed = $this->createTestFeed();
        $newFeedUrl = $feed->getUrl() . 'modified';

        $feedRepository = new FeedRepository($feed);
        $feedRequest = new FeedUpdateRequest();
        $feedRequest->merge(['url' => $newFeedUrl]);

        $feedRepository->update($feed, $feedRequest->all());
        $feed = $feedRepository->getById($feed->id);

        $this->assertEquals($newFeedUrl, $feed->getUrl());
    }

    public function testRemoveFeed(): void
    {
        $feed = $this->createTestFeed();

        $feedRepository = new FeedRepository($feed);
        $feedRepository->remove($feed->id);

        $feed = $feedRepository->getById($feed->id);
        $this->assertNull($feed);
    }

    private function createTestFeed(): Feed
    {
        $feedRepository = new FeedRepository(new Feed());
        $feedRequest = new FeedRequest();
        $feedRequest->merge(['url' => static::FEED_URL]);
        $feedRepository->store($feedRequest->all());

        return $feedRepository->getById(1);
    }
}
