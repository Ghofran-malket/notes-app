<?php

namespace App\Service;

use App\Entity\Animal;
use App\Entity\Note;
use MongoDB\Client;

class MongoDBService
{
    private $client;
    private $database;

    public function __construct()
    {
        $databaseName="notes_db";
        $this->client = new Client("mongodb://localhost:27017/");
        $this->database = $this->client->$databaseName;
    }

    public function getCollection(string $collectionName)
    {
        return $this->database->$collectionName;
    }

    public function newNote(Note $note)
    {
        $collection = $this->getCollection('notes');
        $collection->insertOne([
            'title' => $note->getTitle(),
            'content' => $note->getContent()
        ]);
    }

}
