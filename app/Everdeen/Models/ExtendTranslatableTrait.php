<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-18
 * Time: 20:46
 */

namespace Katniss\Everdeen\Models;


trait ExtendTranslatableTrait
{
    public function scopeOrWhereTranslation($query, $key, $value, $locale = null)
    {
        return $query->orWhereHas('translations', function ($query) use ($key, $value, $locale) {
            $query->where($this->getTranslationsTable() . '.' . $key, $value);
            if ($locale) {
                $query->where($this->getTranslationsTable() . '.' . $this->getLocaleKey(), $locale);
            }
        });
    }

    public function scopeOrManyWhereTranslation($query, $values, $locale = null)
    {
        return $query->orWhereHas('translations', function ($query) use ($values, $locale) {
            if ($locale) {
                $query->where($this->getTranslationsTable() . '.' . $this->getLocaleKey(), $locale);
                $query->where(function ($query) use ($values) {
                    $first = true;
                    foreach ($values as $key => $value) {
                        if (is_array($value)) {
                            foreach ($value as $v) {
                                if ($first) {
                                    $query->where($this->getTranslationsTable() . '.' . $key, $v);
                                } else {
                                    $query->orWhere($this->getTranslationsTable() . '.' . $key, $v);
                                }
                                $first = false;
                            }
                        } else {
                            if ($first) {
                                $query->where($this->getTranslationsTable() . '.' . $key, $value);
                            } else {
                                $query->orWhere($this->getTranslationsTable() . '.' . $key, $value);
                            }
                        }
                        $first = false;
                    }
                });
                return;
            }

            $first = true;
            foreach ($values as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $v) {
                        if ($first) {
                            $query->where($this->getTranslationsTable() . '.' . $key, $v);
                        } else {
                            $query->orWhere($this->getTranslationsTable() . '.' . $key, $v);
                        }
                        $first = false;
                    }
                } else {
                    if ($first) {
                        $query->where($this->getTranslationsTable() . '.' . $key, $value);
                    } else {
                        $query->orWhere($this->getTranslationsTable() . '.' . $key, $value);
                    }
                }
                $first = false;
            }
        });
    }

    public function scopeOrWhereTranslationLike($query, $key, $value, $locale = null)
    {
        return $query->orWhereHas('translations', function ($query) use ($key, $value, $locale) {
            $query->where($this->getTranslationsTable() . '.' . $key, 'LIKE', $value);
            if ($locale) {
                $query->where($this->getTranslationsTable() . '.' . $this->getLocaleKey(), 'LIKE', $locale);
            }
        });
    }

    public function scopeOrManyWhereTranslationLike($query, $values, $locale = null)
    {
        return $query->orWhereHas('translations', function ($query) use ($values, $locale) {
            if ($locale) {
                $query->where($this->getTranslationsTable() . '.' . $this->getLocaleKey(), 'LIKE', $locale);
                $query->where(function ($query) use ($values) {
                    $first = true;
                    foreach ($values as $key => $value) {
                        if (is_array($value)) {
                            foreach ($value as $v) {
                                if ($first) {
                                    $query->where($this->getTranslationsTable() . '.' . $key, 'LIKE', $v);
                                } else {
                                    $query->orWhere($this->getTranslationsTable() . '.' . $key, 'LIKE', $v);
                                }
                                $first = false;
                            }
                        } else {
                            if ($first) {
                                $query->where($this->getTranslationsTable() . '.' . $key, 'LIKE', $value);
                            } else {
                                $query->orWhere($this->getTranslationsTable() . '.' . $key, 'LIKE', $value);
                            }
                        }
                        $first = false;
                    }
                });
                return;
            }

            $first = true;
            foreach ($values as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $v) {
                        if ($first) {
                            $query->where($this->getTranslationsTable() . '.' . $key, 'LIKE', $v);
                        } else {
                            $query->orWhere($this->getTranslationsTable() . '.' . $key, 'LIKE', $v);
                        }
                        $first = false;
                    }
                } else {
                    if ($first) {
                        $query->where($this->getTranslationsTable() . '.' . $key, 'LIKE', $value);
                    } else {
                        $query->orWhere($this->getTranslationsTable() . '.' . $key, 'LIKE', $value);
                    }
                }
                $first = false;
            }
        });
    }
}