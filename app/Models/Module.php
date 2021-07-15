<?php

namespace App\Models;

use App\Imports\ModuleFileImport;
use App\Models\Traits\HasUploadFields;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

class Module extends Model
{
    use CrudTrait, HasUploadFields;

    protected $table = 'modules';
    protected $guarded = ['id'];

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function setFileAttribute($value)
    {
        $this->uploadFileWithNames($value, "file", "local", "modules");
    }
}
