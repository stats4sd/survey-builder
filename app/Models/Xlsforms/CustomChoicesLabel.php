<?php

namespace App\Models\Xlsforms;

use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomChoicesLabel extends Model
{

    protected $guarded = [];
    protected $table = 'xlsform_custom_choice_labels';

    public function customChoicesRows(): belongsTo
    {
        return $this->belongsTo(CustomChoicesRow::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }




}
