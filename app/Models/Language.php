<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use CrudTrait;


    protected $table = 'languages';
    protected $guarded = [];
}
