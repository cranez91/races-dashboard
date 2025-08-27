<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = ['participant_id', 'race_id', 'category', 'notes'];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    /**
     * Scope a query to filter results based on the given criteria.
     *
     * This method applies various filters to the query based on the provided array of filters.
     * It allows filtering by race, gender, category, and a search term for participant names.
     *
     * @param Builder $query The query builder instance to apply the filters on.
     * @param array $filters An associative array of filters to apply:
     *                       - 'race' (int|null): The ID of the race to filter by.
     *                       - 'gender' (string|null): The gender to filter participants by.
     *                       - 'category' (string|null): The category to filter by.
     *                       - 'search' (string|null): A search term to filter participants by name.
     *
     * @return Builder The modified query builder instance with applied filters.
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['race'] ?? null, fn($q, $raceId) =>
                $q->where('race_id', $raceId)
            )
            ->when($filters['gender'] ?? null, fn($q, $gender) =>
                $q->whereHas('participant', fn($p) => $p->where('gender', $gender))
            )
            ->when($filters['category'] ?? null, fn($q, $category) =>
                $q->where('category', $category)
            )
            ->when($filters['search'] ?? null, fn($q, $search) =>
                $q->whereHas('participant', fn($p) =>
                    $p->where('name', 'ILIKE', "%{$search}%")
                )
            );
    }
}
