<?php

namespace App\Models\Xlsforms;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoiceList extends Model
{
    use HasFactory, CrudTrait;

    protected $guarded = [];

    public function choicesRows()
    {
        return $this->hasMany(ChoicesRow::class);
    }
}
