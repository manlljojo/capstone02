<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bhp extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'stock', 'unit'];

    public function bhpUsages()
    {
        return $this->hasMany(MaintenanceBhpUsage::class);
    }
}
