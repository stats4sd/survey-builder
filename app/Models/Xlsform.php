<?php

namespace App\Models;

use App\Models\Traits\HasUploadFields;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Xlsform extends Model
{
    use CrudTrait, HasUploadFields;


    protected $table = 'xlsforms';
    protected $guarded = ['id'];

    public function setXlsfileAttribute($value)
    {
        $this->uploadFileWithNames($value, 'xlsfile', 'local', 'forms');
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function themes()
    {
        return $this->belongsToMany(Theme::class);
    }


    public function moduleVersions()
    {
        return $this->belongsToMany(ModuleVersion::class);
    }
}
