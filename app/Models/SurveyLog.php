<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyLog extends Model
{
    protected $fillable = [
        'survey_id',
        'user_id',
        'action',
        'old_data',
        'new_data',
        'changes',
        'description',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'changes' => 'array'
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'created' => 'Criado',
            'updated' => 'Atualizado',
            'deleted' => 'Eliminado',
            default => 'Desconhecido'
        };
    }

    public function getFormattedChangesAttribute(): array
    {
        if (!$this->changes) {
            return [];
        }

        $formatted = [];
        foreach ($this->changes as $field => $change) {
            $fieldName = $this->getFieldDisplayName($field);
            $formatted[] = [
                'field' => $fieldName,
                'old' => $change['old'] ?? 'N/A',
                'new' => $change['new'] ?? 'N/A'
            ];
        }

        return $formatted;
    }

    private function getFieldDisplayName(string $field): string
    {
        return match ($field) {
            'readings' => 'Leituras',
            'month' => 'Mês',
            'year' => 'Ano',
            'days_in_month' => 'Dias no mês',
            'station_id' => 'Posto',
            default => ucfirst($field)
        };
    }
}