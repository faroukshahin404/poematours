<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'phone', 'email', 'country_id', 'status', 'source', 'notes', 'created_by', 'updated_by', 'archived_at', 'archived_by'])]
class CrmContact extends Model
{
    /**
     * @var array<int, string>
     */
    protected $appends = ['source_label'];

    public const STATUS_PENDING = 'pending';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_INTERESTED = 'interested';

    public const STATUS_NOT_INTERESTED = 'not_interested';

    public const STATUS_DEAL_CLOSED = 'deal_closed';

    public const SOURCE_MANUAL = 'manual';

    public const SOURCE_WEBSITE = 'website';

    public const SOURCE_NEWSLETTER = 'newsletter';

    /**
     * @return array<string, string>
     */
    public static function sourceLabels(): array
    {
        return [
            self::SOURCE_MANUAL => 'Manual',
            self::SOURCE_WEBSITE => 'Website',
            self::SOURCE_NEWSLETTER => 'Newsletter',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONTACTED => 'Contacted',
            self::STATUS_INTERESTED => 'Interested',
            self::STATUS_NOT_INTERESTED => 'Not interested',
            self::STATUS_DEAL_CLOSED => 'Deal closed',
        ];
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(CrmService::class, 'crm_contact_service', 'crm_contact_id', 'crm_service_id')
            ->withTimestamps();
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function archiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'archived_at' => 'datetime',
        ];
    }

    public function getSourceLabelAttribute(): string
    {
        return static::sourceLabels()[$this->source] ?? (string) $this->source;
    }
}
