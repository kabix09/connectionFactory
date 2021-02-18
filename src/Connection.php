<?php declare(strict_types = 1);
namespace App;
use App\Validator\ConnectionDriverValidator;
use App\AbstractFactory\ {MySqlFactory, OdbcFactory, PDOFactory, SQLiteFactory};
use \PDO;

class Connection{
    const PDO_DRIVERS = ['odbc'=>'Odbc', 'mysql' =>'MySql'];

    private \PDO $connection;
    private array $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function connect(): bool {
        try {
            // valid driver data
            $driverValidator = new ConnectionDriverValidator();
            $this->data = $driverValidator->validate($this->data);

            // create pdo instance
            $pdoInstance = $this->makePDO();

            if(is_null($pdoInstance))
            {
                throw new \RuntimeException("The factory has failed. Unexpected error - Object could not be created" . PHP_EOL);
            }else { $this->setConnection($pdoInstance); }
        }catch (\Exception $e){
            var_dump($e);
            error_log($e->getMessage());
        }

        return true;
    }

    private function makePDO(): ?\PDO {
         foreach (self::PDO_DRIVERS as $driver)
             if($driver === $this->data['driver'])
                 return $this->factory(new ($driver . 'Factory')());

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