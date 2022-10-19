<?php

namespace App\Models;

use App\Imports\ImportLocationsFileToChoices;
use App\Models\Traits\HasUploadFields;
use App\Models\Xlsforms\ChoiceList;
use App\Models\Xlsforms\CompiledChoicesRow;
use App\Models\Xlsforms\CompiledSurveyRow;
use App\Models\Xlsforms\SelectedChoicesRow;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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
        'location_file_url',
        'location_file_name',
    ];

    protected $casts = [
        'region_label' => 'array',
        'subregion_label' => 'array',
        'village_label' => 'array',
    ];

    public function getDownloadUrlAttribute()
    {
        if ($this->xlsfile) {
            return url('download/' . $this->xlsfile);
        }
        return null;
    }

    public function getRhomisAppUrl()
    {
        if ($this->xlsfile) {
            return config('auth.rhomis_url');
        }
    }

    public function setXlsfileAttribute($value)
    {
        // if a file is not included, the file is being built on the server.
        if (request()->hasFile('xlsfile')) {
            $this->uploadFileWithNames($value, 'xlsfile', 'local', 'forms');
        } else {
            $this->attributes['xlsfile'] = $value;
        }
    }

    public function getLocationFileUrlAttribute()
    {
        return $this->location_file ? Storage::url($this->location_file) : '';
    }

    public function getLocationFileNameAttribute()
    {
        return $this->location_file ?? '';
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

    public function selectedChoicesRows()
    {
        return $this->hasMany(SelectedChoicesRow::class);
    }

    public function choiceLists()
    {
        return $this->belongsToMany(ChoiceList::class, 'xlsform_choice_list')
            ->withPivot('complete');
    }

    public function setLocationFileAttribute($value)
    {

        $attribute_name = "location_file";
        $disk = "public";
        $destination_path = $this->name;

        $this->uploadFileWithNames($value, $attribute_name, $disk, $destination_path);
    }
}
