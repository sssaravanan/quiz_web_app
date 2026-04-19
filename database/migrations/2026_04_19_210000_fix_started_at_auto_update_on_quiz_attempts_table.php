<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Remove implicit auto-update behavior from started_at.
        DB::statement("
            ALTER TABLE `quiz_attempts`
            MODIFY `started_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ");
    }

    public function down(): void
    {
        // Restore previous behavior if rollback is needed.
        DB::statement("
            ALTER TABLE `quiz_attempts`
            MODIFY `started_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ");
    }
};
