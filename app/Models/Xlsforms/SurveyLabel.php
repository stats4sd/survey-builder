<?php

namespace App\Models\Xlsforms;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class SurveyLabel extends Model
{
    protected $table = 'xls_survey_labels';
    protected $guarded = ['id'];


    public function surveyRow()
    {
        return $this->belongsTo(SurveyRow::class, 'xls_survey_row_id');
    }
}
