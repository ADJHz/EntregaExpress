<?php

namespace App\Http\Controllers;

use App\Models\ElementosEntrega;
use App\Services\ElementosEntregaReceptionService;
use App\Services\ElementosEntregaReportService;
use App\Services\ElementosEntregaSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ElementosEntregaController extends Controller
{
    public function __construct(
        private readonly ElementosEntregaSearchService $searchService,
        private readonly ElementosEntregaReceptionService $receptionService,
        private readonly ElementosEntregaReportService $reportService,
    ) {}

    public function index(): View
    {
        return view('pages.EntregaExpress', [
            'pendingCount' => $this->searchService->pendingCount(),
            'receivedCount' => $this->searchService->receivedCount(),
        ]);
    }

    public function pending(Request $request): JsonResponse
    {
        $search = $this->validatedSearch($request);
        $employees = $this->searchService->pending($search)
            ->map(fn(ElementosEntrega $employee): array => $this->searchService->payload($employee))
            ->values();

        return response()->json(['data' => $employees]);
    }

    public function received(Request $request): JsonResponse
    {
        $search = $this->validatedSearch($request);
        $employees = $this->searchService->received($search)
            ->map(fn(ElementosEntrega $employee): array => $this->searchService->payload($employee))
            ->values();

        return response()->json(['data' => $employees]);
    }

    public function receive(ElementosEntrega $elemento): View|RedirectResponse
    {
        $received = $this->receptionService->receive($elemento);

        if (! $received) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Este empleado ya fue marcado como recibido.');
        }

        return view('pages.receipt', [
            'elemento' => $received,
            'autoPrint' => true,
        ]);
    }

    public function reprint(ElementosEntrega $elemento): View
    {
        abort_unless($elemento->recibio, 404);

        return view('pages.receipt', [
            'elemento' => $elemento,
            'autoPrint' => false,
        ]);
    }

    public function report(Request $request): StreamedResponse
    {
        $status = $request->query('estado', 'ambos');
        abort_unless(in_array($status, ['recibidos', 'pendientes', 'ambos'], true), 422);

        return $this->reportService->download($status);
    }

    private function validatedSearch(Request $request): string
    {
        return trim($request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ])['search'] ?? '');
    }
}
