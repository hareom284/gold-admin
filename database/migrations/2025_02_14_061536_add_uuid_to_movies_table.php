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
        Schema::table('entertainments', function (Blueprint $table) {
            $table->uuid('uuid')->after('id');
        });
        
        Schema::table('cast_crew', function (Blueprint $table) {
            $table->uuid('uuid')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entertainments', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
        
        Schema::table('cast_crew', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
