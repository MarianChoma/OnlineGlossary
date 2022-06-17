<?php

use JetBrains\PhpStorm\Pure;

class Translation
{
    /* @var MyPDO */
    protected $db;
    protected int $id;
    protected string $title;
    protected string $description;
    protected int $word_id;
    protected int $language_id;

    /**
     * @param MyPDO $db
     * @param int $id
     * @param string $title
     * @param string $description
     * @param int $word_id
     * @param int $language_id
     */
    #[Pure] public function __construct(MyPDO $db, string $title, string $description, int $language_id, Word $word)
    {
        $this->db = $db;
        $this->title = $title;
        $this->description = $description;
        $this->language_id = $language_id;
        $this->word_id= $word->getId();
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

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getWordId(): int
    {
        return $this->word_id;
    }

    /**
     * @param int $word_id
     */
    public function setWordId(int $word_id): void
    {
        $this->word_id = $word_id;
    }

    /**
     * @return int
     */
    public function getLanguageId(): int
    {
        return $this->language_id;
    }

    /**
     * @param int $language_id
     */
    public function setLanguageId(int $language_id): void
    {
        $this->language_id = $language_id;
    }


    public function find($id)
    {
        $data = $this->db->run("SELECT * FROM translations WHERE id = ?", [$id])->fetch();
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->language_id = $data['language_id'];
        $this->word_id = $data['word_id'];

    }

    public function save()
    {
        $this->db->run("INSERT into translations 
            (`title`, `description`,`language_id`, `word_id`) values (?,?,?,?)",
            [$this->title, $this->description, $this->language_id, $this->word_id]);
    }
}