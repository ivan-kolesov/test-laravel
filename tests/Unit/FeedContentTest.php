<?php declare(strict_types = 1);

namespace Tests\Unit;

use App\Models\FeedPost;
use App\Models\Rss\Item;
use App\Repositories\FeedPostRepository;
use Carbon\Carbon;
use Tests\TestCase;

class FeedContentTest extends TestCase
{
    public function testCreatePost(): void
    {
        $feed = $this->createTestFeed();

        $post = factory(FeedPost::class)->make([
            'feed_id' => $feed->id,
        ]);
        $feedContentRepository = new FeedPostRepository($post);
        $item = (new Item($post->title))
            ->setDescription($post->description)
            ->setContent($post->content)
            ->setPermalink($post->permalink)
            ->setDate(Carbon::parse($post->created_at)->toDateTimeString());

        $feedContentRepository->store($feed, $item);

        $this->assertEquals($feed->posts()->count(), 1);
    }
}