<?php declare(strict_types = 1);
namespace ConnectionFactory\AbstractFactory;

final class MySqlFactory extends PDOFactory
{
    protected const DATA_KEYS = ['driver', 'host', 'dbname'];
}