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
        Schema::create(
            'transactions',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('event_id')->constrained()->onDelete('cascade');
                $table->integer('quantity');
                $table->decimal('amount', 10, 2);
                $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            }
        );

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('snap_token');
        });
    }
};
