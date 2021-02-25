<?php declare(strict_types = 1);
namespace App;

use App\Validator\BaseValidator;
use App\Validator\Validator;
use App\AbstractFactory\ {MySqlFactory, OdbcFactory, PDOFactory, SQLiteFactory};
use \PDO;

final class Connection{
    const PDO_DRIVERS = ['odbc'=>'Odbc', 'mysql' =>'MySql'];

    private \PDO $connection;
    private array $data = [];

    public function __construct(array $data)    // todo - pass logger as 2'nd parameter
    {
        $this->data = $data;
    }

    public function connect(Validator $driverValidator): bool {
        try {
            // valid driver data
            $this->data = $driverValidator->validate($this->data);

            // create pdo instance
            $pdoInstance = $this->makePDO();

            if(is_null($pdoInstance))
            {
                throw new \RuntimeException("The factory has failed. Unexpected error - Object could not be created" . PHP_EOL);
            }else { $this->setConnection($pdoInstance); }
        }catch (\Exception $e){
            print_r('<pre>');
                var_dump($e);
            print_r('</pre>');
            error_log($e->getMessage());    // todo - pass through logger
        }

        return true;
    }

    private function makePDO(): ?\PDO {
         foreach (self::PDO_DRIVERS as $key => $driver) {
             if ($key === $this->data['driver'])
             {
                 $factoryName = "App\AbstractFactory\\" . $driver . 'Factory';
                 return $this->factory(new $factoryName());
             }
         }
         return null;
    }

    private function factory(PDOFactory $PDOfactory){
        $pdo = $PDOfactory->connect($this->data);

        if(is_null($pdo))
            throw new \RuntimeException("The factory has failed. Unexpected error - PDO instance could not be created" . PHP_EOL);

        return $pdo;
    }

    private function setConnection(\PDO $connection): void{
        $this->connection = $connection;
    }

    public function getConnection(): \PDO{
        return $this->connection;
    }
}