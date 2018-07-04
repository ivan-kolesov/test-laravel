<?php declare(strict_types = 1);

namespace App\Models\Rss;

class Item
{
    private $title;
    private $description;
    private $content;
    private $permalink;
    private $date;

    public function __construct()
    {
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

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setPermalink(string $link): self
    {
        $this->permalink = $link;

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