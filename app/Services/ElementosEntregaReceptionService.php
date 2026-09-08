<?php

namespace App\Services;

use App\Models\ElementosEntrega;
use Illuminate\Support\Facades\DB;

class ElementosEntregaReceptionService
{
    public function receive(ElementosEntrega $elemento): ?ElementosEntrega
    {
        return DB::transaction(function () use ($elemento): ?ElementosEntrega {
            $lockedEmployee = ElementosEntrega::query()
                ->whereKey($elemento->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedEmployee->recibio) {
                return null;
            }

            $lockedEmployee->update(['recibio' => true]);

            return $lockedEmployee->fresh();
        });
    }
}
