<?php declare(strict_types = 1);

namespace App\Http\Requests;

trait FeedModifyTrait
{
    protected function populateNameByUrl(): void
    {
        $input = [
            'name' => app('FeedService')->getFeedTitle($this->url) ?? $this->url
        ];
        $this->merge($input);
    }
}