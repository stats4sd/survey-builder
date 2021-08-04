<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use CrudTrait;


    protected $table = 'themes';
    protected $guarded = ['id'];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function modifiers()
    {
        return $this->hasMany(Modifier::class);
    }

    public function xlsforms()
    {
        return $this->belongsToMany(Xlsform::class);
    }
}
