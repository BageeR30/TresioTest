<?php

namespace App;

use Exception;

class SumCalculator
{
    public function handle()
    {
        Database::db()->beginTransaction();
        try {
            Database::db()->exec("TRUNCATE TABLE sums");

            Database::db()->exec("
                INSERT INTO sums (account_id, sum)
                SELECT t.account_id, SUM(t.amount) as sum
                FROM transactions t
                GROUP BY t.account_id
            ");

            Database::db()->commit();
        } catch (Exception $e) {
            Database::db()->rollBack();
            throw $e;
        }
    }
}
