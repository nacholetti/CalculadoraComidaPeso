<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('comida_ingrediente')) {
            Schema::create('comida_ingrediente', function (Blueprint $t) {
                $t->id();
                $t->foreignId('comida_id')->constrained('comidas')->cascadeOnDelete();
                $t->foreignId('ingrediente_id')->constrained('ingredientes')->cascadeOnDelete();
                $t->decimal('cantidad', 10, 3);
                $t->timestamps();
                $t->unique(['comida_id','ingrediente_id']);
            });
        } else {
            Schema::table('comida_ingrediente', function (Blueprint $t) {
                if (!Schema::hasColumn('comida_ingrediente', 'comida_id')) {
                    $t->foreignId('comida_id')->after('id')->constrained('comidas')->cascadeOnDelete();
                }
                if (!Schema::hasColumn('comida_ingrediente', 'ingrediente_id')) {
                    $t->foreignId('ingrediente_id')->after('comida_id')->constrained('ingredientes')->cascadeOnDelete();
                }
                if (!Schema::hasColumn('comida_ingrediente', 'cantidad')) {
                    $t->decimal('cantidad', 10, 3)->after('ingrediente_id');
                }
                if (!Schema::hasColumn('comida_ingrediente', 'created_at')) {
                    $t->timestamps();
                }
            });

            // Si no usás Doctrine, podés omitir esta verificación y confiar en que no existe:
            // Agregar índice único si faltara
            try {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableIndexes('comida_ingrediente');
                if (!array_key_exists('comida_ingrediente_comida_id_ingrediente_id_unique', $indexes)) {
                    Schema::table('comida_ingrediente', function (Blueprint $t) {
                        $t->unique(['comida_id','ingrediente_id'], 'comida_ingrediente_comida_id_ingrediente_id_unique');
                    });
                }
            } catch (\Throwable $e) {
                // Ignorar si no está Doctrine; es opcional
            }
        }
    }

    public function down(): void
    {
        // Schema::dropIfExists('comida_ingrediente');
    }
};
