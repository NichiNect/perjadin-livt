<?php

namespace App\Models;

use App\Enums\DutyTripStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class DutyTrip extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ticket_number',
        'applicant_user_id',
        'resolver_user_id',
        'from_city_id',
        'destination_city_id',
        'description',
        'start_date',
        'final_date',
        'status'
    ];

     /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'status' => DutyTripStatus::class,
    ];

    /**
     * Relation to `User` model as applicant.
     */
    public function applicantUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant_user_id', 'id');
    }

    /**
     * Relation to `User` model as resolver (approver/rejector).
     */
    public function resolverUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolver_user_id', 'id');
    }

    /**
     * Relation to `City` model as from city.
     */
    public function fromCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'from_city_id', 'id');
    }

    /**
     * Relation to `City` model as from city.
     */
    public function destinationCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'destination_city_id', 'id');
    }

    /**
     * * Generate Ticket number
     */
    public function generateTicketNumber() {

        $zeroPadding = "00000000";
        $prefixCode = 'DTP-';
        $code = "$prefixCode";

        $increment = 0;
        $similiarCode = DB::table('duty_trips')->select('ticket_number')
            ->where('ticket_number', 'LIKE', "$prefixCode%")
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$similiarCode) {
            $increment = 1;
        } else {
            $increment = (int) substr($similiarCode->ticket_number, strlen(($code)));
            $increment = $increment + 1;
        }

        $code = $code . substr($zeroPadding, strlen("$increment")) . $increment;

        return $code;
    }
}
