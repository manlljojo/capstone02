<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'name',
        'code',
        'barcode_photo',
        'status',
        'price',
        'purchase_date'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class)->orderBy('maintenance_date', 'desc');
    }

    public function replacementProcurementItems()
    {
        return $this->hasMany(ProcurementItem::class, 'replaced_asset_id');
    }
}
