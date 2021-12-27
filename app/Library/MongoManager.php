<?php

namespace App\Library;

class MongoManager
{
    private static $connection = null;

    public static function connect()
    {
        if (!empty(self::$connection)) {
            return self::getConnection();
        }

        $user     = getenv('MONGO_INITDB_ROOT_USERNAME');
        $password = getenv('MONGO_INITDB_ROOT_PASSWORD');
        $host     = getenv('MONGO_INITDB_HOST');
        $port     = getenv('MONGO_INITDB_PORT');

        self::$connection = new \MongoDB\Client("mongodb://$user:$password@$host:$port");
    }

    private static function canPerform()
    {
        if (empty(self::getConnection())) {
            throw new \DomainException('A conexão com o mongo deve existir antes de realizar a operação');
        }

        return true;
    }

    public static function getCollection(string $collection = 'fees', string $database = 'checkout')
    {
        self::canPerform();

        $database = self::getConnection()->{$database};

        return $database->{$collection};
    }

    public static function listDatabases()
    {
        self::canPerform();

        $dbs = [];
        foreach (self::getConnection()->listDatabases() as $database) {
            $dbs[] = $database->getName();
        }

        return $dbs;
    }

    public static function getConnection()
    {
        return self::$connection;
    }
}