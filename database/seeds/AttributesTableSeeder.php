<?php

use Illuminate\Database\Seeder;
use App\Attribute;

class AttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attrs = [
        	[
        		'name' => 'ADD',
        		'min' => 1,
        		'max' => 3,
        		'steps' => 0.25,
        		'description' => 'ADICION',
                'display_order' => 4
        	],[
        		'name' => 'CIL',
        		'min' => -8,
        		'max' => 0,
        		'steps' => 0.25,
        		'description' => 'CILINDRO',
                'display_order' => 2
        	],[
        		'name' => 'ESF',
        		'min' => -20,
        		'max' => 20,
        		'steps' => 0.25,
        		'description' => 'ESFERA',
                'display_order' => 1
        	],[
        		'name' => 'EJE',
        		'min' => 1,
        		'max' => 180,
        		'steps' => 1,
        		'description' => 'EJE',
                'display_order' => 3
        	],[
        		'name' => 'DIS',
        		'min' => 20,
        		'max' => 80,
        		'steps' => 1,
        		'description' => 'DISTANCIA',
                'display_order' => 5
        	],[
        		'name' => 'ALT',
        		'min' => 5,
        		'max' => 40,
        		'steps' => 1,
        		'description' => 'ALTURA',
                'display_order' => 6
        	]
        ];

        if (! Attribute::count()) {
            foreach ($attrs as $key => $item) {
    			Attribute::create($item);
            }
        }
    }
}
