<?php

namespace App\Services;

use App\Models\ElementosEntrega;
use Illuminate\Database\Eloquent\Collection;

class ElementosEntregaSearchService
{
    /**
     * @return Collection<int, ElementosEntrega>
     */
    public function pending(string $search = ''): Collection
    {
        return $this->search(false, $search, 50);
    }

    /**
     * @return Collection<int, ElementosEntrega>
     */
    public function received(string $search = ''): Collection
    {
        return $this->search(true, $search, 100);
    }

    public function pendingCount(): int
    {
        return ElementosEntrega::query()->where('recibio', false)->count();
    }

    public function receivedCount(): int
    {
        return ElementosEntrega::query()->where('recibio', true)->count();
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(ElementosEntrega $employee): array
    {
        return [
            'id' => $employee->id,
            'nombre' => $employee->nombre,
            'csp' => $employee->csp,
            'ubicacion' => $employee->ubicacion,
            'coordinacion' => $employee->coordinacion,
            'genero' => $employee->genero,
            'tipo_uniforme' => $employee->tipo_uniforme,
            'color_franja' => $employee->color_franja,
            'camisola' => $employee->camisola,
            'pantalon' => $employee->pantalon,
            'chamarra' => $employee->chamarra,
            'bota' => $employee->bota,
            'cinturon' => $employee->cinturon,
            'receive_url' => route('entregas.recibir', $employee),
            'reprint_url' => route('entregas.reimprimir', $employee),
        ];
    }

    /**
     * @return Collection<int, ElementosEntrega>
     */
    private function search(bool $received, string $search, int $limit): Collection
    {
        return ElementosEntrega::query()
            ->where('recibio', $received)
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('nombre', 'like', "%{$search}%")
                        ->orWhere('csp', 'like', "%{$search}%");
                });
            })
            ->orderBy('nombre')
            ->limit($limit)
            ->get($this->columns());
    }

    /**
     * @return array<int, string>
     */
    private function columns(): array
    {
        return [
            'id',
            'nombre',
            'csp',
            'ubicacion',
            'coordinacion',
            'genero',
            'tipo_uniforme',
            'color_franja',
            'camisola',
            'pantalon',
            'chamarra',
            'bota',
            'cinturon',
        ];
    }
}
