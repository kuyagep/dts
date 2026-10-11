<?php

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
        Schema::create('batch_transmittals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('batch_number')->unique(); // e.g., TR-20261011-89A2
            $table->foreignUuid('from_office_id')->constrained('offices')->cascadeOnDelete();
            $table->foreignUuid('to_office_id')->constrained('offices')->cascadeOnDelete();
            $table->foreignUuid('dispatched_by')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('In Transit'); // In Transit, Received, Partially Received
            $table->timestamp('received_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // Add batch_transmittal_id to document_routes table
        Schema::table('document_routes', function (Blueprint $table) {
            $table->foreignUuid('batch_transmittal_id')->nullable()->after('document_id')->constrained('batch_transmittals')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_transmittals');
    }
};
