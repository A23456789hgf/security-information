<?php

namespace Modules\Differences\Imports;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ChunkReadFilter implements IReadFilter
{
    private $startRow = 0;
    private $endRow = 0;

    public function __construct($startRow, $chunkSize)
    {
        $this->startRow = $startRow;
        $this->endRow = $startRow + $chunkSize;
    }

    public function readCell($columnAddress, $row, $worksheetName = '')
    {
        // Always read the header row (row 1)
        if ($row == 1 || ($row >= $this->startRow && $row < $this->endRow)) {
            return true;
        }
        return false;
    }
}

class ExcelImporter
{
    public function getHeaderAndTotalRows(string $filePath): array
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        
        $highestRow = $sheet->getHighestRow();
        
        // Read first row for headers
        $headers = [];
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $val = $sheet->getCellByColumnAndRow($col, 1)->getValue();
            if ($val !== null && $val !== '') {
                $headers[$col] = trim($val);
            }
        }

        return [
            'headers' => $headers,
            'total_rows' => max(0, $highestRow - 1) // subtract header row
        ];
    }

    public function readChunk(string $filePath, int $startRow, int $chunkSize): array
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        
        // Apply chunk filter to read only the required range of rows
        $filter = new ChunkReadFilter($startRow, $chunkSize);
        $reader->setReadFilter($filter);
        
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        
        $rows = [];
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        
        // First row is headers
        $headers = [];
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $val = $sheet->getCellByColumnAndRow($col, 1)->getValue();
            if ($val !== null && $val !== '') {
                $headers[$col] = trim($val);
            }
        }

        // Read chunk rows
        $endRow = min($startRow + $chunkSize - 1, $highestRow);
        for ($row = $startRow; $row <= $endRow; $row++) {
            $rowData = [];
            $hasData = false;
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                if (isset($headers[$col])) {
                    $val = $sheet->getCellByColumnAndRow($col, $row)->getValue();
                    $rowData[$headers[$col]] = $val !== null ? trim($val) : '';
                    if ($val !== null && trim($val) !== '') {
                        $hasData = true;
                    }
                }
            }
            if ($hasData) {
                $rows[] = $rowData;
            }
        }

        return $rows;
    }
}
