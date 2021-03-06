<?php

namespace App\Exports;

use App\Models\Xlsform;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class XlsSettingsExport implements FromCollection, WithTitle
{

    public Xlsform $xlsform;

    public function __construct (Xlsform $xlsform){
        $this->xlsform = $xlsform;
    }

    public function collection()
    {
        return collect([
            [
                'form_title',
                'form_id',
                'default_language',
                'instance_name',
                'version',
                'allow_choice_duplicates',
            ],
            [
                Str::slug($this->xlsform->name),
                Str::slug($this->xlsform->name),
                'English (en)',
                'concat(${respondentname}," - ", ${village}," - ", ${starttime_auto})',
                Str::slug(Carbon::now()->toISOString()),
                'yes',
            ],
        ]);
    }

    public function title (): string
    {
        return "settings";
    }

}
