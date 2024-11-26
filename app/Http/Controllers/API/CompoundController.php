<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MoleculeResource;
use App\Models\Molecule;
use Illuminate\Http\Request;

class CompoundController extends Controller
{
    /**
     * Retrieve a molecule or a specific property of it by its identifier.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming request instance.
     * @param  string  $id  The identifier of the molecule.
     * @param  string|null  $property  (Optional) The specific property of the molecule to retrieve.
     * @param  string|null  $key  (Optional) A specific key within the property to retrieve.
     * @return mixed The molecule, its property, or a specific key within the property.
     *
     * This method fetches a molecule by its identifier, including its properties and citations.
     * If a property is provided, it returns the requested property. If both a property and key
     * are provided, it returns the value associated with the key within the property.
     */
    public function id(Request $request, $id, $property = null, $key = null)
    {
        $molecule = Molecule::with(['properties', 'citations'])->where('identifier', $id)->firstOrFail();

        if (isset($property)) {
            if (isset($key)) {
                return $molecule[$property][$key];
            }

            return $molecule[$property];
        }

        return $molecule;
    }

    /**
     * Retrieve a paginated list of active molecules with optional sorting.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming request instance.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection A paginated collection of active molecules.
     *
     * This method retrieves active molecules from the database, optionally sorted by the latest updated time.
     * The page size can be customized through the 'size' request parameter, with a default of 15.
     * If the 'sort' parameter is set to 'latest', molecules are ordered by the most recently updated.
     */
    public function list(Request $request)
    {
        $sort = $request->get('sort');
        $size = $request->get('size');

        if ($size == null) {
            $size = 15;
        }

        if ($sort == 'latest') {
            return MoleculeResource::collection(Molecule::where('active', true)->orderByDesc('updated_at')->paginate($size));
        } else {
            return MoleculeResource::collection(Molecule::where('active', true)->paginate($size));
        }

        return MoleculeResource::collection(Molecule::where('active', true)->paginate($size));
    }
}
