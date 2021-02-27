<?php declare(strict_types = 1);
namespace ConnectionFactory\AbstractFactory;

use ConnectionFactory\Validator\DnsParamsValidator;

abstract class PDOFactory
{
    protected const DATA_KEYS = [];
    private DnsParamsValidator $dnsParamValid;

    public function __construct()
    {
        $this->dnsParamValid = new DnsParamsValidator(['dataKeys' => static::DATA_KEYS]);
    }

    public function connect(array $connectData): \PDO
    {
        $dns = $this->makeDns($this->dnsParamValid->validate($connectData));
        $options = $this->buildOptions($connectData['options']);

        return
            new \PDO($dns,
                    $connectData['user'],
                    $connectData['password'],
                    $options);
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

    private function buildOptions(array $options): array
    {
        $evaluatedOptions = [];

        foreach ($options as $key => $value)
        {
            eval("\$evKey = $key; \$evValue = $value; \$evaluatedOptions[\$evKey] = \$evValue;");
        }

        return $evaluatedOptions;
    }
}
