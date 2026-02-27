<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_delete_expired_short_links");

        DB::unprepared("
            CREATE PROCEDURE sp_delete_expired_short_links()
            BEGIN
                DELETE FROM short_links
                WHERE expires_at IS NOT NULL
                  AND expires_at < DATE_SUB(CURDATE(), INTERVAL 7 DAY);
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_delete_expired_short_links");
    }
};
