<?php declare(strict_types = 1);
namespace App\AbstractFactory;

use App\Validator\DnsParamsValidator;

abstract class PDOFactory
{
    protected const DATA_KEYS = []; // php -S localhost:8000

    public function connect(array $connectData): \PDO
    {
        $dnsParam = new DnsParamsValidator(static::DATA_KEYS);

        $dns = $this->makeDns($dnsParam->validate($connectData));

        return
            new \PDO($dns,
                    $connectData['user'],
                    $connectData['password'],
                    $connectData['options']);
    }

    protected function makeDns(array $dnsData): string
    {
        $dns = $dnsData['driver'] . ':';
        unset($dnsData['driver']);

        foreach ($dnsData as $key => $value){
            $dns .= $key . '=' . $value . ';';
        }
        return substr($dns, 0, -1);
    }
}
