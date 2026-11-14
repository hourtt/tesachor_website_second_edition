<?php

namespace App\Database;
use Illuminate\Database\PostgresConnection as BasePostgresConnection;

class PostgresConnection extends BasePostgresConnection
{

    public function prepareBindings(array $bindings)
    {
        foreach ($bindings as $key => $value) {
            if (\is_bool($value)) {
                $bindings[$key] = $value ? 'true' : 'false';
            }
        }
        return parent::prepareBindings($bindings);
    }
}
