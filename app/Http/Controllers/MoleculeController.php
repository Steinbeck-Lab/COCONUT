<?php

namespace App\Http\Controllers;

use App\Models\Molecule;
use Cache;
use Illuminate\Http\Request;

class MoleculeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        $molecule = Cache::remember('molecules.'.$id, 1440, function () use ($id) {
            return Molecule::where('identifier', $id)->first();
        });

        return view('molecule', [
            'molecule' => $molecule,
        ]);
    }
}
