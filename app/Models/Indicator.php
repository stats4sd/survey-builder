<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    use CrudTrait;


    protected $table = 'indicators';
    protected $guarded = ['id'];
}
