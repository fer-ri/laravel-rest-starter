<?php

namespace App\Traits;

trait MetableTrait
{
    public function metas()
    {
        return $this->morphMany(\App\Models\Meta::class, 'metable');
    }

    public function checkExists($key)
    {
        if (! $this->relationLoaded('metas')) {
            $this->load('metas');
        }
        
        return $this->getRelation('metas')
            ->where('key', $key)
            ->first();
    }

    /**
     * Gets all meta data
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllMeta()
    {
        return collect($this->metas->lists('value', 'key'));
    }

    /**
     * Gets meta data
     *
     * @param $key
     * @param null $default
     * @param bool $getObj
     * @return Collection
     */
    public function getMeta($key, $default = null)
    {
        $meta = $this->checkExists($key);

        return $meta ? $meta->value : $default;
    }
    
    /**
     * Set meta data
     *
     * @return mixed
     */
    public function setMeta($key, $value)
    {
        $meta = $this->checkExists($key);

        if ($meta) {
            $meta->update(compact('value'));

            return $meta;
        }

        $meta = $this->metas()->create([
            'key' => $key,
            'value' => $value,
        ]);

        return $meta;
    }

    /**
     * Deletes meta data
     *
     * @param $key
     * @param bool $value
     * @return mixed
     */
    public function deleteMeta($key)
    {
        return $this->metas()->where('key', $key)->delete();
    }
    
    /**
     * Deletes all meta data
     *
     * @return mixed
     */
    public function deleteAllMeta()
    {
        return $this->metas()->delete();
    }
}
