<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(($handle = fopen('database/seeds/cities.csv', 'r')) !== false)
        {
            // get column headers so that they are:
            //   $headers => [
            //      'column_header' => column_index
            //   ]
            $headers = array_flip( fgetcsv($handle) );
            while (($data = fgetcsv($handle)) !== false) {
                \DB::table('cities')->insert([
                    'id' => $data[ $headers['id'] ],
                    'state_id' => $data[ $headers['state_id'] ],
                    'name' => $data[ $headers['name'] ],
                    'slug' => Str::slug( $data[ $headers['name'] ] ),
                    'population' => $data[ $headers['population'] ],
                    'latitude' => $data[ $headers['latitude'] ],
                    'longitude' => $data[ $headers['longitude'] ]
                ]);
                unset($data);
            }
            fclose($handle);
        }
    }
}
