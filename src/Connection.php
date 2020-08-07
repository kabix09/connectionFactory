<?php declare(strict_types = 1);
namespace App;
use App\AbstractFactory\mysqlFactory;
use App\AbstractFactory\odbcFactory;
use App\AbstractFactory\PDOfactory;
use App\AbstractFactory\sqliteFactory;
use \PDO;

class Connection{
    const PDOdrivers = ['odbc'=>'odbc', 'mysql' =>'mysql'];
    private static $connection;
    private static $instance;
    private static $data = array();

    public function __construct(array $data)
    {
        if (count($data) < 5)
            throw new \RuntimeException("invalid arguments: incorrect number of parameters" . PHP_EOL);

        if (!isset($data['driver']))
            throw new \RuntimeException("invalid arguments: driver wasn't indicated" . PHP_EOL);

        if (!$this->checkDriver($data['driver']))
            throw new \RuntimeException("unrecognized PDO driver: your server don't support '" . $data['driver'] . "' driver extension" . PHP_EOL);

        $this->setData($data);
    }

    public static function init(array $data){
        if(self::$instance === NULL)
            self::$instance = new Connection($data);
        return self::$instance;
    }

    public function setData(array $data){
        self::$data = $data;
    }

    protected function checkDriver(string $driver){
        foreach (PDO::getAvailableDrivers() as $allowedDriver)
            if($allowedDriver === $driver)
                return TRUE;

        return FALSE;
    }

    public static function PDOconnect(){
        switch (self::$data['driver'])
        {
            case self::PDOdrivers['odbc']: {
                self::$instance->setConnection(self::$instance->factory(new odbcFactory()));
                break;
            }
            case self::PDOdrivers['mysql']: {
                self::$instance->setConnection(self::$instance->factory(new mysqlFactory()));
                break;
            }
            default: {
                throw new \RuntimeException("unrecognized PDO driver: this class don't support '". self::$data['driver'] . "'" . PHP_EOL);
                break;
            }
        }

        /**
         * foreach (self::PDOdrivers as $driver){
         * if($driver === self::$data['driver']){
         *      try{
         *          self::$instance->setConnection(self::$instance->factory(new ($driver . 'Factory')()));
         *      }catch(\Throwable $e)
         *      {
         *          error_log($e->getMessage());
         *      }catch(\Exception $e){
         *          error_log($e->getMessage());
         *      }
         *  }
         * }
         * return FALSE;
         */
    }

    private function factory(PDOfactory $PDOfactory){
        return $PDOfactory->connect(self::$data);
    }

    private function setConnection(\PDO $connection){
        self::$connection = $connection;
    }

    public function getConnection(): \PDO{
        return self::$connection;
    }
}