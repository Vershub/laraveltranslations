<?php

namespace Vershub\LaravelTranslations\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class TranslatableModel extends Model
{
    abstract protected function getTranslationModel(): string;

    abstract protected function getForeignKeyForTranslation(): string;

    protected function getLocaleCodeColumn(): string
    {
        return 'locale_code';
    }

    public function translation(): BelongsTo
    {
        return $this->belongsTo($this->getTranslationModel());
    }

    public function scopeWithTranslation($query, string $localeCode = null): void
    {
        $localeCode = $localeCode ?: app()->getLocale();

        $query->addSelect([
            'translation_id' => $this->getTranslationModel()::select('id')
                ->whereColumn($this->getForeignKeyForTranslation(), "{$this->getTable()}.id")
                ->where($this->getLocaleCodeColumn(), $localeCode)
                ->take(1)
        ])->with('translation');
    }
}