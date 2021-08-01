<?php

namespace App\Models;

use App\Imports\ModuleFileImport;
use App\Models\Traits\HasUploadFields;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

class Module extends Model
{
    use CrudTrait;

    protected $table = 'modules';
    protected $guarded = ['id'];
    protected $appends = [
        'current_version_name',
        'current_version',
    ];

    // ****** Latest Published Version ********* //
    public function getCurrentVersionAttribute()
    {
        if ($this->publishedVersions->count() > 0) {
            return $this->publishedVersions()->orderBy('created_at', 'desc')->first();
        }

        return null;
    }

    public function getCurrentVersionNameAttribute()
    {
        if ($this->publishedVersions->count() > 0) {
            return $this->current_version->version_name;
        }

        return null;
    }

    public function getCurrentFileAttribute()
    {
        if ($this->publishedVersions->count() > 0) {
            return $this->current_version->file;
        }

        return null;
    }


    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function moduleVersions()
    {
        return $this->hasMany(ModuleVersion::class);
    }

    public function draftVersions()
    {
        return $this->hasMany(ModuleVersion::class)->where('published_at', null);
    }

    public function publishedVersions()
    {
        return $this->hasMany(ModuleVersion::class)->where('published_at', '!=', null);
    }
}
