<?php

namespace common\extend\excel;

class SpreadExcel
{
    /**
     * @param $data
     * @param $path
     */
    public static function exportExcelToFile($data, $path)
    {
        array_unshift($data['data'], $data['info']);
        $writer = new XLSXWriter();
        $writer->setAuthor('DELCACHE');
        foreach ($data['data'] as $row) {
            $writer->writeSheetRow('Sheet1', $row);
        }
        $writer->writeToFile($path);
    }

    public static function exportExcel($data)
    {
        array_unshift($data['data'], $data['info']);
        $writer = new XLSXWriter();
        $writer->setAuthor('DELCACHE');
        foreach ($data['data'] as $row) {
            $writer->writeSheetRow('Sheet1', $row);
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $data['table_name'] . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->writeToStdOut();
        exit;
    }
}