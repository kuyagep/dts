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
        Schema::create('document_routes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('document_id')->constrained('documents')->cascadeOnDelete();
            $table->integer('step_number');

            $table->foreignUuid('from_office_id')->constrained('offices');
            $table->foreignUuid('to_office_id')->constrained('offices');
            $table->foreignUuid('assigned_user_id')->nullable()->constrained('users');

            $table->enum('status', ['Pending', 'In Transit', 'Received', 'Completed', 'Bypassed'])->default('Pending');
            $table->text('remarks')->nullable();

            $table->timestamp('received_at')->nullable();
            $table->timestamp('action_taken_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_routes');
    }
};
