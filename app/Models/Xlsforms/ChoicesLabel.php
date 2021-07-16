<?php

namespace App\Models\Xlsforms;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ChoicesLabel extends Model
{
    protected $table = 'xls_choices_labels';
    protected $guarded = ['id'];


    public function ChoicesRow()
    {
        return $this->belongsTo(ChoicesRow::class, 'xls_choice_row_id');
    }
}
