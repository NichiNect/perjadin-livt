<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'can' => [
                    'user_management.index' => $request->user()->role_id != 3 ? true : false,
                    'user_management.create' => $request->user()->role_id != 3 ? true : false,
                    'user_management.edit' => $request->user()->role_id != 3 ? true : false,
                    'user_management.delete' => $request->user()->role_id != 3 ? true : false,

                    'master_city.index' => true,
                    'master_city.create' => $request->user()->role_id != 3 ? true : false,
                    'master_city.edit' => $request->user()->role_id != 3 ? true : false,
                    'master_city.delete' => $request->user()->role_id != 3 ? true : false,

                    'duty_trip.index' => true,
                    'duty_trip.create' => true,
                    'duty_trip.reslove' => $request->user()->role_id != 3 ? true : false,
                ]
            ],
            'flash' => [
                'message' => $request->session()->get('message'),
                'class' => $request->session()->get('class')
            ]
        ];
    }
}
