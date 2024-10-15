<?php

namespace App\Services;

use App\Models\City;
use Illuminate\Support\Facades\DB;

class DutyTripService
{
    // a. Jarak 0 – 60km : tidak mendapat uang saku
    // b. Diatas 60km tetapi dalam satu provinsi mendapatkan Rp 200.000 per-hari
    // c. Diatas 60km ke luar provinsi tapi masih dalam satu pulau mendapatkan Rp 250.000,-
    // d. Diatas 60km ke luar provinsi dan luar pulau mendapatkan Rp 300.000,-
    // e. Khusus perdin ke Luar Negeri mendapatkan USD 50 per-hari

    private $kursUsd = 15000;

    public function generateAllowance($fromCityId, $destinationCityId, $totalDay)
    {
        $fromCity = City::findOrFail($fromCityId);
        $destinationCity = City::findOrFail($destinationCityId);

        $distance = $this->getNearestLocationByLatLong($fromCity->latitude, $fromCity->longitude, $destinationCity->id);

        // Case A (default)
        $result = [
            'allowancePerDay' => 0,
            'totalAllowance' => 0,
            'distance' => $distance->distance
        ];

        if ($destinationCity->is_foreign_country) {
            // Case E
            $result['allowancePerDay'] = $this->kursUsd * 50;
            $result['totalAllowance'] = $result['allowancePerDay'] * $totalDay;
        } else if ($distance->distance >= 60) {

            if ($fromCity->province_id == $destinationCity->province_id) {
                // Case B
                $result['allowancePerDay'] = 200000;
                $result['totalAllowance'] = $result['allowancePerDay'] * $totalDay;
            } else if ($fromCity->province_id != $destinationCity->province_id) {

                if ($fromCity->island != $destinationCity->island) {
                    // Case D
                    $result['allowancePerDay'] = 300000;
                    $result['totalAllowance'] = $result['allowancePerDay'] * $totalDay;
                } else {
                    // Case C
                    $result['allowancePerDay'] = 250000;
                    $result['totalAllowance'] = $result['allowancePerDay'] * $totalDay;
                }
            }
        }

        return $result;
    }

    private function getNearestLocationByLatLong($lat, $long, $destinationId)
    {
        $result = DB::table('cities')
            ->select([
                'id', 'name',
                DB::raw("
                    (
                        (
                            (
                                acos(
                                    sin(($lat * pi() / 180))
                                    *
                                    sin((latitude * pi() / 180)) + cos(($lat * pi() / 180))
                                    *
                                    cos((latitude * pi() / 180)) * cos((($long - longitude) * pi()/180))
                                )
                            ) * 180/pi()
                        ) * 60 * 1.1515 * 1.609344
                    ) as distance
                    ")
                ])
            ->where('id', $destinationId)
            ->first();

        return $result;
    }
}