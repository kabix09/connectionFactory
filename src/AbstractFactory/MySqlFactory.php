<?php declare(strict_types = 1);
namespace App\AbstractFactory;

class MySqlFactory extends PDOFactory
{
    protected const DATA_KEYS = ['driver', 'host', 'dbname'];
}