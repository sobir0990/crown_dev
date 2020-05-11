<?php


namespace common\components;

use DomainException;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Yii;
use yii\base\Component;

/**
 * Class ReportHelper
 * @package common\modules\report\helpers
 */
class ExcelHelper extends Component
{
    /**
     * @param $alpha
     * @param $position
     * @return array
     */
    public static function getAlphaIncDecPosition($alpha, $position)
    {
        $index = self::getAlphaIndex($alpha);
        if ($position == 0) {
            return $alpha;
        }
        $index += $position;
        return strtoupper(self::alphas($index));
    }

    /**
     * @param $alpha
     * @return int|string
     */
    public static function getAlphaIndex($alpha)
    {
        $alphas = self::alphas();
        foreach ($alphas as $index => $a) {
            if (strtolower($alpha) == strtolower($a)) {
                return $index++;
            }
        }
        throw new DomainException("Alhpas index not founded");
    }

    /**
     * @param null $index
     * @return array
     */
    public static function alphas($index = null)
    {
        $alphas = self::createColumnsArray("ZZ");
        if ($index !== null) {
            $index--;
            return $alphas[$index];
        }
        return $alphas;
    }

    /**
     * @param $end_column
     * @param string $first_letters
     * @return array
     */
    static function createColumnsArray($end_column, $first_letters = '')
    {
        $columns = array();
        $length = strlen($end_column);
        $letters = range('A', 'Z');

        // Iterate over 26 letters.
        foreach ($letters as $letter) {
            // Paste the $first_letters before the next.
            $column = $first_letters . $letter;

            // Add the column to the final array.
            $columns[] = $column;

            // If it was the end column that was added, return the columns.
            if ($column == $end_column)
                return $columns;
        }

        // Add the column children.
        foreach ($columns as $column) {
            // Don't itterate if the $end_column was already set in a previous itteration.
            // Stop iterating if you've reached the maximum character length.
            if (!in_array($end_column, $columns) && strlen($column) < $length) {
                $new_columns = self::createColumnsArray($end_column, $column);
                // Merge the new columns which were created with the final columns array.
                $columns = array_merge($columns, $new_columns);
            }
        }

        return $columns;
    }

    public static function getHighCords($activeSheet)
    {
        /** @var $activeSheet \PhpOffice\PhpSpreadsheet\Worksheet */
        $res['highRow'] = $activeSheet->getHighestRow();
        $res['highCol'] = $activeSheet->getHighestColumn();
        $res['highCord'] = $res['highCol'] . $res['highRow'];
        return $res;
    }

    /**
     * @param Worksheet $sheet
     * @param int $startCol
     * @throws Exception
     */
    public static function justifyCols(Worksheet $sheet, $startCol = 1)
    {
        $col = Coordinate::columnIndexFromString($sheet->getHighestColumn());
        for ($i = $startCol; $i < $col; $i++) {
            $sheet->getColumnDimension(self::col($i))->setAutoSize(true);
        }
    }

    public static function col(int $col): string
    {
        return Coordinate::stringFromColumnIndex($col);
    }

    /**
     * @param Worksheet $sheet
     * @param $cellCoordinate
     * @throws Exception
     */
    public static function border(Worksheet $sheet, $cellCoordinate)
    {
        $sheet->getStyle($cellCoordinate)
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['argb' => 'FF000000'],
                    ],
                ]
            ]);
    }

    /**
     * @param Worksheet $sheet
     * @param $cellCoordinate
     * @throws Exception
     */
    public static function align(Worksheet $sheet, $cellCoordinate)
    {
        $sheet->getStyle($cellCoordinate)
            ->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ]
                ]
            );
    }

    /**
     * @param Worksheet $sheet
     * @param $cellCoordinate
     * @throws Exception
     */
    public static function text(Worksheet $sheet, $cellCoordinate, $size = 10, $bold = true, $wrapText = true, $name = "Arial")
    {
        $sheet->getStyle($cellCoordinate)
            ->applyFromArray([
                    'font'      => ['size' => $size, 'bold' => $bold, 'name' => $name],
                    'alignment' => ['wrapText' => $wrapText],
                ]
            );
    }
    /**
     * @param Worksheet $sheet
     * @param $cellCoordinate
     * @throws Exception
     */
    public static function autosize(Worksheet $sheet, $columnStr)
    {
        $sheet->get($columnStr)->setAutoSize(true);
    }
    /**
     * @param Spreadsheet $sheet
     * @param string      $fileName
     * @return string|bool Absolute path to generated file
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public static function export(Spreadsheet $sheet, string $fileName)
    {
        $path = Yii::getAlias( '@static/report/');
        $file = $path . $fileName;

        File::setUseUploadTempDirectory(true);
        IOFactory::createWriter($sheet, 'Xls')->save($file);

        if (file_exists($file)) {
            return $file;
        }
        return false;
    }

}
