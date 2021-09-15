<?php

namespace App\Exports;

use App\Models\Xlsform;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class XlsSettingsExport implements FromCollection, WithTitle
{

    public function __construct (public Xlsform $xlsform){}

    public function collection()
    {
        return collect([
            [
                'form_title',
                'form_id',
                'default_language',
                'instance_name',
                'version',
            ],
            [
                $this->xlsform->title,
                Str::slug($this->xlsform->title),
                'English (en)',
                'concat(${respondentname}," - ", ${village}," - ", ${starttime_auto})',
                Str::slug(Carbon::now()->toISOString()),
            ],
        ]);
    }

    public function title (): string
    {
        return "settings";
    }

}