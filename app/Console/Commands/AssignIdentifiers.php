<?php

namespace App\Console\Commands;

use App\Models\Molecule;
use App\Models\Ticker;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class AssignIdentifiers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coconut:molecules-assign-identifiers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command to assign identifiers to molecules and update their related data.
     *
     * The method performs the following steps:
     * 1. Assigns identifiers to molecules without stereo information and updates the database.
     * 2. Generates and stores parent-child mappings based on molecule relationships.
     * 3. Updates molecule identifiers using the stored mappings and updates the database in batches.
     * 4. Maps mismatched parent IDs from a CSV file and updates the database.
     * 5. Updates placeholder flags for molecules from a CSV file.
     *
     * @return void
     */
    public function handle()
    {
        $batchSize = 10000;

        $mapped_data = array_map('str_getcsv', file(storage_path('collection_molecule_no_duplicates.csv')));

        Collection::make($mapped_data)->chunk($batchSize)->each(function ($chunk) use (&$i) {
            echo $i;
            echo "\r\n";
            DB::transaction(function () use ($chunk) {
                foreach ($chunk as $data) {
                    DB::table('molecules')
                        ->where('id', $data)
                        ->update(['is_placeholder' => false]);
                }
            });
            $i++;
        });
    }

    /**
     * Generates a unique identifier for a molecule.
     *
     * @param  int  $index  An incrementing index used to generate the identifier.
     * @param  string  $type  The type of molecule ('parent' or other) to determine the identifier format.
     * @return string A unique identifier for the molecule.
     */
    public function generateIdentifier($index, $type)
    {
        if ($type == 'parent') {
            return 'CNP'.str_pad($index, 7, '0', STR_PAD_LEFT).'.0';
        } else {
            return 'CNP'.str_pad($index, 7, '0', STR_PAD_LEFT);
        }
    }

    /**
     * Retrieves the last index of a molecule identifier from the database.
     *
     * The method fetches the last index from the 'tickers' table where the type is 'molecule'.
     *
     * @return int The last index of a molecule identifier.
     */
    public function fetchLastIndex()
    {
        $ticker = Ticker::where('type', 'molecule')->first();

        return (int) $ticker->index;
    }
}
