<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'procurement_draft_id',
        'type',
        'name',
        'price',
        'quantity',
        'purchase_link',
        'replaced_asset_id',
        'status',
        'received_quantity'
    ];

    public function draft()
    {
        return $this->belongsTo(ProcurementDraft::class, 'procurement_draft_id');
    }

    public function replacedAsset()
    {
        return $this->belongsTo(Asset::class, 'replaced_asset_id');
    }

    public function receipts()
    {
        return $this->hasMany(ItemReceipt::class);
    }
}
