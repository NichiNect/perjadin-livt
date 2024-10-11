<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Role;
use Illuminate\Http\JsonResponse;

class PicklistController extends Controller
{
    public function optionRole(): JsonResponse
    {
        $roles = Role::select(['id as value', 'name as label'])
            ->get();

        return response()->json([
            'message' => 'Success',
            'data' => $roles
        ]);
    }

    public function optionProvince(): JsonResponse
    {
        $provinces = Province::select(['id as value', 'name as label'])
            ->get();

        return response()->json([
            'message' => 'Success',
            'data' => $provinces
        ]);
    }
}
