<?php

namespace App\Exports;

use App\Models\Survey;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SurveyExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    protected $survey;

    public function __construct(Survey $survey)
    {
        $this->survey = $survey;
    }

    public function array(): array
    {
        $data = [];
        
        foreach ($this->survey->station->fuels as $fuel) {
            $fuelHeader = [$fuel->name . ' ' . $fuel->capacity . 'LT'];
            for ($day = 1; $day <= $this->survey->days_in_month; $day++) {
                $fuelHeader[] = '';
                $fuelHeader[] = '';
            }
            $data[] = $fuelHeader;

            for ($counter = 1; $counter <= $fuel->counter; $counter++) {
                $counterRow = ['Contador ' . $counter];
                for ($day = 1; $day <= $this->survey->days_in_month; $day++) {
                    $counterRow[] = $this->survey->readings[$fuel->id]['counters'][$counter-1]['values'][$day] ?? '0';
                    $counterRow[] = $this->survey->readings[$fuel->id]['counters'][$counter-1]['totals'][$day] ?? '0';
                }
                $data[] = $counterRow;
            }

            $litrosRow = ['Litros Vendidos'];
            for ($day = 1; $day <= $this->survey->days_in_month; $day++) {
                $totalLitrosVendidos = 0;
                if (isset($this->survey->readings[$fuel->id]['counters'])) {
                    for ($i = 0; $i < $fuel->counter; $i++) {
                        if (isset($this->survey->readings[$fuel->id]['counters'][$i]['totals'][$day])) {
                            $totalLitrosVendidos += abs(intval($this->survey->readings[$fuel->id]['counters'][$i]['totals'][$day]));
                        }
                    }
                }
                $litrosRow[] = '-';
                $litrosRow[] = $totalLitrosVendidos;
            }
            $data[] = $litrosRow;

            $sondagemRow = ['Sondagem'];
            for ($day = 1; $day <= $this->survey->days_in_month; $day++) {
                $sondagemRow[] = $this->survey->readings[$fuel->id]['sounding']['values'][$day] ?? '0';
                $sondagemRow[] = $this->survey->readings[$fuel->id]['sounding']['totals'][$day] ?? '0';
            }
            $data[] = $sondagemRow;

            $stockRow = ['Entrada Stock'];
            for ($day = 1; $day <= $this->survey->days_in_month; $day++) {
                $stockValue = '';
                if (isset($this->survey->readings[$fuel->id]['stock_entries']['values'][$day]) && 
                    $this->survey->readings[$fuel->id]['stock_entries']['values'][$day] > 0) {
                    $stockValue = '+' . $this->survey->readings[$fuel->id]['stock_entries']['values'][$day];
                } else {
                    $stockValue = '-';
                }
                $stockRow[] = $stockValue;
                $stockRow[] = '-';
            }
            $data[] = $stockRow;

            $resultadoRow = ['Resultado A-B'];
            for ($day = 1; $day <= $this->survey->days_in_month; $day++) {
                $totalLitrosVendidos = 0;
                if (isset($this->survey->readings[$fuel->id]['counters'])) {
                    for ($i = 0; $i < $fuel->counter; $i++) {
                        if (isset($this->survey->readings[$fuel->id]['counters'][$i]['totals'][$day])) {
                            $totalLitrosVendidos += abs(intval($this->survey->readings[$fuel->id]['counters'][$i]['totals'][$day]));
                        }
                    }
                }
                $sondagem = abs(intval($this->survey->readings[$fuel->id]['sounding']['totals'][$day] ?? 0));
                $resultadoAB = $totalLitrosVendidos - $sondagem;
                
                $resultadoRow[] = '-';
                $resultadoRow[] = $resultadoAB;
            }
            $data[] = $resultadoRow;

            $data[] = [''];
        }

        return $data;
    }

    public function headings(): array
    {
        $headings = [''];
        
        for ($day = 1; $day <= $this->survey->days_in_month; $day++) {
            $headings[] = 'Dia ' . $day;
            $headings[] = '';
        }
        
        $subHeadings = [''];
        for ($day = 1; $day <= $this->survey->days_in_month; $day++) {
            $subHeadings[] = 'Valores';
            $subHeadings[] = 'Total';
        }

        return [$headings, $subHeadings];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
            2 => ['font' => ['bold' => true]],
            'A:A' => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        $widths = ['A' => 20];
        
        $column = 'B';
        for ($day = 1; $day <= $this->survey->days_in_month; $day++) {
            $widths[$column] = 10;
            $column++;
            $widths[$column] = 10;
            $column++;
        }
        
        return $widths;
    }
}