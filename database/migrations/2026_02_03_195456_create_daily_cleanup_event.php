<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Drop old combined event if it exists
        DB::unprepared("DROP EVENT IF EXISTS ev_daily_cleanup");

        // Create a single event that triggers both procedures
        DB::unprepared("
            CREATE EVENT ev_daily_cleanup
            ON SCHEDULE EVERY 1 DAY
            STARTS CURRENT_TIMESTAMP
            DO
            BEGIN
                CALL sp_delete_old_done_tasks();
                CALL sp_delete_expired_short_links();
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP EVENT IF EXISTS ev_daily_cleanup");
    }
};
