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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('placa', 7)->unique();
            $table->string('chassi', 17)->unique();
            $table->string('marca');
            $table->string('modelo');
            $table->string('versao');
            $table->decimal('valor_venda', 15, 2);
            $table->string('cor');
            $table->unsignedInteger('km')->default(0);
            $table->enum('cambio', ['manual', 'automatico']);
            $table->enum('combustivel', ['gasolina', 'alcool', 'flex', 'diesel', 'hibrido', 'eletrico']);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['marca', 'modelo']);
            $table->index('valor_venda');
            $table->index('km');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
