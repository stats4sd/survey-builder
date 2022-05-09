<?php

namespace App\Models\Xlsforms;

use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class ChoicesLabel extends Model
{
    protected $table = 'xls_choices_labels';
    protected $guarded = ['id'];
    protected $appends = ['display_label'];

    public function getDisplayLabelAttribute()
    {
        return $this->language_id . ": " . $this->label;
    }


    public function ChoicesRow()
    {
        return $this->belongsTo(ChoicesRow::class, 'xls_choices_row_id');
    }

    public function language ()
    {
       return $this->belongsTo(Language::class);
    }

}
