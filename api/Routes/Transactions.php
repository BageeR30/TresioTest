<?php

namespace App\Routes;

use App\Database as DB;
use App\SumCalculator;
use DataTables\Database;
use DataTables\Editor;
use DataTables\Editor\Field;
use DataTables\Editor\Format;
use DataTables\Editor\Validate;

class Transactions implements BaseRoute
{
    public function handle()
    {
        $db = new Database([
            'type' => 'Mysql',
            'pdo' => DB::db(),
        ]);

        $editor = Editor::inst($db, 'transactions', ['account_id', 'id'])
            ->fields(
                Field::inst('transactions.id'),
                Field::inst('transactions.date')
                    ->validator( Validate::dateFormat('Y-m-d'))
                    ->getFormatter( Format::dateSqlToFormat('Y-m-d'))
                    ->setFormatter( Format::dateFormatToSql('Y-m-d')),
                Field::inst('transactions.amount')->validator(Validate::numeric()),
                Field::inst('accounts.name'),
                Field::inst('accounts.currency')
            )
            ->leftJoin('accounts', 'accounts.id', '=', 'transactions.account_id')
            ->process($_POST);

        (new SumCalculator())->handle();

        $editor->json();
    }
}
