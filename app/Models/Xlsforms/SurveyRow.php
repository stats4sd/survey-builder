<?php

namespace App\Models\Xlsforms;

use App\Models\Module;
use App\Models\ModuleVersion;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class SurveyRow extends Model
{
    protected $table = 'xls_survey_rows';
    protected $guarded = ['id'];


    public function surveyLabels()
    {
        return $this->hasMany(SurveyLabel::class, 'xls_survey_row_id');
    }

    public function moduleVersion()
    {
        return $this->belongsTo(ModuleVersion::class);
    }
}
