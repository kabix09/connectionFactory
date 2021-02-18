<?php declare(strict_types = 1);
namespace App\AbstractFactory;

use App\DnsParameters;
use PDO;

class MySqlFactory extends PDOFactory
{
    protected const DATA_KEYS = ['driver', 'host', 'dbname'];

    protected function makeConnection(array $connectData)
    {
        /* ["host" => $connectData["host"], "dbname" => $connectData["dbname"], "charset" => $connectData['charset'] ?? "utf8"] */
        $dnsParam = new DnsParameters($connectData, self::DATA_KEYS);

        $dns = $this->makeDns(
            $dnsParam->generate()
        );

        return new PDO($dns, $connectData['user'], $connectData['password'],
                        $connectData['options'] ??  NULL);
    }
}