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
            $table->foreignUuid('document_type_id')->constrained('document_types')->restrictOnDelete();
            $table->string('priority')->default('Normal');
            $table->string('status')->default('Draft'); // e.g., Draft, In Transit, Received, In Review, Action Taken, Approved, Archived

            // Originator and current custodian
            $table->foreignUuid('originating_office_id')->constrained('offices')->restrictOnDelete();

            $table->foreignUuid('current_office_id')->nullable()->constrained('offices')->nullOnDelete();
            $table->foreignUuid('created_by')->constrained('users');
            $table->foreignUuid('current_custodian_id')->nullable()->constrained('users')->nullOnDelete();

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
