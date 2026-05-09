<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CrmContactsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        private readonly Collection $rows
    ) {}

    public function collection(): Collection
    {
        return $this->rows;
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<int, mixed>
     */
    public function map($row): array
    {
        return [
            $row['name'] ?? '',
            $row['phone'] ?? '',
            $row['email'] ?? '',
            $row['country'] ?? '',
            $row['status'] ?? '',
            $row['services'] ?? '',
            $row['notes'] ?? '',
            $row['created_by'] ?? '',
            $row['updated_by'] ?? '',
            $row['archived'] ?? '',
            $row['archived_by'] ?? '',
            $row['created_at'] ?? '',
            $row['updated_at'] ?? '',
        ];
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Name',
            'Phone',
            'Email',
            'Country',
            'Status',
            'Services',
            'Notes',
            'Created By',
            'Updated By',
            'Archived',
            'Archived By',
            'Created At',
            'Updated At',
        ];
    }
}
