<?php

namespace App\Http\Controllers;

use App\Models\Molecule;
use Cache;
use Illuminate\Http\Request;

class MoleculeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming request instance.
     * @param  string  $id  The identifier of the molecule.
     * @return \Illuminate\View\View The rendered view for the molecule.
     */
    public function __invoke(Request $request, $id)
    {
        if (strpos($id, '.') === false) {
            $id .= '.0';
        }

        $molecule = Cache::remember('molecules.'.$id, 172800, function () use ($id) {
            return Molecule::where('identifier', $id)->first();
        });

        if ($molecule) {
            return view('molecule', [
                'molecule' => $molecule,
            ]);
        }

        abort(404);
    }
}
