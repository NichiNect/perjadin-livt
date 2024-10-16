<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('master_city.index');

        $search = $request->get('search', '');

        $city = City::with('province')
            ->when($search != '', function ($q) use ($search) {
                return $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('island', 'LIKE', '%' . $search . '%');
            })
            ->paginate(10);

        return Inertia::render('City', [
            'city' => $city,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('master_city.create');

        $request->validate([
            'province_id' => 'required|numeric|exists:provinces,id',
            'name' => 'required|string|max:255',
            'island' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'is_foreign_country' => 'required|boolean',
        ]);

        City::create([
            'province_id' => $request->province_id,
            'name' => $request->name,
            'island' => $request->island,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_foreign_country' => $request->is_foreign_country,
        ]);

        return redirect()->route('cities.index')->with([
            'message' => 'City created successfuly.',
            'class' => 'bg-success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('master_city.edit');

        $city = City::findOrFail($id);

        $request->validate([
            'province_id' => 'required|numeric|exists:provinces,id',
            'name' => 'required|string|max:255',
            'island' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'is_foreign_country' => 'required|boolean',
        ]);

        $city->province_id = $request->province_id;
        $city->name = $request->name;
        $city->island = $request->island;
        $city->latitude = $request->latitude;
        $city->longitude = $request->longitude;
        $city->is_foreign_country = $request->is_foreign_country;
        $city->save();

        return redirect()->route('cities.index')->with([
            'message' => 'City updated successfuly.',
            'class' => 'bg-success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $this->authorize('master_city.delete');

        $city = City::findOrFail($id)
            ->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Success',
                'data' => $city
            ]);
        }

        return redirect()->route('cities.index')->with([
            'message' => 'City delete successfuly.',
            'class' => 'bg-success'
        ]);
    }
}
