<?php

namespace App\Http\Controllers\API\Schemas\Bioschemas;

use App\Http\Controllers\Controller;
use App\Models\Molecule;
use Cache;
use Illuminate\Http\Request;

/**
 * Implement Bioschemas MolecularEntity on COCONUT molecules to enable exporting
 * their metadata with a json endpoint and increase their findability.
 *
 * @param  \Illuminate\Http\Request  $request  The incoming request instance.
 * @param  string  $identifier  The unique identifier for the molecule.
 * @return mixed The schema of the molecule, either from cache or generated dynamically.
 */
class MolecularEntityController extends Controller
{
    public function moleculeSchema(Request $request, $identifier)
    {
        $molecule = Cache::remember('molecules.'.$identifier, 172800, function () use ($identifier) {
            return Molecule::where('identifier', $identifier)->first();
        });

        if (isset($molecule['schema'])) {
            return $molecule['schema'];
        }

        return $molecule->getSchema('bioschema');
    }
}
