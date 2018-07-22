<?php

use Illuminate\Database\Seeder;
use App\MovementConcept;

class MovementConceptsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $concepts = [
        	['name' => 'SALIDA POR VENTA', 'code' => 'VTA', 'type' => 'S', 'is_auto' => true],
        	['name' => 'ENTRADA POR DEVOLUCION', 'code' => 'DEV', 'type' => 'E', 'is_auto' => true],
            ['name' => 'ENTRADA POR COMPRA', 'code' => NULL, 'type' => 'E', 'is_auto' => false],
            ['name' => 'ENTRADA MANUAL', 'code' => NULL, 'type' => 'E', 'is_auto' => false],
        	['name' => 'SALIDA MANUAL', 'code' => NULL, 'type' => 'S', 'is_auto' => false]
        ];

        if (! MovementConcept::count()) {
            foreach ($concepts as $key => $item) {
            	MovementConcept::create($item);
            }
        }
    }
}
