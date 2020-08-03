<?php declare(strict_types = 1);
namespace App\AbstractFactory;

use PDO;

class odbcFactory extends PDOfactory
{
    public function connect(array $connectData)
    {
        /* [$connectData['driver'], "Driver" => $connectData['Driver'], "Server" => $connectData['Server'], "Database" => $connectData['Database'], "charset" => $connectData['charset'] ?? "UTF8" ] */

        $dns = $this->makeDns(array_merge(
                                array_splice($connectData, 0, 4),
                                ["charset" => $connectData['charset'] ?? "UTF8"])
                            );
    var_dump($dns);

        try{
            return new PDO($dns,
                            $connectData['user'], $connectData['password'],
                            $connectData['errmode'] ? [PDO::ATTR_ERRMODE => $connectData['errmode']] :  NULL);
        }catch (\PDOException $e){
            error_log($e->getMessage());
        }
    }
}