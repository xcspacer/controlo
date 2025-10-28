<?php

namespace App\Traits;

use App\Models\SurveyLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    public function logActivity(string $action, $oldData = null, $newData = null, ?string $description = null): void
    {
        $changes = null;

        if ($action === 'updated' && $oldData && $newData) {
            $changes = $this->calculateFieldChanges($oldData, $newData);
        }

        SurveyLog::create([
            'survey_id' => $this->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'old_data' => $oldData,
            'new_data' => $newData,
            'changes' => $changes,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent()
        ]);
    }

    protected function calculateFieldChanges(array $oldData, array $newData): array
    {
        $changes = [];

        foreach ($newData as $key => $newValue) {
            $oldValue = $oldData[$key] ?? null;

            if ($this->hasFieldChanged($oldValue, $newValue)) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue
                ];
            }
        }

        return $changes;
    }

    protected function hasFieldChanged($oldValue, $newValue): bool
    {
        if (is_array($oldValue) && is_array($newValue)) {
            return json_encode($oldValue) !== json_encode($newValue);
        }

        return $oldValue != $newValue;
    }
}