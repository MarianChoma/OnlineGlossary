<?php

class Word
{
    /* @var MyPDO */
    protected $db;
    protected int $id;
    protected string $title;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function __construct(MyPDO $db, string $title)
    {
        $this->db = $db;
        $this->title=$title;
    }

    public function find($id)
    {
        $data = $this->db->run("SELECT * FROM words WHERE id = ?", [$id])->fetch();
        $this->title= $data["title"];

    }
    public function save()
    {
        $this->db->run("INSERT into words (`title`) values (?)", [$this->title]);
        $this->id = $this->db->lastInsertId();
    }
}