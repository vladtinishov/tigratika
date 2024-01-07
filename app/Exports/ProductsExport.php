<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithCalculatedFormulas
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Название',
            'Ссылка',
            'Изображение',
            'Цена',
            'Старая цена',
            'Валюта',
            'Вендор',
            'Активен',
            'Категория',
            'Подкатегороия',
            'Под-подкатегороия',
        ];
    }

    private function prepareData()
    {
        foreach ($this->data as &$row) {
            unset($row['category_id']);
        }

        return $this->data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = $this->prepareData();
        return collect([$data]);
    }
}
