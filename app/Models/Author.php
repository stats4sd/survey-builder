<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use CrudTrait;


    protected $table = 'authors';
    protected $guarded = ['id'];

    public function modules()
    {
        return $this->belongsToMany(Author::class);
    }
}
