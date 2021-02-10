<?php declare(strict_types = 1);
namespace App\AbstractFactory;

use PDO;

class MySqlFactory extends  PDOFactory
{
    protected const DATA_KEYS = ['driver', 'host', 'dbname'];

    protected function makeConnection(array $connectData)
    {
        /* ["host" => $connectData["host"], "dbname" => $connectData["dbname"], "charset" => $connectData['charset'] ?? "utf8"] */

        $dns = $this->makeDns(array_merge(
                array_splice($connectData, 0, 3),
                ["charset" => $connectData['charset'] ?? "utf8"])
        );

        return new PDO($dns, $connectData['user'], $connectData['password'],
                        $connectData['options'] ??  NULL);
    }
}