<?php

namespace App\Models;

use Carbon\Carbon;
use App\Imports\CoreFileImport;
use App\Imports\CoreFileUnpack;
use App\Imports\CoreFileValidation;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Traits\HasUploadFields;
use App\Models\Xlsforms\ChoicesRow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class CoreVersion extends Model
{
    use CrudTrait, HasUploadFields;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'core_versions';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected static function booted()
    {

        // Run validation **before** initial save
        static::saving(function($coreVersion) {
            Excel::import(new CoreFileValidation($coreVersion), $coreVersion->file);
        });

        // run importer to unpack file and setup Module Version links
        static::created(function($coreVersion) {
            Excel::import(new CoreFileImport($coreVersion), $coreVersion->file);
        });

        static::updated(function($coreVersion) {
            // if file is dirty
            if($coreVersion->wasChanged('file')) {
                Excel::import(new CoreFileImport($coreVersion), $coreVersion->file);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function setFileAttribute($value)
    {
        $this->uploadFileWithNames($value, "file", config('filesystems.default'), "modules");
    }

    public function publish ()
    {
        // delete survey rows for each core module:
        $this->moduleVersions->each(function($moduleVersion) {
            $moduleVersion->surveyRows()->delete();
            $moduleVersion->module->moduleVersions()->update(['is_current' => false]);
        });

        // choices rows without a module are linked to core
        ChoicesRow::where('module_version_id', null)->delete();

        // import the new survey and choices rows with the Unpack imports:
        Excel::import(new CoreFileUnpack($this), $this->file);

        $publishedAt = Carbon::now();
        $this->update(['published_at' => $publishedAt]);

        $this->moduleVersions()->update(['published_at' => $publishedAt, 'is_current' => true]);

        return $this->published_at;
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function moduleVersions ()
    {
       return $this->hasMany(ModuleVersion::class);
    }

}
