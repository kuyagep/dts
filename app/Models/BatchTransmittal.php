<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchTransmittal extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'batch_transmittals';

    protected $fillable = [
        'batch_number',
        'from_office_id',
        'to_office_id',
        'dispatched_by',
        'received_by',
        'status',
        'received_at',
        'remarks',
    ];

    public function fromOffice()
    {
        return $this->belongsTo(Office::class, 'from_office_id');
    }

    public function toOffice()
    {
        return $this->belongsTo(Office::class, 'to_office_id');
    }

    public function dispatcher()
    {
        return $this->belongsTo(User::class, 'dispatched_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function routes()
    {
        return $this->hasMany(DocumentRoute::class, 'batch_transmittal_id');
    }
}
