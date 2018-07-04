<?php declare(strict_types = 1);

namespace App\Adapters;

use App\Models\Rss\Feed;

interface AdapterInterface
{
    public function __construct(\App\Models\Feed $feed);

    public function getFeed(): Feed;
}