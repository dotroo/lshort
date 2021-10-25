<?php

namespace MVC\Models;

use Db\Db;
use MVC\Core\App;
use MVC\Core\Model;

class ShortLinkModel extends Model
{
    private $id;
    private $originalLink;
    private $shortUri;

    public function getId(): int
    {
        return $this->id;
    }

    public function getOriginalLink(): string
    {
        return $this->originalLink;
    }

    public function setOriginalLink(string $link): self
    {
        $this->originalLink = $link;

        return $this;
    }

    public function getShortUri(): string
    {
        return $this->shortUri;
    }

    public function setShortUri(string $link): self
    {
        $this->shortUri = $link;

        return $this;
    }

    public function getShortLink()
    {
        return App::$config['host'] . $this->shortUri;
    }

    public function saveToDb()
    {
        Db::getInstance();

        $sqlData = [
            'original_url' => $this->originalLink,
            'short_uri' => $this->shortUri    
        ];
        $request = '';

        if (!empty($this->getRowByOriginalUrl($this->originalLink))) {
            $request = 'UPDATE links SET short_uri = :short_uri WHERE original_url = :original_url';
        } else {
            $request = 'INSERT INTO links(original_url, short_uri) VALUES (:original_url, :short_uri)';
        }

        Db::request($request, $sqlData);
    }

    public function getDataById(int $id): array
    {
        Db::getInstance();
        $select = 'SELECT id, original_url, short_uri FROM links WHERE id = ?';
        $statement = Db::request($select, [$id]);
        $data = Db::fetch($statement);

        return $data;
    }

    public function getRowByUri(string $uri): array
    {
        Db::getInstance();
        $select = 'SELECT original_url, short_uri FROM links WHERE short_uri = ?';
        $statement = Db::request($select, [$uri]);
        $data = Db::fetch($statement);

        return $data;
    }

    public function getRowByOriginalUrl(string $url): array
    {
        Db::getInstance();
        $select = 'SELECT original_url, short_uri FROM links WHERE original_url = ?';
        $statement = Db::request($select, [$url]);
        $data = Db::fetch($statement);

        return $data;
    }
}