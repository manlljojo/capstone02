<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceBhpUsage extends Model
{
    use HasFactory;

    protected $table = 'maintenance_bhp_usage';

    protected $fillable = [
        'maintenance_log_id',
        'bhp_id',
        'quantity_used'
    ];

    public function maintenanceLog()
    {
        return $this->belongsTo(MaintenanceLog::class);
    }

    public function bhp()
    {
        return $this->belongsTo(Bhp::class);
    }
}
