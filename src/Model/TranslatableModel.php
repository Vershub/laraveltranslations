<?php

namespace Vershub\LaravelTranslations\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

abstract class TranslatableModel extends Model
{
    abstract protected function getTranslationModel(): string;

    abstract protected function getForeignKeyForTranslation(): string;

    public function translates(): HasMany
    {
        return $this->hasMany(
            related: $this->getTranslationModel(),
            foreignKey: $this->getForeignKeyForTranslation(),
            localKey: 'id'
        );
    }

    public function translation(): BelongsTo
    {
        return $this->belongsTo($this->getTranslationModel());
    }

    public function scopeWithTranslation($query, string $localeCode = null): void
    {
        $localeCodeAndColumns = explode(':', $localeCode);
        $localeCode = $localeCodeAndColumns[0];
        $columns = $localeCodeAndColumns[1] ?? '*';

        $localeCode = $localeCode ?: app()->getLocale();

        $query->addSelect([
            'translation_id' => $this->getTranslationModel()::select('id')
                ->whereColumn($this->getForeignKeyForTranslation(), "{$this->getTable()}.id")
                ->where(config('laraveltranslations.locale_code_column'), $localeCode)
                ->take(1)
        ])->with("translation:{$columns}");
    }
}