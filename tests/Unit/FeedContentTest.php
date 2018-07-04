<?php declare(strict_types = 1);

namespace Tests\Unit;

use App\Models\FeedContent;
use App\Models\Rss\Item;
use App\Repositories\FeedContentRepository;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FeedContentTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePost(): void
    {
        $feed = $this->createTestFeed();

        $post = factory(FeedContent::class)->make([
            'feed_id' => $feed->id,
        ]);
        $feedContentRepository = new FeedContentRepository($post);
        $item = (new Item())
            ->setTitle($post->title)
            ->setDescription($post->description)
            ->setContent($post->content)
            ->setPermalink($post->permalink)
            ->setDate(Carbon::parse($post->created_at)->toDateTimeString());

        $feedContentRepository->store($feed, $item);

        $this->assertEquals($feed->content()->count(), 1);
    }
}