<?php

use App\Models\City;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('duty_trips', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 128)->index();
            $table->foreignIdFor(User::class, 'applicant_user_id')->index();
            $table->foreignIdFor(User::class, 'resolver_user_id')->nullable()->index();
            $table->foreignIdFor(City::class, 'from_city_id')->index();
            $table->foreignIdFor(City::class, 'destination_city_id')->index();
            $table->string('description');
            $table->date('start_date');
            $table->date('final_date');
            $table->enum('status', ['proposed', 'approved', 'rejected']);
            $table->integer('distance')->default(0);
            $table->bigInteger('allowance_per_day')->default(0);
            $table->bigInteger('total_allowance')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duty_trips');
    }
};
