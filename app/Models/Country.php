<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use CrudTrait;

    protected $table = 'countries';

    // use ISO code for country ID
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function xlsforms()
    {
        return $this->belongsToMany(Xlsform::class, 'country_xlsform');
    }
}
