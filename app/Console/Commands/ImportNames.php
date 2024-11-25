<?php

namespace App\Console\Commands;

use App\Models\Entry;
use App\Models\Molecule;
use DB;
use Illuminate\Console\Command;

class ImportNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coconut:molecules-import-names';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * This command will go through all entries that have a name, and
     * add that name to the synonyms array for the associated molecule.
     * If the molecule doesn't have a name, it will be set.
     *
     * @return int
     */
    public function handle()
    {

        // Map them via entry -> molecule
        Entry::whereNotNull('name')->whereStatus('PASSED')->select('id', 'name', 'molecule_id')->chunk(10000, function ($entries) {
            DB::transaction(function () use ($entries) {
                foreach ($entries as $entry) {
                    echo $entry->id;
                    echo "\r\n";
                    echo $entry->name;
                    echo "\r\n";
                    $molecule = $entry->molecule;
                    if (! $molecule->name) {
                        $molecule->name = $entry->name;
                    }
                    if ($molecule->synonyms) {
                        $synonyms = $molecule->synonyms;
                        $synonyms[] = $entry->name;
                        $molecule->synonyms = $synonyms;
                    } else {
                        $molecule->synonyms = [$entry->name];
                    }
                    $molecule->save();
                }
            });
        });
    }
}
