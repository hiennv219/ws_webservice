<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-07-02
 * Time: 12:25
 */

namespace Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Grammars;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\MySqlGrammar as BaseMySqlGrammar;
use Illuminate\Support\Fluent;

class MySqlGrammar extends BaseMySqlGrammar
{
    public function compileCreate(Blueprint $blueprint, Fluent $command, Connection $connection)
    {
        $sql = parent::compileCreate($blueprint, $command, $connection);

        if (isset($blueprint->rowFormat)) {
            $sql .= ' row_format = ' . $blueprint->rowFormat;
        } elseif (!is_null($rowFormat = $connection->getConfig('row_format'))) {
            $sql .= ' row_format = ' . $rowFormat;
        }

        return $sql;
    }
}