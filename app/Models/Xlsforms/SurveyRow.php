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
    protected $appends = [
        'english_label',
        'is_core',
    ];
    //protected int $order = 0;

    /**
     * Get boolean to see if this survey row is core or not
     * Used to determine priority when 2 survey rows have the same name value (optional module rows will override core rows)
     * @return bool
     */
    public function getIsCoreAttribute()
    {
        if ($this->moduleVersion) {
            return $this->moduleVersion->module->core;
        }

        return false;
    }

    public function getEnglishLabelAttribute()
    {
        $label = $this->surveyLabels()->where('language_id', 'en')->first();

        return $label->label ?? '';
    }

    public function surveyLabels()
    {
        return $this->hasMany(SurveyLabel::class, 'xls_survey_row_id');
    }

    public function moduleVersion()
    {
        return $this->belongsTo(ModuleVersion::class);
    }

    public function choiceList()
    {
        return $this->belongsTo(ChoiceList::class, 'choice_list', 'list_name');
    }
}
