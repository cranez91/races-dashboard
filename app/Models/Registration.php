<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = ['participant_id', 'race_id', 'category'];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['race_id'] ?? null, fn($q, $raceId) =>
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
