<?php

use App\Models\Package;
use App\Models\School;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('package_school', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Package::class)->constrained();
            $table->foreignIdFor(School::class)->constrained();
            $table->integer('cost')->unsigned();
            $table->integer('duration')->unsigned();
            $table->timestamp('starts_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_school');
    }
};
