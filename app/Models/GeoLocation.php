<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GeoLocation extends Model implements Auditable
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
    ];

    /**
     * Get all of the molecules that are associated with the GeoLocation
     *
     * @return BelongsToMany
     */
    public function molecules()
    {
        return $this->belongsToMany(Molecule::class)->withPivot('locations')->withTimestamps();
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
            TextInput::make('name')
                ->required()
                ->maxLength(255),
        ];
    }
}
