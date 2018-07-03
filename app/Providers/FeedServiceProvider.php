<?php declare(strict_types=1);

namespace App\Providers;

use Feeds;
use Illuminate\Support\ServiceProvider;

class FeedServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register(): void
    {
        $this->app->bind('FeedService', function () {
            return new self($this->app);
        });
    }

    public function provides(): array
    {
        return ['FeedService'];
    }

    public function getFeedTitle(string $url): ?string
    {
        $feed = Feeds::make([$url]);
        return $feed->get_title();
    }
}