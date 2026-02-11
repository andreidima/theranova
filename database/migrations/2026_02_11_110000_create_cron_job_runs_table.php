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
        Schema::create('cron_job_runs', function (Blueprint $table) {
            $table->id();
            $table->string('job_key', 200);
            $table->string('display_name', 255)->nullable();
            $table->string('source', 100)->default('scheduler');
            $table->string('status', 50)->default('running');
            $table->dateTime('started_at');
            $table->dateTime('finished_at')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->unsignedBigInteger('triggered_by_user_id')->nullable();
            $table->text('output')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('job_key');
            $table->index('status');
            $table->index('started_at');
            $table->foreign('triggered_by_user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cron_job_runs');
    }
};
