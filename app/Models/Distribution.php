<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distribution extends Model
{
    /** @use HasFactory<\Database\Factories\DistributionFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'campaign_id',
        'mustahik_id',
        'amount',
        'distribution_date',
        'description',
        'proof_of_distribution',
        'distributed_by',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'distribution_date' => 'date',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function mustahik(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mustahik_id');
    }

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'distributed_by');
    }
}
