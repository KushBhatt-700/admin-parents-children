<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('children', function (Blueprint $table) {
            if (Schema::hasColumn('children', 'country')) {
                $table->dropColumn('country');
            }
            if (Schema::hasColumn('children', 'state')) {
                $table->dropColumn('state');
            }
            if (Schema::hasColumn('children', 'city')) {
                $table->dropColumn('city');
            }
        });
    }

    public function down(): void {
        Schema::table('children', function (Blueprint $table) {
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
        });
    }
};