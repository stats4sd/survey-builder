<?php

namespace App\Models;

use App\Models\Xlsforms\SurveyLabel;
use App\Models\Xlsforms\ChoicesLabel;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Language extends Model
{
    use CrudTrait;


    protected $table = 'languages';
    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';

    public function modules()
    {
        return $this->belongsToMany(Module::class);
    }

    public function surveyLabels ()
    {
       return $this->hasMany(SurveyLabel::class);
    }

    public function choicesLabels ()
    {
       return $this->hasMany(ChoicesLabel::class);
    }

    public function xlsforms()
    {
        return $this->belongsToMany(Xlsform::class);
    }


}
