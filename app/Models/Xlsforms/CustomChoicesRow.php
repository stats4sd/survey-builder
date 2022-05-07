<?php

namespace App\Models\Xlsforms;

use App\Models\Xlsform;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomChoicesRow extends Model
{

    protected $guarded = [];
    protected $table = "xlsform_custom_choice_rows";

    public function customChoiceLabels(): hasMany
    {
        return $this->hasMany(CustomChoicesLabel::class);
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
