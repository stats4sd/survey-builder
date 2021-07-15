<?php

namespace App\Models\Xlsforms;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ChoiceLabel extends Model
{

    protected $table = 'xls_choice_labels';
    protected $guarded = ['id'];


    public function choiceRow ()
    {
       return $this->belongsTo(ChoiceRow::class, 'xls_choice_row_id');
    }

}
