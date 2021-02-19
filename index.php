<?php
require_once realpath("vendor/autoload.php");
use App\Connection;

define('MY_DB_DRIVERS', ['odbc', 'mysql']);

$configs = parse_ini_file('./src/database.ini', true);

    /* connect */
$conn = new Connection($configs[MY_DB_DRIVERS[1].'Config']);
$conn->connect();

    // execute simple query
$q = $conn->getConnection()->query('SELECT * FROM User');

write($q->fetch(PDO::FETCH_ASSOC));

function write($data){
    print_r('<pre>');
        var_dump($data);
    print_r('</pre>');
}
