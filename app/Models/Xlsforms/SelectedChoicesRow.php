<?php

namespace App\Models\Xlsforms;

use App\Models\Xlsform;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SelectedChoicesRow extends Model
{

    protected $guarded = [];
    protected $table = "xlsform_selected_choice_rows";

    public function selectedChoicesLabels(): hasMany
    {
        return $this->hasMany(SelectedChoicesLabel::class, 'xlsform_selected_choice_row_id');
    }

    public function xlsform(): BelongsTo
    {
        return $this->belongsTo(Xlsform::class);
    }

    public function choicesRow(): belongsTo
    {
        return $this->belongsTo(ChoicesRow::class);
    }

    public function choiceList(): BelongsTo
    {
        return $this->belongsTo(ChoiceList::class, 'list_name');
    }
}
