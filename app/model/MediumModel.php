<?php

use DateTime;

class MediumModel
{
    private int $id;
    private string $title;
    private DateTime $uploadDate;
    private string $fileSize;
    private string $description;
    private string $type;

    public function __construct( string $title, DateTime $uploadDate, string $fileSize, string $description, string $type)
    {
        $this->title = $title;
        $this->uploadDate = $uploadDate;
        $this->fileSize = $fileSize;
        $this->description = $description;
        $this->type = $type;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getUploadDate(): DateTime
    {
        return $this->uploadDate;
    }

    public function setUploadDate(DateTime $uploadDate): void
    {
        $this->uploadDate = $uploadDate;
    }

    public function getFileSize(): string
    {
        return $this->fileSize;
    }

    public function setFileSize(string $fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}

class PhotoModel extends MediumModel
{
    public int $resolution;

    public function __construct(int $id, string $title, DateTime $uploadDate, string $fileSize, string $description, int $resolution)
    {
        parent::__construct($id, $title, $uploadDate, $fileSize, $description);
        $this->resolution = $resolution;
    }

    public function getResolution(): int
    {
        return $this->resolution;
    }

    public function setResolution(int $resolution): void
    {
        $this->resolution = $resolution;
    }
}

class VideoModel extends MediumModel
{
    public int $duration;
    public int $resolution;

    public function __construct(int $id, string $title, DateTime $uploadDate, string $fileSize, string $description, int $duration, int $resolution)
    {
        parent::__construct($id, $title, $uploadDate, $fileSize, $description);
        $this->duration = $duration;
        $this->resolution = $resolution;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    public function getResolution(): int
    {
        return $this->resolution;
    }

    public function setResolution(int $resolution): void
    {
        $this->resolution = $resolution;
    }
}

class AudioBook extends MediumModel
{
    public int $duration;
    public string $speaker;

    public function __construct(int $id, string $title, DateTime $uploadDate, string $fileSize, string $description, int $duration, string $speaker)
    {
        parent::__construct($id, $title, $uploadDate, $fileSize, $description);
        $this->duration = $duration;
        $this->speaker = $speaker;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    public function getSpeaker(): string
    {
        return $this->speaker;
    }

    public function setSpeaker(string $speaker): void
    {
        $this->speaker = $speaker;
    }
}

class Ebook extends MediumModel
{
    public string $author;
    public string $pageCount;

    public function __construct(int $id, string $title, DateTime $uploadDate, string $fileSize, string $description, string $author, string $pageCount)
    {
        parent::__construct($id, $title, $uploadDate, $fileSize, $description);
        $this->author = $author;
        $this->pageCount = $pageCount;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function getPageCount(): string
    {
        return $this->pageCount;
    }

    public function setPageCount(string $pageCount): void
    {
        $this->pageCount = $pageCount;
    }
}