<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

#[Fillable(['path', 'storage_path', 'model_type', 'model_id', 'created_by'])]
class Media extends Model
{
    public function model(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function publicUrl(): string
    {
        if (Str::startsWith($this->path, ['assets/', '/assets/'])) {
            return asset(ltrim($this->path, '/'));
        }

        return asset('storage/'.$this->path);
    }
}
