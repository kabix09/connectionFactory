<?php
require_once realpath("vendor/autoload.php");
use App\Connection;

    /* connection using odbc */
$odbcConfig = [
    'driver'    => 'odbc',
    'Driver'    => '{SQL Server}',
    'Server'    => 'localhost',
    'Database'  => 'test',
    'user'      => 'root',
    'password'  => '',
    'errmode'   => PDO::ERRMODE_EXCEPTION
];
    /* connection using mysql */
$mysqlConfig = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'dbname' => 'test',
    'charset' => 'utf8',
    'user' => 'root',
    'password' => '',
    "options" => array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)
];

    /* connect */
$conn = new Connection($odbcConfig);
$conn->connect();

    // execute simple query
$q = $conn->getConnection()->query('SELECT * FROM User');

write($q->fetch(PDO::FETCH_ASSOC));

function write($data){
    print_r('<pre>');
        var_dump($data);
    print_r('</pre>');
}
