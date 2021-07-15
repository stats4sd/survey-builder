<?php

namespace App\Models\Xlsforms;

use App\Models\Module;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class ChoiceRow extends Model
{
    protected $table = 'xls_choice_rows';
    protected $guarded = ['id'];


    public function choiceLabels()
    {
        return $this->hasMany(ChoiceLabel::class, 'xls_choice_row_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
