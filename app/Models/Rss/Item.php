<?php declare(strict_types = 1);

namespace App\Models\Rss;

use Illuminate\Support\Str;

class Item
{
    private $title;
    private $description;
    private $content;
    private $permalink;
    private $date;

    public function __construct(string $title)
    {
        $this->title = Str::substr($title, 0, 255);
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setDescription(string $description = null): self
    {
        $this->description = Str::substr($description, 0, 1024);

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setContent(string $content = null): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setPermalink(string $link = null): self
    {
        $this->permalink = Str::substr($link, 0, 255);

        return $this;
    }

    public function getPermalink(): ?string
    {
        return $this->permalink;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }
}