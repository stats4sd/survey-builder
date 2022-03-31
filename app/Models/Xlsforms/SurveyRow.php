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
    //    'order',
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
        return $this->moduleVersion->core_version_id !== null;
    }

    public function surveyLabels()
    {
        return $this->hasMany(SurveyLabel::class, 'xls_survey_row_id');
    }

    public function moduleVersion()
    {
        return $this->belongsTo(ModuleVersion::class);
    }
}
