<?php

namespace App\Support;

use App\Models\Audit;
use Illuminate\Database\Eloquent\Model;

trait RecordsAdminAudit
{
    protected function recordAudit(string $action, Model $model, ?array $before = null, ?array $after = null): void
    {
        Audit::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'auditable_type' => $model::class,
            'auditable_id' => $model->getKey(),
            'before' => $before,
            'after' => $after,
            'ip_address' => request()->ip(),
        ]);
    }
}
