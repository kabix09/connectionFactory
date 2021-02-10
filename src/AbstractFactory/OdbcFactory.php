<?php declare(strict_types = 1);
namespace App\AbstractFactory;

use PDO;

class OdbcFactory extends PDOFactory
{
    protected const DATA_KEYS = ['driver', 'Driver', 'Server', 'Database'];

    protected function makeConnection(array $connectData)
    {
        /* [$connectData['driver'], "Driver" => $connectData['Driver'], "Server" => $connectData['Server'], "Database" => $connectData['Database'], "charset" => $connectData['charset'] ?? "UTF8" ] */

        $dns = $this->makeDns(array_merge(
                array_splice($connectData, 0, 4),
                ["charset" => $connectData['charset'] ?? "UTF8"])
        );

        return new PDO($dns, $connectData['user'], $connectData['password'],
                        $connectData['errmode'] ? array(PDO::ATTR_ERRMODE => $connectData['errmode']):  NULL);
    }
}