<?php declare(strict_types = 1);
namespace App;
use App\AbstractFactory\ {mysqlFactory, odbcFactory, PDOfactory, sqliteFactory};
use \PDO;

class Connection{
    const PDOdrivers = ['odbc'=>'odbc', 'mysql' =>'mysql'];
    private $connection;
    private $data = array();

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

    public function connect(){
        $pdoInstance = $this->makePDO();

        if(is_null($pdoInstance))
        {
            throw new \RuntimeException("unrecognized PDO driver: this class don't support '". $this->data['driver'] . "'" . PHP_EOL);
        }else{
            $this->setConnection($pdoInstance);
        }

    }

    private function makePDO(): ?\PDO {
        switch ($this->data['driver'])
        {
            case self::PDOdrivers['odbc']: {
                return $this->factory(new odbcFactory());
            }
            case self::PDOdrivers['mysql']: {
                return $this->factory(new mysqlFactory());
            }
            default: {
                return NULL;
            }
        }
        /**
         * foreach (self::PDOdrivers as $driver){
         * if($driver === $this->data['driver']){
         *      try{
         *          return $this->factory(new ($driver . 'Factory')());
         *      }catch(\Throwable $e)
         *      {
         *          error_log($e->getMessage());
         *      }catch(\Exception $e){
         *          error_log($e->getMessage());
         *      }
         *  }
         * }
         * return NULL;
         */
    }

    private function factory(PDOfactory $PDOfactory){
        return $PDOfactory->connect($this->data);
    }

    protected function checkDriver(string $driver){
        foreach (PDO::getAvailableDrivers() as $allowedDriver)
            if($allowedDriver === $driver)
                return TRUE;

        return FALSE;
    }

    public function setData(array $data){
        $this->data = $data;
    }

    public function getDara():array{
        return $this->data;
    }

    private function setConnection(\PDO $connection){
        $this->connection = $connection;
    }

    public function getConnection(): \PDO{
        return $this->connection;
    }
}