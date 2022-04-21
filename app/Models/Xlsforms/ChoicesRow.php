<?php

namespace App\Models\Xlsforms;

use App\Models\Module;
use App\Models\ModuleVersion;
use App\Models\Xlsforms\ChoicesLabel;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class ChoicesRow extends Model
{
    use CrudTrait;

    protected $table = 'xls_choices_rows';
    protected $guarded = ['id'];


    public function choiceList()
    {
        return $this->belongsTo(ChoiceList::class, 'list_name');
    }

    public function choicesLabels()
    {
        return $this->hasMany(ChoicesLabel::class, 'xls_choices_row_id');
    }

    public function moduleVersion()
    {
        return $this->belongsTo(ModuleVersion::class);
    }
}
