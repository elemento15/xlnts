<?php

use Illuminate\Database\Seeder;
use App\VisitType;

class VisitTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $concepts = [
        	['name' => 'Visita', 'code' => 'VIS'],
        	['name' => 'Venta',  'code' => 'VTA']
        ];

        foreach ($concepts as $key => $item) {
        	VisitType::updateOrCreate($item);
        }
    }
}
