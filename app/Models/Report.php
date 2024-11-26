<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\ModelStates\HasStates;
use Spatie\Tags\HasTags;

class Report extends Model implements Auditable
{
    use HasFactory;
    use HasStates;
    use HasTags;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'report_type',
        'title',
        'evidence',
        'url',
        'mol_id_csv',
        'status',
        'comment',
        'user_id',
        'suggested_changes',
        'is_change',
        'doi',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'suggested_changes' => 'array',
    ];

    /**
     * Get all of the collections that are assigned this report.
     */
    public function collections(): MorphToMany
    {
        return $this->morphedByMany(Collection::class, 'reportable');
    }

    /**
     * Get all of the molecules that are assigned this repot.
     */
    public function molecules(): MorphToMany
    {
        return $this->morphedByMany(Molecule::class, 'reportable');
    }

    /**
     * Get all of the citations that are assigned this report.
     */
    public function citations(): MorphToMany
    {
        return $this->morphedByMany(Citation::class, 'reportable');
    }

    /**
     * Get all of the organisms that are assigned this report.
     */
    public function organisms(): MorphToMany
    {
        return $this->morphedByMany(Organism::class, 'reportable');
    }

    /**
     * Get all of the geoLocations that are assigned this report.
     */
    public function geoLocations(): MorphToMany
    {
        return $this->morphedByMany(Organism::class, 'reportable');
    }

    /**
     * Get all of the users that are assigned this report.
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the users that are assigned this report.
     */
    public function curator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
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
