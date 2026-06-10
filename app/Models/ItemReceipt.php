<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'procurement_item_id',
        'received_date',
        'quantity_received',
        'received_by'
    ];

    public function item()
    {
        return $this->belongsTo(ProcurementItem::class, 'procurement_item_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
