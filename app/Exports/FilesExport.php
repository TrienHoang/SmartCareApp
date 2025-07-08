<?php

namespace App\Exports;

use App\Models\FileUpload;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FilesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return FileUpload::select(
            'id',
            'file_name',
            'file_category',
            'size',
            'uploaded_at'
        )->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'File Name',
            'Category',
            'Size (bytes)',
            'Uploaded At',
        ];
    }
}
