<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    /**
     * Get the document type classification.
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Get the office that created/originated this document.
     */
    public function originatingOffice(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'originating_office_id');
    }

    /**
     * Get the office where the document currently resides.
     */
    public function currentOffice(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'current_office_id');
    }

    /**
     * Get the office where the document is intended to go.
     */
    public function destinationOffice(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'destination_office_id');
    }

    /**
     * Get the user who created the document record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the current user holding/processing the document.
     */
    public function currentCustodian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_custodian_id');
    }

    /**
     * Get all routing history steps.
     */
    public function routes(): HasMany
    {
        return $this->hasMany(DocumentRoute::class)->orderBy('step_number');
    }

    /**
     * Get all audit trail logs.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(DocumentLog::class)->latest();
    }
}
