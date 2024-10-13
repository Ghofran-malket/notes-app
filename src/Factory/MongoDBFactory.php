<?php

namespace App\Factory;

use App\Service\MongoDBService;

class MongoDBFactory
{
    public static function create(): MongoDBService
    {
        return new MongoDBService();
    }
}
