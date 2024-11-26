<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;

class SampleLocation extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'iri',
        'slug',
        'organism_id',
        'collection_ids',
        'molecule_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'collection_ids' => 'array',
    ];

    /**
     * Get the organism that exists in the sample location.
     */
    public function organisms(): HasOne
    {
        return $this->hasOne(Organism::class);
    }

    /**
     * Get the molecules that belong to the sample location.
     */
    public function molecules(): BelongsToMany
    {
        return $this->belongsToMany(Molecule::class)->withTimestamps();
    }

    /**
     * Transforms the audit data by applying a custom function to the provided array.
     *
     * @param  array  $data  The audit data to transform.
     * @return array The transformed audit data.
     */
    public function transformAudit(array $data): array
    {
        return changeAudit($data);
    }
}
