<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-02-06
 * Time: 22:18
 */

namespace Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Connectors;

use Illuminate\Database\Connection;
use Illuminate\Database\Connectors\ConnectionFactory as BaseConnectionFactory;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\MySqlConnection;

class ConnectionFactory extends BaseConnectionFactory
{
    protected function createConnection($driver, $connection, $database, $prefix = '', array $config = [])
    {
        if ($resolver = Connection::getResolver($driver)) {
            return $resolver($connection, $database, $prefix, $config);
        }

        if ($driver == 'mysql') {
            return new MySqlConnection($connection, $database, $prefix, $config);
        }

        return parent::createConnection($driver, $connection, $database, $prefix, $config);
    }
}