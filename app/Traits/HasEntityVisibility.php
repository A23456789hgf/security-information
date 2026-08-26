<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait HasEntityVisibility
{
    /**
     * Scope a query to only include records visible to the user for a module.
     *
     * @param  string  $module
     * @return Builder
     */
    public function scopeVisibleToUserForModule(Builder $query, $module, ?User $user = null)
    {
        $user = $user ?: auth()->user();

        if (! $user) {
            return $query->whereRaw('1=0');
        }

        // Logic based on requirements: None, All, Same_governorate, Same_directorate, Custom
        // This usually depends on the permission name, e.g., 'view_all_dynamic_pages', 'view_governorate_dynamic_pages'

        // For simplicity and following the requirement "Filtering depends on: Created_by_entity"

        $internalEntity = $user->internal_entity;

        if (! $internalEntity) {
            // If user has no entity, they might only see what they created
            return $query->where('created_by', $user->id);
        }

        // Check permissions (Spatie)
        if ($user->hasPermissionTo("view_all_{$module}")) {
            return $query;
        }

        if ($user->hasPermissionTo("view_governorate_{$module}")) {
            return $query->whereHas('entity', function ($q) use ($internalEntity) {
                $q->where('governorate_id', $internalEntity->governorate_id);
            });
        }

        if ($user->hasPermissionTo("view_directorate_{$module}")) {
            return $query->whereHas('entity', function ($q) use ($internalEntity) {
                $q->where('directorate_id', $internalEntity->directorate_id);
            });
        }

        // Default: Only own entity AND own records for safety
        return $query->where('created_by_entity_id', $user->internal_entity_id)
            ->orWhere('created_by', $user->id);
    }
}
