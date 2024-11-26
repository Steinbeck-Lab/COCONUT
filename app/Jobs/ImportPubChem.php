<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ImportPubChem implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $molecule;

    /**
     * Create a new job instance.
     *
     * @param  mixed  $molecule
     */
    public function __construct($molecule)
    {
        $this->molecule = $molecule;
    }

    /**
     * Get a unique identifier for the queued job.
     */
    public function uniqueId(): string
    {
        return $this->molecule->id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->fetchIUPACNameFromPubChem();
    }

    /**
     * Fetch IUPAC Name from PubChem.
     *
     * This function first fetches the PubChem CID from the given SMILES string.
     * If the CID is not 0, it will fetch the properties of the CID, and look for the IUPAC Name.
     * If the IUPAC Name is found, it will update the molecule's iupac_name field with the found value.
     * Finally, it will save the molecule.
     *
     * @return void
     */
    public function fetchIUPACNameFromPubChem()
    {
        $smilesURL = 'https://pubchem.ncbi.nlm.nih.gov/rest/pug/compound/smiles/cids/TXT?smiles='.urlencode($this->molecule->canonical_smiles);
        $cid = Http::get($smilesURL)->body();
        echo $cid;
        if ($cid && $cid != 0) {
            $cidPropsURL = 'https://pubchem.ncbi.nlm.nih.gov/rest/pug/compound/cid/'.trim(preg_replace('/\s+/', ' ', $cid)).'/json';
            $data = Http::get($cidPropsURL)->json();
            $props = $data['PC_Compounds'][0]['props'];
            $IUPACName = null;
            foreach ($props as $prop) {
                if ($prop['urn']['label'] == 'IUPAC Name' && $prop['urn']['name'] == 'Preferred') {
                    $IUPACName = $prop['value']['sval'];
                }
            }
            $this->fetchSynonymsCASFromPubChem($cid);
            if ($IUPACName) {
                $this->molecule->iupac_name = $IUPACName;
            }
            $this->molecule->save();
        }
    }

    /**
     * Fetch synonyms and CAS registry numbers from PubChem.
     *
     * This function takes a PubChem CID and fetches the synonyms of the compound.
     * It then searches for CAS registry numbers in the synonyms and stores them in the 'cas' field of the molecule.
     * If the molecule does not have a name, it will set the name to the first synonym.
     *
     * @param  string  $cid  The PubChem CID of the compound.
     * @return void
     */
    public function fetchSynonymsCASFromPubChem($cid)
    {
        if ($cid && $cid != 0) {
            $synonymsURL = 'https://pubchem.ncbi.nlm.nih.gov/rest/pug/compound/cid/'.trim(preg_replace('/\s+/', ' ', $cid)).'/synonyms/txt';
            $data = Http::get($synonymsURL)->body();
            $synonyms = preg_split("/\r\n|\n|\r/", $data);
            if ($synonyms && count($synonyms) > 0) {
                if ($synonyms[0] != 'Status: 404') {
                    $pattern = "/\b[1-9][0-9]{1,5}-\d{2}-\d\b/";
                    $casIds = preg_grep($pattern, $synonyms);
                    if ($this->molecule->synonyms) {
                        $_synonyms = $this->molecule->synonyms;
                        $synonyms[] = $_synonyms;
                        $this->molecule->synonyms = $synonyms;
                    } else {
                        $this->molecule->synonyms = $synonyms;
                    }
                    $this->molecule->cas = array_values($casIds);
                    if (! $this->molecule->name) {
                        $this->molecule->name = $synonyms[0];
                    }
                }
            }
        }
    }
}
