<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(($handle = fopen('database/seeds/states.csv', 'r')) !== false)
        {
            // get column headers so that they are:
            //   $headers => [
            //      'column_header' => column_index
            //   ]
            $headers = array_flip( fgetcsv($handle) );
            while (($data = fgetcsv($handle)) !== false) {
                \DB::table('states')->insert([
                    'id' => $data[ $headers['id'] ],
                    'name' => $data[ $headers['name'] ],
                    'slug' => Str::slug( $data[ $headers['name'] ] ),
                    'abbr' => $data[ $headers['abbr'] ]
                ]);
                unset($data);
            }
            fclose($handle);
        }
    }
}
