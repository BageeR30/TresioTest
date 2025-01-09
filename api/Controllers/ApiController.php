<?php

namespace App\Controllers;

use App\Routes\GetTransactions;
use App\Routes\Sums;
use App\Routes\TotalChart;
use App\Routes\Transactions;
use App\Routes\Upload;

class ApiController
{
    public function handle($uri)
    {
        header('Content-Type: application/json');

        $path = parse_url($uri, PHP_URL_PATH);

        switch ($path) {
            case '/api/upload':
                return (new Upload())->handle();

            case '/api/transactions':
                return (new GetTransactions())->handle();

            case '/api/sums':
                (new Sums())->handle();
                break;

            case '/api/total-chart':
                return (new TotalChart())->handle();

            case '/api/transactions-dt':
                (new Transactions())->handle();
                break;

            default:
                http_response_code(404);

                return json_encode(['status' => 'error', 'message' => 'Endpoint not found']);
        }
    }
}
