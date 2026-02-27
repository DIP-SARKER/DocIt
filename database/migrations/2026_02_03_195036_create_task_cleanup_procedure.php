<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Drop if exists (safe re-run)
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_delete_old_done_tasks");

        DB::unprepared("
            CREATE PROCEDURE sp_delete_old_done_tasks()
            BEGIN
                DELETE FROM tasks
                WHERE status = 'done'
                  AND updated_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_delete_old_done_tasks");
    }
};
