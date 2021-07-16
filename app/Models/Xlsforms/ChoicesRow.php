<?php

namespace App\Models\Xlsforms;

use App\Models\Module;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class ChoicesRow extends Model
{
    protected $table = 'xls_choices_rows';
    protected $guarded = ['id'];


    public function ChoicesLabels()
    {
        return $this->hasMany(ChoicesLabel::class, 'xls_choice_row_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}