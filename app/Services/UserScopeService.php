<?php

namespace App\Services;

use App\Models\Directorate;
use App\Models\Governorate;
use App\Models\InternalEntity;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserScopeService
{
    /**
     * Apply Administrative and Geographical Scopes to any Eloquent query.
     *
     * @param Builder $query
     * @param User|null $user
     * @param array $customColumns Custom column names e.g. ['entity' => 'internal_entity_id', 'gov' => 'governorate_id', 'dir' => 'directorate_id']
     * @return Builder
     */
    public static function applyScope(Builder $query, ?User $user = null, array $customColumns = []): Builder
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return $query;
        }

        // Super Admin check: if user has 'Super Admin' or 'admin' role or no restrictions
        if ($user->hasRole('Super Admin') || $user->hasRole('المدير العام')) {
            return $query;
        }

        $entityCol = $customColumns['entity'] ?? 'internal_entity_id';
        $govCol    = $customColumns['gov'] ?? 'governorate_id';
        $dirCol    = $customColumns['dir'] ?? 'directorate_id';

        $model = $query->getModel();

        // 1. Administrative Scope Filtering
        if ($user->internal_entity_id) {
            if ($model->getConnection()->getSchemaBuilder()->hasColumn($model->getTable(), $entityCol)) {
                $query->where($entityCol, $user->internal_entity_id);
            } elseif (method_exists($model, 'personalData')) {
                $query->whereHas('personalData', function ($q) use ($user, $entityCol) {
                    $q->where($entityCol, $user->internal_entity_id);
                });
            }
        }

        // 2. Geographical Scope Filtering
        $scopes = $user->geographicScopes;

        if ($scopes && $scopes->count() > 0) {
            $hasAll = $scopes->contains(fn($s) => $s->scope_type === 'all');

            if (!$hasAll) {
                $query->where(function ($q) use ($scopes, $model, $govCol, $dirCol) {
                    $hasDirectGovCol = $model->getConnection()->getSchemaBuilder()->hasColumn($model->getTable(), $govCol);
                    $hasPersonalDataRelation = method_exists($model, 'personalData');

                    foreach ($scopes as $scope) {
                        if ($scope->scope_type === 'none') {
                            if ($hasDirectGovCol) {
                                $q->orWhereNull($govCol);
                            } elseif ($hasPersonalDataRelation) {
                                $q->orWhereHas('personalData', function ($p) use ($govCol) {
                                    $p->whereNull($govCol);
                                });
                            }
                        } elseif ($scope->scope_type === 'specific') {
                            if ($hasDirectGovCol) {
                                $q->orWhere(function ($sub) use ($scope, $govCol, $dirCol) {
                                    if ($scope->governorate_id) {
                                        $sub->where($govCol, $scope->governorate_id);
                                    }
                                    if ($scope->directorate_id) {
                                        $sub->where($dirCol, $scope->directorate_id);
                                    }
                                });
                            } elseif ($hasPersonalDataRelation) {
                                $q->orWhereHas('personalData', function ($p) use ($scope, $govCol, $dirCol) {
                                    if ($scope->governorate_id) {
                                        $p->where($govCol, $scope->governorate_id);
                                    }
                                    if ($scope->directorate_id) {
                                        $p->where($dirCol, $scope->directorate_id);
                                    }
                                });
                            }
                        }
                    }
                });
            }
        }

        return $query;
    }

    /**
     * Scope Governorates dropdown query according to user's geographical scope.
     */
    public static function getScopedGovernorates(?User $user = null)
    {
        $user = $user ?? auth()->user();
        $query = Governorate::orderBy('name');

        if (!$user || $user->hasRole('Super Admin') || $user->hasRole('المدير العام')) {
            return $query->get();
        }

        $scopes = $user->geographicScopes;
        if (!$scopes || $scopes->count() === 0 || $scopes->contains(fn($s) => $s->scope_type === 'all')) {
            return $query->get();
        }

        $allowedGovIds = $scopes->pluck('governorate_id')->filter()->unique()->toArray();
        if (!empty($allowedGovIds)) {
            $query->whereIn('id', $allowedGovIds);
        }

        return $query->get();
    }

    /**
     * Scope Directorates dropdown query according to user's geographical scope.
     */
    public static function getScopedDirectorates(?User $user = null, $governorateId = null)
    {
        $user = $user ?? auth()->user();
        $query = Directorate::orderBy('name');

        if ($governorateId) {
            $query->where('governorate_id', $governorateId);
        }

        if (!$user || $user->hasRole('Super Admin') || $user->hasRole('المدير العام')) {
            return $query->get();
        }

        $scopes = $user->geographicScopes;
        if (!$scopes || $scopes->count() === 0 || $scopes->contains(fn($s) => $s->scope_type === 'all')) {
            return $query->get();
        }

        $allowedGovIds = $scopes->pluck('governorate_id')->filter()->unique()->toArray();
        $allowedDirIds = $scopes->pluck('directorate_id')->filter()->unique()->toArray();

        $query->where(function($q) use ($allowedGovIds, $allowedDirIds) {
            if (!empty($allowedGovIds)) {
                $q->orWhereIn('governorate_id', $allowedGovIds);
            }
            if (!empty($allowedDirIds)) {
                $q->orWhereIn('id', $allowedDirIds);
            }
        });

        return $query->get();
    }

    /**
     * Scope Internal Entities dropdown query according to user's administrative scope.
     */
    public static function getScopedEntities(?User $user = null)
    {
        $user = $user ?? auth()->user();
        $query = InternalEntity::orderBy('name');

        if (!$user || $user->hasRole('Super Admin') || $user->hasRole('المدير العام')) {
            return $query->get();
        }

        if ($user->internal_entity_id) {
            $allowedEntityIds = InternalEntity::getAllChildrenIds($user->internal_entity_id);
            if (!empty($allowedEntityIds)) {
                $query->whereIn('id', $allowedEntityIds);
            } else {
                $query->where('id', $user->internal_entity_id);
            }
        }

        return $query->get();
    }
}
