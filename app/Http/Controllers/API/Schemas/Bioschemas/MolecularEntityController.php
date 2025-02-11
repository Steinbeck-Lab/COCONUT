<?php

namespace App\Http\Controllers\API\Schemas\Bioschemas;

use App\Http\Controllers\Controller;
use App\Models\Molecule;
use Cache;
use Illuminate\Http\Request;

/**
 * Implement Bioschemas MolecularEntity on COCONUT molecules to enable exporting
 * their metadata with a json endpoint and increase their findability.
 */
class MolecularEntityController extends Controller
{
    public function moleculeSchema(Request $request, $identifier)
    {
        $molecule = Cache::flexible('molecules.'.$identifier, [172800, 259200], function () use ($identifier) {
            return Molecule::where('identifier', $identifier)->first();
        });

        if (isset($molecule['schema'])) {
            return $molecule['schema'];
        }

        return $molecule->getSchema('bioschema');
    }
}
