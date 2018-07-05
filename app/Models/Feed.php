<?php declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feed extends Model
{
    protected $fillable = ['filters'];

    public $timestamps = false;

    public function getUrl(): string
    {
        return $this->getAttribute('url');
    }

    public function getName(): string
    {
        return $this->getAttribute('name');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(FeedPost::class);
    }
}