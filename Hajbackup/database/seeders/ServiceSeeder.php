<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlFilePath = __DIR__ . "/services.sql"; // Deklaráljuk a változót

        // Megpróbáljuk beolvasni az SQL fájlt
        $sql = file_get_contents($sqlFilePath);

        if ($sql === false) {
            Log::error("Sikertelen beolvasás: " . $sqlFilePath);
            return;
        }

        Log::info("SQL file: " . $sql);

        // Hibakezelés az SQL végrehajtására
        try {
            DB::unprepared($sql);
            Log::info("ServiceSeeder sikeresen lefutott.");
        } catch (\Exception $e) {
            Log::error("Hiba az SQL végrehajtása során: " . $e->getMessage());
        }
    }
}
