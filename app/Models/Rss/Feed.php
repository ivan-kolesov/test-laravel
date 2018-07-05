<?php declare(strict_types = 1);

namespace App\Models\Rss;

use Illuminate\Support\Collection;

class Feed
{
    private $url;
    private $title;
    private $description;
    private $items;

    public function __construct(string $url, Collection $items = null)
    {
        $this->url = $url;
        $this->items = $items ?? collect();
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setItems(Collection $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }
}