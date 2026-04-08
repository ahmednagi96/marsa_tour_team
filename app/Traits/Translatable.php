<?php 

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait Translatable
{
 
    public function initializeTranslatable()
    {
        foreach ($this->getTranslatableAttributes() as $field) {
            $this->casts[$field] = 'array';
        }
    }

    protected function getTranslated($field)
    {
        $lang = app()->getLocale();
        $translations = $this->getAttributeValue($field);

        if (is_array($translations)) {
            return $translations[$lang] ?? ($translations['ar'] ?? ($translations['en'] ?? ''));
        }

        return $translations;
    }

    public function getAttribute($key)
    {
        if (in_array($key, $this->getTranslatableAttributes())) {
            return $this->getTranslated($key);
        }

        return parent::getAttribute($key);
    }

    protected function getTranslatableAttributes(): array
    {
        return $this->translatable ?? [];
    }
}