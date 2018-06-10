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
        		'description' => 'ADICION'
        	],[
        		'name' => 'CIL',
        		'min' => -8,
        		'max' => 0,
        		'steps' => 0.25,
        		'description' => 'CILINDRO'
        	],[
        		'name' => 'ESF',
        		'min' => -20,
        		'max' => 20,
        		'steps' => 0.25,
        		'description' => 'ESFERA'
        	],[
        		'name' => 'EJE',
        		'min' => 1,
        		'max' => 180,
        		'steps' => 1,
        		'description' => 'EJE'
        	],[
        		'name' => 'DIP',
        		'min' => 20,
        		'max' => 80,
        		'steps' => 1,
        		'description' => 'DIP'
        	],[
        		'name' => 'ALT',
        		'min' => 5,
        		'max' => 40,
        		'steps' => 1,
        		'description' => 'ALTURA'
        	]
        ];

        foreach ($attrs as $key => $item) {
			Attribute::create($item);
        }
    }
}
