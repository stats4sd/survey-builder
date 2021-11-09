<?php

namespace App\Models;

use App\Models\Xlsforms\ChoicesRow;
use App\Models\Xlsforms\SurveyRow;
use Carbon\Carbon;
use App\Imports\ModuleFileImport;
use App\Imports\ModuleFileUnpack;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ModuleFileValidation;
use App\Models\Traits\HasUploadFields;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class ModuleVersion extends Model
{
    use CrudTrait, HasUploadFields;

    protected $table = 'module_versions';
    protected $guarded = ['id'];
    protected $appends = [
        // 'dropdown_label',
    ];
    protected $casts = [
        'is_current' => 'boolean',
    ];

    protected static function booted()
    {

        //Run validation **before** initial save
        static::saving(function($moduleVersion) {

            // Only do this if the module version is stand-alone. Core Versions get validated at the CoreVersion level.
            if(!$moduleVersion->core_version_id) {
                Excel::import(new ModuleFileValidation($moduleVersion), $moduleVersion->file);
            }

        });

        // run importer to add question count to module Version
        static::created(function($moduleVersion) {
            if(!$moduleVersion->core_version_id) {

                Excel::import(new ModuleFileImport($moduleVersion), $moduleVersion->file);
            }
        });

        static::updated(function($moduleVersion) {
            // if file is dirty

            if(!$moduleVersion->core_version_id && $moduleVersion->wasChanged('file')) {
                Excel::import(new ModuleFileImport($moduleVersion), $moduleVersion->file);
            }
        });
    }

    public function publish()
    {

        // clean out old module_version from survey and choices table
        $this->surveyRows()->delete();
        $this->choicesRows()->delete();

        // import the new
        Excel::import(new ModuleFileUnpack($this->module), $this->file);

        //remove 'is_current' flag from previous versions;
        $this->module->moduleVersions()->update(['is_current' => false]);


        $this->update(['published_at' => Carbon::now(), 'is_current' => true]);
        return $this->published_at;
    }

    public function getFileNameAttribute()
    {
        return $this->file;
    }

    // ** probably temporary label var ** //
    public function getDropdownLabelAttribute()
    {
        $moduleTitle = $this->module ? $this->module->title : null;
        $themeTitle = $this->module ? ($this->module->theme ? $this->module->theme->title : null) : null;

        return '('.$themeTitle.') ' . $moduleTitle . ' - Version: ' . $this->version_name;
    }



    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function modifiers()
    {
        return $this->belongsToMany(Modifier::class);
    }

    public function xlsforms()
    {
        return $this->belongsToMany(Xlsform::class);
    }

    public function coreVersion ()
    {
       return $this->belongsTo(CoreVersion::class);
    }

    public function surveyRows()
    {
        return $this->hasMany(SurveyRow::class);
    }
    public function choicesRows()
    {
        return $this->hasMany(ChoicesRow::class);
    }




    public function setFileAttribute($value)
    {
        $this->uploadFileWithNames($value, "file", config('filesystems.default'), "modules");
    }
}
