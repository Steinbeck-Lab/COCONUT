<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;
use Str;

class Collection extends Model implements Auditable, HasMedia
{
    use HasFactory;
    use HasTags;
    use InteractsWithMedia;
    use \OwenIt\Auditing\Auditable;

    protected static function booted()
    {
        static::creating(fn ($collection) => $collection->uuid = Str::uuid());
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'jobs_status',
        'comments',
        'identifier',
        'url',
        'image',
        'status',
        'release_date',
    ];

    /**
     * Get the license of the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function license()
    {
        return $this->belongsTo(License::class, 'license_id');
    }

    /**
     * Get all of the citations for the collection.
     */
    public function citations(): MorphToMany
    {
        return $this->morphToMany(Citation::class, 'citable');
    }

    /**
     * Get all of the entries for the collection.
     */
    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }

    /**
     * Get the molecules associated with the collection.
     */
    public function molecules(): BelongsToMany
    {
        return $this->belongsToMany(Molecule::class)->withPivot('url', 'reference', 'mol_filename', 'structural_comments')->withTimestamps();
    }

    /**
     * Get all of the reports for the collection.
     */
    public function reports(): MorphToMany
    {
        return $this->morphToMany(Report::class, 'reportable');
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
