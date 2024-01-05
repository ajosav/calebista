<?php

namespace App\Console\Commands;

use Throwable;
use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;

class SpoolCountryStateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:spool-country-state-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       $data = Http::get('https://countriesnow.space/api/v0.1/countries/states')->json()['data'];

        try {
            DB::transaction(function() use($data) {
                foreach($data as $country) {
                    $new_country = Country::firstOrCreate([
                        'name' => $country['name'],
                        'code' => $country['iso2']
                    ]);
                    
                    if ($new_country->wasRecentlyCreated) {
                        $this->createState($country['states'], $new_country);
                    }
                }
            });
    
            $this->info(count($data) . ' countries seeded successfully');
        } catch(Throwable $e) {
            $this->error($e->getMessage());
        }
        
    }

    private function createState($states, Model $country)
    {
        $states_data = [];
        foreach($states as $state) {
            $states_data[] = [
                'name' => $state['name'],
                'country_id' => $country->id
            ];
        }

        $country->states()->createMany($states_data);
    }
}
