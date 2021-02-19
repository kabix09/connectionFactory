<?php declare(strict_types = 1);
namespace App\AbstractFactory;

class OdbcFactory extends PDOFactory
{
    protected const DATA_KEYS = ['driver', 'Driver', 'Server', 'Database'];
}