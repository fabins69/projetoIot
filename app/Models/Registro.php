<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registro extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_id',
        'valor',
        'umidade',
        'data_hora',
    ];

    protected function casts(): array
    {
        return [
            'data_hora' => 'datetime',
        ];
    }

    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class);
    }
}
