<?php

namespace App\Routes;

use App\Database;
use PDO;

class TotalChart implements BaseRoute
{
    public function handle()
    {
        $data = Database::db()->query('
                    SELECT
                date,
                daily_sum,
                @running_total := @running_total + daily_sum AS cumulative_sum
            FROM (
                SELECT
                    date,
                    SUM(amount) AS daily_sum
                FROM
                    transactions
                GROUP BY
                    date
                ORDER BY
                    date
            ) AS daily_sums,
            (SELECT @running_total := 0) AS vars_init
            ORDER BY
                date;
        ')->fetchAll(PDO::FETCH_ASSOC);

        return json_encode(['data' => $data]);
    }
}
