<?php

namespace App\Console\Commands;

use App\Models\Molecule;
use DB;
use Illuminate\Console\Command;
use Log;

class ImportNPClassifierOutput extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coconut:import-np-classifier-output  {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command to import NPClassifier output from a file.
     *
     * This method performs the following steps:
     * 1. Validates the presence and readability of the input file.
     * 2. Reads the file as a tab-separated values (TSV) format.
     * 3. Collects data in batches and inserts it into the database.
     * 4. Logs and outputs the number of rows inserted.
     * 5. Returns 0 on successful import, or 1 if the file is not found or not readable.
     *
     * @return int Returns 0 on successful import, or 1 if the file is not found or not readable.
     */
    public function handle()
    {
        $file = storage_path($this->argument('file'));

        if (! file_exists($file) || ! is_readable($file)) {
            $this->error('File not found or not readable.');

            return 1;
        }

        Log::info('Reading file: '.$file);

        $batchSize = 10000;
        $header = null;
        $data = [];
        $rowCount = 0;

        if (($handle = fopen($file, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, "\t")) !== false) {
                if (! $header) {
                    $header = $row;
                } else {
                    try {
                        $data[] = array_combine($header, $row);
                        $rowCount++;
                        if ($rowCount % $batchSize == 0) {
                            $this->insertBatch($data);
                            $this->info('Rows inserted: '.$rowCount);
                            $data = [];
                        }
                    } catch (\ValueError $e) {
                        Log::info('An error occurred: '.$e->getMessage());
                        Log::info($rowCount++);
                    }
                }
            }
            fclose($handle);

            if (! empty($data)) {
                $this->insertBatch($data);
            }
        }

        $this->info('Data imported successfully!');

        return 0;
    }

    /**
     * Insert a batch of data into the database.
     *
     * @return void
     */
    private function insertBatch(array $data)
    {
        DB::transaction(function () use ($data) {
            foreach ($data as $row) {
                $properties = Molecule::where('identifier', $row['identifier'])->first()->properties()->get()[0];
                $properties['np_classifier_pathway'] = $row['pathway_results'] == '' ? null : $row['pathway_results'];
                $properties['np_classifier_superclass'] = $row['superclass_results'] == '' ? null : $row['superclass_results'];
                $properties['np_classifier_class'] = $row['class_results'] == '' ? null : $row['class_results'];
                $properties['np_classifier_is_glycoside'] = $row['isglycoside'] == '' ? null : $row['isglycoside'];
                $properties->save();
            }
        });
    }
}
