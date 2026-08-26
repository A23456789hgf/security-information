<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalEntity extends Model
{
    use HasFactory;

    protected $table = 'internal_entities';

    protected $fillable = ['name', 'governorate_id', 'directorate_id', 'parent_id'];

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function directorate()
    {
        return $this->belongsTo(Directorate::class);
    }

    public function parent()
    {
        return $this->belongsTo(InternalEntity::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(InternalEntity::class, 'parent_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected static array $resolvedChildrenCache = [];

    protected static function booted()
    {
        static::saved(function ($entity) {
            static::$resolvedChildrenCache = [];
            \Illuminate\Support\Facades\Cache::forget('internal_entity_children_' . (int)$entity->id);
            if ($entity->parent_id) {
                \Illuminate\Support\Facades\Cache::forget('internal_entity_children_' . (int)$entity->parent_id);
            }
        });

        static::deleted(function ($entity) {
            static::$resolvedChildrenCache = [];
            \Illuminate\Support\Facades\Cache::forget('internal_entity_children_' . (int)$entity->id);
            if ($entity->parent_id) {
                \Illuminate\Support\Facades\Cache::forget('internal_entity_children_' . (int)$entity->parent_id);
            }
        });
    }

    /**
     * Get all children IDs recursively, including the parent ID itself.
     * 
     * @param int|null $parentId
     * @return array
     */
    public static function getAllChildrenIds($parentId)
    {
        if (!$parentId) {
            return [];
        }

        $parentId = (int) $parentId;

        if (isset(static::$resolvedChildrenCache[$parentId])) {
            return static::$resolvedChildrenCache[$parentId];
        }

        $cacheKey = 'internal_entity_children_' . $parentId;

        $result = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () use ($parentId) {
            $allIds = [$parentId];
            $toProcess = [$parentId];

            while (!empty($toProcess)) {
                $ids = static::whereIn('parent_id', $toProcess)->pluck('id')->toArray();
                $toProcess = array_diff($ids, $allIds);
                $allIds = array_merge($allIds, $toProcess);
                
                if (empty($toProcess)) break;
            }

            return array_values(array_unique($allIds));
        });

        static::$resolvedChildrenCache[$parentId] = $result;

        return $result;
    }
}
