<?php declare(strict_types = 1);
namespace App\AbstractFactory;

use App\DnsParamsValidator;

abstract class PDOFactory
{
    protected const DATA_KEYS = []; // php -S localhost:8000

    public function connect(array $connectData)
    {
        try
        {
            $dnsParam = new DnsParamsValidator($connectData, static::DATA_KEYS);

            return $this->makeConnection($dnsParam);
        } catch (\InvalidArgumentException $e) {

            var_dump($e->getMessage());
        } catch (\PDOException $e) {

            error_log("Connection error: " . $e->getMessage());
        } catch(\Throwable $e) {

            error_log("Unable to connect: " . $e->getMessage());
        } finally {
            error_log("Unexpected error " . $e->getMessage());
        }
    }

    abstract protected function makeConnection(array $connectData);

    public function makeDns(array $dnsData): string
    {
        $dns = $dnsData['driver'] . ':';
        unset($dnsData['driver']);

        foreach ($dnsData as $key => $value){
            $dns .= $key . '=' . $value . ';';
        }
        return substr($dns, 0, -1);
    }
}
