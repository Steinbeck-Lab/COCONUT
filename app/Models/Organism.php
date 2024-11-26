<?php

namespace App\Models;

use Filament\Forms;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use OwenIt\Auditing\Contracts\Auditable;

class Organism extends Model implements Auditable
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
        'rank',
        'molecule_count',
        'slug',
    ];

    /**
     * Get the molecules that belong to the organism.
     */
    public function molecules(): BelongsToMany
    {
        return $this->belongsToMany(Molecule::class)->withTimestamps();
    }

    /**
     * Get all of the reports for the organism.
     */
    public function reports(): MorphToMany
    {
        return $this->morphToMany(Report::class, 'reportable');
    }

    /**
     * Get all of the sample locations for the organism.
     */
    public function sampleLocations(): HasMany
    {
        return $this->hasMany(SampleLocation::class);
    }

    /**
     * Get the iri attribute.
     *
     * @return string
     */
    public function getIriAttribute($value)
    {
        return urldecode($value);
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

    /**
     * Returns the form schema with fields for name.
     *
     * @return array The form schema.
     */
    public static function getForm(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->unique(Organism::class, 'name')
                ->maxLength(255),
            Forms\Components\TextInput::make('iri')
                ->label('IRI')
                ->maxLength(255),
            Forms\Components\TextInput::make('rank')
                ->maxLength(255),
        ];
    }
}
