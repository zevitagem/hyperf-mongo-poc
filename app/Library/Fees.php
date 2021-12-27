<?php

namespace App\Library;

class Fees
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function get()
    {
        $collection = $this->connection::getCollection();

        return $collection->find()->toArray();
    }

    public function add()
    {
        $collection = $this->connection::getCollection();

        $result = $collection->insertOne(['name' => 'Hinterland', 'brewery' => 'BrewDog']);

        return "Inserted with Object ID '{$result->getInsertedId()}'";
    }

    public function destroy(string $id)
    {
        $collection = $this->connection::getCollection();

        $result = $collection->deleteOne(
            ['_id' => new \MongoDB\BSON\ObjectID($id)],
            ['limit' => 1]
        );

        return ($result->getDeletedCount() > 0);
    }
}