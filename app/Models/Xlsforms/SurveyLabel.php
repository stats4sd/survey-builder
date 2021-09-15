<?php

namespace App\Models\Xlsforms;

use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class SurveyLabel extends Model
{
    protected $table = 'xls_survey_labels';
    protected $guarded = ['id'];


    public function surveyRow()
    {
        return $this->belongsTo(SurveyRow::class, 'xls_survey_row_id');
    }

    public function language ()
    {
       return $this->belongsTo(Language::class);
    }

}
