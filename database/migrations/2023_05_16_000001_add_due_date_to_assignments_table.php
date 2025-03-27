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
        // Check if the column doesn't exist before adding it
        if (!Schema::hasColumn('assignments', 'due_date')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->date('due_date')->nullable()->after('file_path');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop the column if it exists
        if (Schema::hasColumn('assignments', 'due_date')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->dropColumn('due_date');
            });
        }
    }
};
