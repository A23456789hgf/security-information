<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * Additional fillable fields beyond Spatie's defaults.
     */
    protected $fillable = ['name', 'guard_name', 'scopes'];

    /**
     * Cast 'scopes' as an array so it is automatically
     * serialised/deserialised from the JSON column.
     */
    protected $casts = [
        'scopes' => 'array',
    ];
}
