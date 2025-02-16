<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private $rowNumber = 0;
    protected $filterData;

    public function __construct($filterData)
    {
        $this->filterData = $filterData;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        try {
            $query = Customer::orderBy('name', 'asc');

            if (isset($this->filterData['gender'])) {
                $query->where('gender', $this->filterData['gender']);
            }
            if (isset($this->filterData['customerCountry'])) {
                $query->where('country', $this->filterData['customerCountry']);
            }

            return $query->get();

        } catch (\Exception $e) {
            Log::error('Failed to get data: ' . $e->getMessage());    
            return false;
        }
    }

    public function headings(): array
    {
        return [
            "No.", 
            "Name", 
            "Email", 
            "Phone Number", 
            "POB", 
            "DOB",
            "Gender",
            "Country",
        ];
    }

    public function map($row): array
    {
        return [
            ++$this->rowNumber,
            $row->name,
            $row->email,
            $row->phone,
            $row->birth_place,
            Carbon::parse($row->birth_date)->format('d-m-Y'),
            $row->gender,
            $row->country,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Atur Wrap Text dan Text Alignment
            'A' => ['alignment' => [
                'wrapText' => true,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
            ]],
            'B' => ['alignment' => ['wrapText' => true, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP]],
            'C' => ['alignment' => ['wrapText' => true, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP]],
            'D' => ['alignment' => ['wrapText' => true, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP]],
            'E' => ['alignment' => ['wrapText' => true, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP]],
            'F' => ['alignment' => ['wrapText' => true, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP]],
            'G' => ['alignment' => ['wrapText' => true, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP]],
            'H' => ['alignment' => ['wrapText' => true, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP]],
        ];
    }
}
