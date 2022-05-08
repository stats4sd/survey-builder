<?php

namespace App\Models\Xlsforms;

use App\Models\Module;
use App\Models\ModuleVersion;
use App\Models\Xlsforms\ChoicesLabel;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChoicesRow extends Model
{
    use CrudTrait;

    protected $table = 'xls_choices_rows';
    protected $guarded = ['id'];


    public function choiceList(): BelongsTo
    {
        return $this->belongsTo(ChoiceList::class, 'list_name', 'list_name');
    }

    public function choicesLabels(): HasMany
    {
        return $this->hasMany(ChoicesLabel::class, 'xls_choices_row_id');
    }

    public function moduleVersion(): BelongsTo
    {
        return $this->belongsTo(ModuleVersion::class);
    }

    public function customChoicesRow(): HasMany
    {
        return $this->hasMany(SelectedChoicesRow::class);
    }
}
