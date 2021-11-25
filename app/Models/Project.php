<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use CrudTrait;


    protected $table = 'projects';
    protected $guarded = [];

    protected $primaryKey = 'name';
    public $incrementing = false;
    protected $keyType = 'string';


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function xlsforms()
    {
        return $this->hasMany(Xlsform::class, 'project_name');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
