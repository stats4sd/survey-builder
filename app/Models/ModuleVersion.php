<?php

namespace App\Models;

use App\Models\Traits\HasUploadFields;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class ModuleVersion extends Model
{
    use CrudTrait, HasUploadFields;

    protected $table = 'module_versions';
    protected $guarded = ['id'];

    public function publish()
    {
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
        $this->uploadFileWithNames($value, "file", "local", "modules");
    }
}
