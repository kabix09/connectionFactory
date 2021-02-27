<?php declare(strict_types=1);
namespace ConnectionFactory\Validator;

final class DnsParamsValidator implements Validator
{
    private int $finalIndex;
    private array $dataKeys;

    public function __construct(array $options = [])
    {
        $this->dataKeys = array_key_exists('dataKeys', $options) ? $options['dataKeys'] : [];
        $this->finalIndex = count($this->dataKeys);
    }

    public function validate(array $dataToValid): array
    {
        $array = $this->buildParametersArray($dataToValid);

        $dnsData = $this->validDnsData($array);

        if(!$this->checkDnsData($dnsData)) {
            throw new \InvalidArgumentException('Invalid DNS array data argument');
        }
        return $dnsData;
    }

    private function buildParametersArray(array $connectionData) : array
    {
        return array_merge(
                array_splice($connectionData, 0, $this->finalIndex),
                [
                    "charset" => $connectData['charset'] ?? "utf8"
                ]
            );
    }

    private function validDnsData(array $data) : array
    {
        $validData = [];

        foreach ($this->dataKeys as $key) {
            $validData[$key] = $data[$key] ?? null;
        }

        return $validData;
    }

    private function checkDnsData(array $data) : bool
    {
        foreach ($this->dataKeys as $key) {
            if(is_null($data[$key]))
            {
                return false;
            }
        }
        return true;
    }
}