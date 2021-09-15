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
        // if a file is not included, the file is being built on the server.
        if(request()->hasFile('xlsfile')) {
            $this->uploadFileWithNames($value, 'xlsfile', 'local', 'forms');
        }
        else {
            $this->attributes['xlsfile'] = $value;
        }
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
