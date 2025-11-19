<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'third_party/PhpSpreadsheet/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

if (!function_exists('export_excel')) {
    function export_excel($data, $filename = 'export', $title = 'Export Data')
    {
        if (empty($data) || !is_array($data)) {
            return;
        }

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set title if provided
        if (!empty($title)) {
            $sheet->setCellValue('A1', $title);
            $sheet->mergeCells('A1:' . getColumnLetter(count($data[0])) . '1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $startRow = 3;
        } else {
            $startRow = 1;
        }

        // Add headers
        if (!empty($data)) {
            $headers = array_keys($data[0]);
            $column = 'A';

            foreach ($headers as $header) {
                $sheet->setCellValue($column . $startRow, ucwords(str_replace('_', ' ', $header)));
                $sheet->getStyle($column . $startRow)->getFont()->setBold(true);
                $sheet->getStyle($column . $startRow)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFE0E0E0');
                $sheet->getStyle($column . $startRow)->getBorders()
                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $column++;
            }

            // Add data rows
            $row = $startRow + 1;
            foreach ($data as $item) {
                $column = 'A';
                foreach ($item as $value) {
                    $sheet->setCellValue($column . $row, $value);
                    $sheet->getStyle($column . $row)->getBorders()
                        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $column++;
                }
                $row++;
            }

            // Auto size columns
            $lastColumn = $sheet->getHighestColumn();
            for ($col = 'A'; $col <= $lastColumn; $col++) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        // Create writer and output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}

if (!function_exists('getColumnLetter')) {
    function getColumnLetter($num)
    {
        $letters = '';
        while ($num > 0) {
            $num--;
            $letters = chr(65 + ($num % 26)) . $letters;
            $num = (int)($num / 26);
        }
        return $letters;
    }
}

if (!function_exists('read_excel_file')) {
    function read_excel_file($file_path)
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file_path);
        $sheet = $spreadsheet->getActiveSheet();

        $data = [];
        foreach ($sheet->getRowIterator() as $row) {
            $rowData = [];
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);

            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }
            $data[] = $rowData;
        }

        return $data;
    }
}
