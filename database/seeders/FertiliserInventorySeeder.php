<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FertiliserInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Import the provided csv to "inventory" table
        $csvFile = fopen(storage_path("Fertiliser_inventory_movements.csv"), "r"); // "r" Open a file for read only
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== FALSE) {
            if (!$firstline) {
                //Make sure to alline the $data to its correct table column
                DB::table('inventory')->insert([
                    'type' => $data[1],
                    'quantity' => $data[2],
                    'unit_price' => !empty($data[3]) ? $data[3] : 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile); //Make sure to close file to save server resources
    }
}
