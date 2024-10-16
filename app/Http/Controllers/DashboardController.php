<?php

namespace App\Http\Controllers;

use App\Enums\DutyTripStatus;
use App\Models\City;
use App\Models\DutyTrip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::count('*');
        $city = City::count('*');

        $dutyTrip = DutyTrip::select([
                DB::raw('COUNT(*) as total'), 'status'
            ])
            ->groupBy('status')
            ->get();

        return Inertia::render('Dashboard', [
            'totalUser' => $user,
            'totalCity' => $city,
            'totalDutyTrip' => $dutyTrip->sum('total'),
            // 'totalDutyTripProposed' => $dutyTrip->filter(function ($item) {
            //     return $item->status == DutyTripStatus::Proposed;
            // })->pluck('total')[0],
            'totalDutyTripProposed' => $dutyTrip->where('status', DutyTripStatus::Proposed)->sum('total'),
            'totalDutyTripApproved' => $dutyTrip->where('status', DutyTripStatus::Approved)->sum('total'),
            'totalDutyTripRejected' => $dutyTrip->where('status', DutyTripStatus::Rejected)->sum('total'),
        ]);
    }
}
