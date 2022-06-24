<?php

namespace App\Models\Xlsforms;

use App\Models\ModuleVersion;
use App\Models\Xlsform;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoiceList extends Model
{
    use HasFactory, CrudTrait;

    protected $guarded = [];

    public function getModuleListLabelAttribute()
    {
        return $this->moduleVersion->full_label ?? 'No linked Module';
    }

    public function moduleVersion()
    {
        return $this->belongsTo(ModuleVersion::class);
    }


    public function xlsforms()
    {
        return $this->belongsToMany(Xlsform::class)->withPivot(['completed']);
    }


    // TODO: resolve relationship with real choice_list_id...
    public function choicesRows()
    {
        return $this->hasMany(ChoicesRow::class);
    }

    public function surveyRows()
    {
        return $this->hasMany(SurveyRow::class);
    }
}
