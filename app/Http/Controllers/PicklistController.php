<?php

namespace App\Http\Controllers;

use App\Models\Role;

class PicklistController extends Controller
{
    public function optionRole()
    {
        $roles = Role::select(['id as value', 'name as label'])
            ->get();

        return response()->json([
            'message' => 'success',
            'data' => $roles
        ]);
    }
}
