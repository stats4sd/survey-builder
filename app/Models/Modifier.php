<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Modifier extends Model
{
    use CrudTrait;


    protected $table = 'modifiers';
    protected $guarded = ['id'];

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }


    /**
     * Which modules have this modifier as an 'available' modifier?
     *
     * @return void
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class);
    }


    /**
     * Which module versions have this modifier assigned?
     *
     * @return void
     */
    public function moduleVersions()
    {
        return $this->belongsToMany(ModuleVersion::class);
    }
}
