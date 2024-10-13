<?php

namespace App\Service;

use App\Entity\Animal;
use App\Entity\Note;
use Exception;
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

    public function getAllNotes(): array{
        $collection = $this->getCollection('notes');
        $notes = $collection->find();
        return iterator_to_array($notes);
    }

    public function getNoteByTitle(string $title): ?Note
    {
        $collection = $this->getCollection('notes');

        $note = $collection->findOne(['title' => $title]);
        $note2 = new Note();
        $note2->setTitle($note['title']);
        $note2->setContent($note['content']);
        return $note2;
    }


    public function updateNote(string $newTitle, string $newContent, string $oldTitle)
    {
        $collection = $this->getCollection('notes');

        $result = $collection->updateOne(
            ['title' => $oldTitle], // Find the note by its current title
            ['$set' => [
                'title' => $newTitle, // Update the title (this may change)
                'content' => $newContent, // Update the content
            ]]
        );
        
        
    }
}
