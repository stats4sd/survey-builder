<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Sdg extends Model
{
    use CrudTrait;


    protected $table = 'sdgs';
    protected $guarded = ['id'];
}
