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
    Schema::create('return_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('borrowing_id')->constrained('borrowings')->onDelete('cascade');
        $table->date('return_date');
        $table->integer('late_days')->default(0);
        $table->integer('fine_amount')->default(0);
        $table->enum('fine_status', ['none', 'Belum Lunas', 'Lunas'])->default('none');
        $table->string('pustakawan_name'); 
        $table->timestamps();
    });

} 



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_logs');
    }
};
