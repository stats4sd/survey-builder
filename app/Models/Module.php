<?php

namespace App\Models;

use App\Imports\ModuleFileImport;
use App\Models\Xlsforms\SurveyRow;
use App\Models\Xlsforms\ChoicesRow;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Traits\HasUploadFields;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Module extends Model
{
    use CrudTrait;

    protected $table = 'modules';
    protected $guarded = ['id'];

    protected $casts = [
        'requires_before' => 'array'
    ];

    public function getCurrentVersionNameAttribute()
    {
        return $this->currentVersions->pluck('version_name')->toArray();
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
        return $this->hasMany(ModuleVersion::class)->where('published_at', null)->orderBy('created_at', 'desc');
    }

    public function publishedVersions()
    {
        return $this->hasMany(ModuleVersion::class)->where('published_at', '!=', null)->orderBy('published_at', 'desc');
    }

    //published versions filtered to show only the ones that are 'current'
    public function currentVersions()
    {
        return $this->hasMany(ModuleVersion::class)->where('published_at', '!=', null)->where('is_current', 1)->orderBy('published_at', 'desc');
    }

    public function xlsforms()
    {
        return $this->belongsToMany(Xlsform::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function indicators()
    {
        return $this->belongsToMany(Indicator::class);
    }

    public function sdgs()
    {
        return $this->belongsToMany(Sdg::class);
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }
}
