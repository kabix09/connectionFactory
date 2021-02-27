<?php declare(strict_types=1);
namespace ConnectionFactory\Validator;

use PDO;

final class ConnectionDriverValidator implements Validator
{
    public function __construct(array $options = []) { }

    /** @noinspection PhpInconsistentReturnPointsInspection */
    public function validate(array $dataToValid): array
    {
        if($this->validDriverData($dataToValid)) {
            return $dataToValid;
        }
    }

    private function validDriverData(array $data) : bool{
        if (count($data) < 1) {
            throw new \RuntimeException("Invalid arguments: incorrect number of parameters" . PHP_EOL);
        }

        if (!isset($data['driver'])) {
            throw new \RuntimeException("Invalid arguments: driver wasn't indicated or is null" . PHP_EOL);
        }

        if (!$this->checkDriver($data['driver'])) {
            throw new \RuntimeException("Unrecognized PDO driver: your server don't support '" . $data['driver'] . "' driver extension" . PHP_EOL);
        }

        return true;
    }

    private function checkDriver(string $driver): bool{
        foreach (PDO::getAvailableDrivers() as $allowedDriver) {
            if ($allowedDriver === $driver) {
                return true;
            }
        }

        return false;
    }
}