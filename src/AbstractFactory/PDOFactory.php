<?php declare(strict_types = 1);
namespace App\AbstractFactory;

abstract class PDOFactory
{
    protected const DATA_KEYS = []; // php -S localhost:8000

    public function connect(array $connectData)
    {
        try
        {
            $this->makeConnection($connectData);
        } catch (\InvalidArgumentException $e) {

            var_dump($e->getMessage());
        } catch (\PDOException $e) {

            var_dump("unable to connect: " . $e->getMessage());
            error_log("unable to connect: " . $e->getMessage());    // save in default php log file
        } catch(\Throwable $e) {

            var_dump("unable to connect: " . $e->getMessage());
            error_log("unable to connect: " . $e->getMessage());    // save in default php log file
        }
    }

    abstract protected function makeConnection(array $connectData);
    public function makeDns(array $dnsData): string
    {
        $dnsData = $this->validDnsData($dnsData);

        if(!$this->checkDnsData($dnsData))
            throw new \InvalidArgumentException('Invalid DNS array data argument');

        $dns = $dnsData['driver'] . ':';
        unset($dnsData['driver']);

        foreach ($dnsData as $key => $value){
            $dns .= $key . '=' . $value . ';';
        }
        return substr($dns, 0, -1);
    }

    private function validDnsData(array $data) : array
    {
        $validData = [];

        foreach (static::DATA_KEYS as $key) {
            $validData[$key] = array_key_exists($key, $data) ? $data[$key] : null;
        }

        return $validData;
    }

    private function checkDnsData(array $data) : bool
    {
        foreach (static::DATA_KEYS as $key) {
            if(is_null($data[$key]))
                return false;
        }

        return true;
    }
}