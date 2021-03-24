<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImportCPMController extends Controller
{

  public function parseExcelCPM(Request $request)
  {


    $tmp_name = $_FILES["file"]["tmp_name"];
    $name = basename($_FILES["file"]["name"]);
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    if (empty($name)) {
      throw new \Exception("Error on uploading file");
    }

    if ($ext === 'xlsx' || $ext === 'xls') {
      $reader = IOFactory::createReader('Xlsx');
      $reader->setReadDataOnly(false);
      $spreadsheet = $reader->load($tmp_name);

      $sheet = $spreadsheet->getActiveSheet();
      $pages = array();
      foreach ($sheet->getRowIterator() as $index => $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(FALSE);
        $cells = [];
        foreach ($cellIterator as $cell) {
          $cells[] = @$cell->getFormattedValue();
        }
        if ($index === 1) {
          $pages['headers'] = $cells;
        }
        $pages['data'][] = $cells;
      }

      $pages['data'] = self::checkDuplicates($pages['data']);

      return $pages;
    }
  }

  public function checkDuplicates(&$rows)
  {

    $prev = null;
    $dups = array(); //indexes
    foreach ($rows as $index => $row) {
      if ($prev === null) {
        $prev = $row;
      } else {
        $arraysAreEqual = $prev === $row;
        if ($arraysAreEqual) {
          $dups[$index - 1] = true;
          $dups[$index] = true;
        } else {
          if (!empty($dups)) {
            $count = count($dups);
            foreach ($dups as $dindex => $val) {
              $rows[$dindex]['dups'] = $count;
            }
            $dups = array();
          }
        }
        $prev = $row;
      }

    }
    return $rows;
  }

  public function parseTXTCPM(Request $request)
  {

    $contents = file_get_contents(base_path() . '/public/spendings.txt');
    $lines = explode(PHP_EOL, $contents);
    $pages = array();
    $pages['headers'] = explode('|', $lines[0]);
    $pages['headers'][] = 'dist_seq_nbr';

    array_shift($lines);

    foreach ($lines as $i => $item) {
      $data = explode('|', $item);
      if (isset($data[5]) && !empty($data[5])) {
        $datetime = new DateTime(trim(str_replace('-', '/', $data[5])));

        if ($datetime->format('Y') == 2017) {
          $pages['data'][] = array_map('trim', $data);
          if (count($pages['data']) > 50) {
            break;
          }
        }
      }
    }

    return $pages;
  }
}
