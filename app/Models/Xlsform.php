<?php

namespace App\Models;

use App\Models\Traits\HasUploadFields;
use App\Models\Xlsforms\CompiledChoicesRow;
use App\Models\Xlsforms\CompiledSurveyRow;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Xlsform extends Model
{
    use CrudTrait, HasUploadFields;


    protected $table = 'xlsforms';
    protected $guarded = [];
    public $incrementing = false;
    protected $primaryKey = 'name';
    protected $keyType = 'string';
    protected $appends = [
        'download_url',
    ];

    public function getDownloadUrlAttribute()
    {
        if($this->xlsfile) {
            return url('download/' . $this->xlsfile);
        }
        return null;
    }

    public function getRhomisAppUrl()
    {
        if($this->xlsfile) {
            return config('auth.rhomis_url');
        }
    }

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
        return $this->belongsTo(Project::class, 'project_name');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function themes()
    {
        return $this->belongsToMany(Theme::class);
    }

    public function moduleVersions()
    {
        return $this->belongsToMany(ModuleVersion::class)->withPivot(['order']);
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_xlsform');
    }

    public function compiledSurveyRows()
    {
        return $this->hasMany(CompiledSurveyRow::class);
    }

    public function compiledChoicesRows()
    {
        return $this->hasMany(CompiledChoicesRow::class);
    }
}
