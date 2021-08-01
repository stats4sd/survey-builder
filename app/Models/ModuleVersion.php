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


    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function setFileAttribute($value)
    {
        $this->uploadFileWithNames($value, "file", config('filesystems.default'), "modules");
    }
}
