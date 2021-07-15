<?php

namespace App\Models\Xlsforms;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class SurveyRow extends Model
{
    protected $table = 'xls_survey_rows';
    protected $guarded = ['id'];


    public function surveyLabels()
    {
        return $this->hasMany(SurveyLabel::class, 'xls_survey_row_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
