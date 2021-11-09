<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    protected $table = 'countries';

    // use ISO code for country ID
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = ['id'];

    public function xlsforms()
    {
        return $this->belongsToMany(Xlsform::class);
    }
}
