<?php

namespace App\Models\Xlsforms;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoiceList extends Model
{
    use HasFactory, CrudTrait;

    protected $guarded = [];

    public function choicesRows()
    {
        return $this->hasMany(ChoicesRow::class, 'list_name', 'list_name');
    }

    public function surveyRows()
    {
        return $this->hasMany(SurveyRow::class, 'choice_list', 'list_name');
    }
}
