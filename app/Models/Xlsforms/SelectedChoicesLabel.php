<?php

namespace App\Models\Xlsforms;

use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelectedChoicesLabel extends Model
{

    protected $guarded = [];
    protected $table = 'xlsform_selected_choice_labels';

    public function selectedChoicesRows(): belongsTo
    {
        return $this->belongsTo(SelectedChoicesRow::class, 'xlsform_selected_choice_row_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }




}
