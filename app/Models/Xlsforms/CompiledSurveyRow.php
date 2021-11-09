<?php

namespace App\Models\Xlsforms;

use App\Models\Xlsform;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompiledSurveyRow extends Model
{

    protected $table = 'compiled_survey_rows';
    protected $guarded = ['id'];

    public function xlsform()
    {
        return $this->belongsTo(Xlsform::class);
    }
}
