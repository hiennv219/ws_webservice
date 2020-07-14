<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-07-02
 * Time: 11:43
 */

namespace Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database;

use Illuminate\Database\MySqlConnection as BaseMySqlConnection;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Grammars\MySqlGrammar;

class MySqlConnection extends BaseMySqlConnection
{
    public function getSchemaBuilder()
    {
        $schemaBuilder = parent::getSchemaBuilder();
        $schemaBuilder->blueprintResolver(function ($table, $callback) {
            return new Blueprint($table, $callback);
        });

        return $schemaBuilder;
    }

    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new MySqlGrammar());
    }
}