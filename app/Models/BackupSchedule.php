<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupSchedule extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'frequency',
        'day_of_week',
        'day_of_month',
        'time',
        'retention_count',
        'auto_backup',
        'last_run',
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'auto_backup' => 'boolean',
        'last_run' => 'datetime',
    ];
}
