<?php

namespace App\Models;

use App\Models\Xlsforms\ChoicesRow;
use App\Models\Xlsforms\SurveyRow;
use Carbon\Carbon;
use App\Imports\ModuleFileImport;
use App\Imports\ModuleFileUnpack;
use Illuminate\Validation\ValidationException;
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
//        'required',
    ];
    protected $casts = [
        'is_current' => 'boolean',
    ];

    protected static function booted()
    {

        //Run validation **before** initial save
        static::saving(function ($moduleVersion) {

            if(!$moduleVersion->file) {
                throw ValidationException::withMessages([
                    'file' => 'Please add an XLS file for the module',
                ]);
            }

            if (request() && request()->hasFile('file')) {
                Excel::import(new ModuleFileValidation($moduleVersion), request()->file('file'));
            }

        });

//        // run importer to add question count to module Version
//        static::created(function($moduleVersion) {
//                Excel::import(new ModuleFileImport($moduleVersion), $moduleVersion->file);
//        });
//
//        static::updated(function($moduleVersion) {
//            // if file is dirty
//
//            if($moduleVersion->wasChanged('file')) {
//                Excel::import(new ModuleFileImport($moduleVersion), $moduleVersion->file);
//            }
//        });
    }

    public function publish()
    {

        // clean out old module_version from survey and choices table
        $this->surveyRows()->delete();
        $this->choicesRows()->delete();

        // import the new file + unpack into ODK survey + choices tables
        (new ModuleFileUnpack($this))->import($this->file);


        //remove 'is_current' flag from previous versions. If full - only change other full. if 'mini', change other minis
        $mini = $this->mini;

        $this->module->moduleVersions()->where('mini', $mini)->update(['is_current' => false]);


        $this->update(['published_at' => Carbon::now(), 'is_current' => true]);
        return $this->published_at;
    }

    public function unpublish()
    {
        $this->surveyRows()->delete();
        $this->choicesRows()->delete();

        $this->update(['published_at' => null, 'is_current' => false]);
        return true;
    }

    public function getFileNameAttribute()
    {
        return $this->file;
    }

    // Added for drag-and-drop selection (core modules are required)
    public function getRequiredAttribute()
    {
        return $this->module->core;
    }

    // ** probably temporary label var ** //
    public function getDropdownLabelAttribute()
    {
        $moduleTitle = $this->module ? $this->module->title : null;
        $themeTitle = $this->module ? ($this->module->theme ? $this->module->theme->title : null) : null;

        return '(' . $themeTitle . ') ' . $moduleTitle . ' - Version: ' . $this->version_name;
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
        return $this->belongsToMany(Xlsform::class)->withPivot(['order']);
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
