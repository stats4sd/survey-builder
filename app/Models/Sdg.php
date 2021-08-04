<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Sdg extends Model
{
    use CrudTrait;


    protected $table = 'sdgs';
    protected $guarded = [];
    public $incrementing = false;

    public function modules()
    {
        return $this->belongsToMany(Module::class);
    }
}
