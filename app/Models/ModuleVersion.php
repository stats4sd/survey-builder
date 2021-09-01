<?php

namespace App\Models;

use Carbon\Carbon;
use App\Imports\ModuleFileImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Traits\HasUploadFields;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class ModuleVersion extends Model
{
    use CrudTrait, HasUploadFields;

    protected $table = 'module_versions';
    protected $guarded = ['id'];
    protected $appends = [
        'dropdown_label',
    ];

    public function publish()
    {

        // clean out old module_version from survey and choices table
        $this->module->surveyRows()->delete();
        $this->module->choicesRows()->delete();

        // import the new
        Excel::import(new ModuleFileImport($this->module), $this->file);

        $this->update(['published_at' => Carbon::now()]);

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




    public function setFileAttribute($value)
    {
        $this->uploadFileWithNames($value, "file", config('filesystems.default'), "modules");
    }
}
