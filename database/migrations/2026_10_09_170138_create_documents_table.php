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
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tracking_number')->unique(); // e.g., DIV-202610-0042
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('document_type'); // Memorandum, Purchase Order, Claim
            $table->enum('urgency', ['Normal', 'Urgent', 'Immediate'])->default('Normal');
            $table->enum('status', [
                'Draft',
                'In Transit',
                'Received',
                'In Review',
                'Action Taken',
                'Approved',
                'Archived'
            ])->default('Draft');

            // Originator and current custodian
            $table->foreignUuid('originating_division_id')->constrained('divisions');
            $table->foreignUuid('created_by')->constrained('users');
            $table->foreignUuid('current_division_id')->nullable()->constrained('divisions');
            $table->foreignUuid('current_custodian_id')->nullable()->constrained('users');

            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
