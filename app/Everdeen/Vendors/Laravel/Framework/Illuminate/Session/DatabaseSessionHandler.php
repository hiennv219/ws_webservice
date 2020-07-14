<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-06
 * Time: 16:43
 */

namespace Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Session;

use Illuminate\Container\Container;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Session\DatabaseSessionHandler as BaseDatabaseSessionHandler;

class DatabaseSessionHandler extends BaseDatabaseSessionHandler
{
    /**
     * Create a new database session handler instance.
     *
     * @param  \Illuminate\Database\ConnectionInterface $connection
     * @param  string $table
     * @return void
     */
    public function __construct(ConnectionInterface $connection, $table, $minutes, Container $container = null)
    {
        parent::__construct($connection, $table, $minutes, $container);
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        parent::write($sessionId, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        parent::destroy($sessionId);
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime)
    {
        parent::gc($lifetime);
    }
}