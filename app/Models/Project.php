<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use CrudTrait;


    protected $table = 'projects';
    protected $guarded = ['id'];


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function xlsforms()
    {
        return $this->hasMany(Xlsform::class);
    }
}
