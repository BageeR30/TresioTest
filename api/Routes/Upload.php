<?php

namespace App\Routes;

use App\Database;
use App\SumCalculator;
use App\XlsxParser;
use Exception;
use PDO;

class Upload implements BaseRoute
{
    public function handle()
    {
        $file = $_FILES['file']['tmp_name'];
        $rows = (new XlsxParser())->read($file);

        Database::db()->beginTransaction();

        try {
            $this->truncateTables();
            $accountIds = $this->insertAccountsAndFetchIds($rows);
            $this->insertTransactions($rows, $accountIds);
            $transactions = $this->fetchTransactions();

            Database::db()->commit();

            (new SumCalculator())->handle();

            return json_encode(['data' => $transactions]);
        } catch (Exception $e) {
            Database::db()->rollback();
            http_response_code(500);

            return [];
        }
    }

    private function truncateTables()
    {
        Database::db()->query('TRUNCATE TABLE transactions');
        Database::db()->query("TRUNCATE TABLE sums");
        Database::db()->query('DELETE FROM accounts');
    }

    private function insertAccountsAndFetchIds(array $rows)
    {
        $accountIds = [];
        $currencies = array_column($rows, 3);
        $stmt = Database::db()->prepare("INSERT INTO accounts (name, currency) VALUES (?, ?)");
        foreach (array_unique(array_column($rows, 0)) as $key => $name) {
            $stmt->execute([$name, $currencies[$key]]);
        }
        $stmt->closeCursor();

        $result = Database::db()->query("SELECT name, id FROM accounts");
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $accountIds[$row['name']] = $row['id'];
        }

        return $accountIds;
    }

    private function insertTransactions(array $rows, array $accountIds)
    {
        $query = Database::db()->prepare(
            "INSERT INTO transactions (id, account_id, amount, date) VALUES (:id, :account_id, :amount, :date)"
        );

        foreach ($rows as $row) {
            $query->execute([
                ':id' => $row[1],
                ':account_id' => $accountIds[(string)$row[0]],
                ':amount' => $row[2],
                ':date' => $row[4],
            ]);
        }

        $query->closeCursor();
    }

    private function fetchTransactions()
    {
        $result = Database::db()->query(
            "SELECT t.id, a.name as account, CAST(amount AS DECIMAL) as amount, date 
            FROM transactions t 
            LEFT JOIN accounts a on a.id = t.account_id"
        );

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}
