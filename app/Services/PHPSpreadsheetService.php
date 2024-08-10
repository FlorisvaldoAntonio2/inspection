<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PHPSpreadsheetService
{
    public static function createSpreadsheet()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');

        $writer = new Xlsx($spreadsheet);
        $writer->save('hello_world.xlsx');
    }

    // Abrir planilha
    public static function openSpreadsheet($path, $sheetName = null)
    {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
        if ($sheetName) {
            $spreadsheet->setActiveSheetIndexByName($sheetName);
        }
        return $spreadsheet;
    }

    // Salvar planilha
    public static function saveSpreadsheet($spreadsheet, $path = '/storage/app/public/grr-new.xlsx')
    {
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);
    }

    // Adicione outros métodos conforme necessário
}