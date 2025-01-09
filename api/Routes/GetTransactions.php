<?php

namespace App\Routes;

use App\Database;
use PDO;

class GetTransactions implements BaseRoute
{
    public function handle()
    {
        $result = Database::db()->query("
            SELECT t.id, a.name as account, amount, currency, date 
            FROM transactions t 
                join testdb.accounts a on a.id = t.account_id");

        $transactions = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $transactions[] = array_merge($row, ['amount' => (float)$row['amount']]);
        }

        return json_encode(['data' => $transactions]);
    }
}
