<?php declare(strict_types = 1);
namespace App\AbstractFactory;

use PDO;

class MySqlFactory extends  PDOFactory
{
    protected const DATA_KEYS = ['driver', 'host', 'dbname'];

    public function connect(array $connectData)
    {
        /* ["host" => $connectData["host"], "dbname" => $connectData["dbname"], "charset" => $connectData['charset'] ?? "utf8"] */

        try {
            $dns = $this->makeDns(array_merge(
                    array_splice($connectData, 0, 3),
                    ["charset" => $connectData['charset'] ?? "utf8"])
            );

            return new PDO($dns, $connectData['user'], $connectData['password'],
                            $connectData['options'] ??  NULL);
        }catch (\InvalidArgumentException $e)
        {
            var_dump($e->getMessage());
            die();
        }catch (\PDOException $e){
            error_log($e->getMessage());
            throw new \RuntimeException("unable to connect: ".$e->getMessage());
        }catch(\Throwable $e){
            error_log($e->getMessage());
            throw new \RuntimeException("unable to connect: ".$e->getMessage());
        }
    }
}