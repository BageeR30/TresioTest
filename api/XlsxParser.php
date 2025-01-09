<?php

namespace App;

use PhpOffice\PhpSpreadsheet\IOFactory;

class XlsxParser
{
    public function read($filePath)
    {
        $spreadsheet = IOFactory::load($filePath);

        $array = $spreadsheet->getActiveSheet()->toArray();

        array_shift($array);

        return $array;
    }
}
