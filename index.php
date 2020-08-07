<?php
require_once realpath("vendor/autoload.php");
use App\Connection;

    /* connection using odbc */
$odbcConfig = [
    'driver'    => 'odbc',
    'Driver'    => '{SQL Server}',
    'Server'    => 'localhost', //localhost
    'Database'  => 'myDB',
    'user'      => 'root',
    'password'  => '',
    'errmode'   => PDO::ERRMODE_EXCEPTION
];
    /* connection using mysql */
$mysqlConfig = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'dbname' => 'myDB',
    'charset' => 'utf8',
    'user' => 'root',
    'pass' => ''
];

    /* connect */
$conn = Connection::init($odbcConfig);
$conn::PDOconnect();

    // execute simple query
$q = $conn->getConnection()->query('SELECT * FROM "User"');
write($q->fetch(PDO::FETCH_ASSOC));


function write($data){
    print_r('<pre>');
        var_dump($data);
    print_r('</pre>');
}
