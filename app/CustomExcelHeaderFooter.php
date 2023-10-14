<?php

namespace App;

use PhpOffice\PhpSpreadsheet\Style\Alignment;

trait CustomExcelHeaderFooter
{
    /**
     * @param $event
     * @param array $cell_array
     * @param array $head_array_number
     * @param array $footer_array_number
     */
    public function excelHeaderFooter($event, array $cell_array, array $head_array_number, array $footer_array_number, $footer_exist = 1)
    {
        $headCellRange = $cell_array[0] . $head_array_number[0] . ':' . $cell_array[count($cell_array) - 1] . $head_array_number[count($head_array_number) - 1];


        // For Header
        $event->sheet->getDelegate()->getStyle($headCellRange)->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle($headCellRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e8ffde');
        $event->sheet->getDelegate()->getStyle($headCellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $event->sheet->getDelegate()->getStyle($headCellRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $event->sheet->getDelegate()->getStyle($cell_array[0] . $head_array_number[0] . ':' . $cell_array[count($cell_array) - 1] . $head_array_number[1])->getFont()->setSize(14);
        $event->sheet->getDelegate()->getStyle($cell_array[0] . $head_array_number[2] . ':' . $cell_array[count($cell_array) - 1] . $head_array_number[count($head_array_number) - 1])->getFont()->setSize(12);

        if ($footer_exist) {
            $footerCellRange = $cell_array[0] . $footer_array_number[0] . ':' . $cell_array[count($cell_array) - 1] . $footer_array_number[count($footer_array_number) - 1];
            // For Footer
            $event->sheet->getDelegate()->getStyle($footerCellRange)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle($footerCellRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('fcf8d9');
            $event->sheet->getDelegate()->getStyle($cell_array[0] . $footer_array_number[0] . ':' . $cell_array[0] . $footer_array_number[count($footer_array_number) - 1])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle($cell_array[0] . $footer_array_number[count($footer_array_number) - 1])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        }

        // Header and Footer section border
        foreach ($cell_array as $cell) {
            foreach ($head_array_number as $head_no) {
                $event->sheet->styleCells(
                    $cell . $head_no,
                    [
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]
                );
            }
            if ($footer_exist) {
                foreach ($footer_array_number as $head_no) {
                    $event->sheet->styleCells(
                        $cell . $head_no,
                        [
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '00000000'],
                                ],
                            ],
                        ]
                    );
                }
            }
        }
        // Main Body section border
        foreach ($cell_array as $cell) {
            for ($cell_no = $head_array_number[count($head_array_number) - 1] + 1; $cell_no < $footer_array_number[0]; $cell_no++) {
                $event->sheet->styleCells(
                    $cell . $cell_no,
                    [
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '00000000'],
                            ],
                        ],
                    ]
                );
            }
        }
    }
}
