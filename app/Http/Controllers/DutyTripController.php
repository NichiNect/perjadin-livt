<?php

namespace App\Http\Controllers;

use App\Models\DutyTrip;
use App\Services\DutyTripService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DutyTripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $credential = Auth::user();

        $dutyTrip = DutyTrip::with(['applicantUser', 'resolverUser', 'fromCity', 'destinationCity'])
            ->when($credential->role_id == 3, function ($q) use ($credential) { // role staff
                return $q->where('applicant_user_id', $credential->id);
            })
            ->when($search != '', function ($q) use ($search) {
                return $q->where('ticket_number', 'LIKE', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('DutyTrip/Index', [
            'dutyTripProposal' => $dutyTrip
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $credential = Auth::user();

        $request->validate([
            'from_city_id' => 'required|numeric|exists:cities,id',
            'destination_city_id' => 'required|numeric|exists:cities,id',
            'start_date' => 'required|string',
            'final_date' => 'required|string',
            'description' => 'required|string|min:3|max:255',
            'applicant_user_id' => $credential->role_id != 3 // not staff
                ? 'required|numeric|exists:users,id'
                : 'nullable'
        ]);

        $countDay = Carbon::parse($request->final_date)->diffInDays($request->start_date);
        $allowance = (new DutyTripService())->generateAllowance($request->from_city_id, $request->destination_city_id, $countDay);

        $model = new DutyTrip();
        $model->ticket_number = $model->generateTicketNumber();
        $model->from_city_id = $request->from_city_id;
        $model->destination_city_id = $request->destination_city_id;
        $model->start_date = $request->start_date;
        $model->final_date = $request->final_date;
        $model->description = $request->description;
        $model->applicant_user_id = $credential->role_id != 3 // not staff
            ? $request->applicant_user_id
            : $credential->id;
        $model->allowance_per_day = $allowance['allowancePerDay'];
        $model->total_allowance = $allowance['totalAllowance'];
        $model->distance = ceil($allowance['distance']);
        $model->save();

        return redirect()->route('duty-trip-proposals.index')->with([
            'message' => 'Duty Trip Proposal created successfuly.',
            'class' => 'bg-success'
        ]);
    }
}
