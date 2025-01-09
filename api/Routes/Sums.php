<?php

namespace App\Routes;

use DataTables\Database;
use DataTables\Editor;
use DataTables\Editor\Field;
use DataTables\Editor\Validate;
use App\Database as DB;

class Sums implements BaseRoute
{
    public function handle() {
        $db = new Database([
            'type' => 'Mysql',
            'pdo' => DB::db(),
        ]);

        Editor::inst($db, 'sums', 'sums.account_id')
            ->fields(
                Field::inst('sums.account_id'),
                Field::inst('accounts.name'),
                Field::inst('accounts.currency'),
                Field::inst('sums.sum')->validator(Validate::numeric()),
                Field::inst('sums.starting_balance')->validator(Validate::numeric())
            )
            ->leftJoin('accounts', 'accounts.id', '=', 'sums.account_id')
            ->process($_POST)
            ->json();
    }
}
