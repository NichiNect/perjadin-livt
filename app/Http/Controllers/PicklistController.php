<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Province;
use App\Models\Role;
use App\Models\User;
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

    public function optionCity(): JsonResponse {
        $city = City::select(['id as value', 'name as label'])
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'message' => 'Success',
            'data' => $city
        ]);
    }

    public function optionUser(): JsonResponse {
        $user = User::select(['id as value', 'name as label'])
            ->where('role_id', '!=', 1) // not `Admin`
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'message' => 'Success',
            'data' => $user
        ]);
    }
}
