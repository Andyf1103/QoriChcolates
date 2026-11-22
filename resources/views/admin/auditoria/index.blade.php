@extends('admin.layout')
@section('title', 'Auditoría - Qori Chocolates')
@section('heading', 'Auditoría')
@section('content')
<div class="mx-auto max-w-7xl space-y-5 p-4 sm:p-6">
    <div><h2 class="text-2xl font-bold">Registro de auditoría</h2><p class="text-sm text-gray-500">Acciones administrativas recientes.</p></div>
    <p class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">Estos registros son históricos y no se pueden editar ni eliminar.</p>
    <form method="GET" class="flex flex-col gap-3 rounded-xl border border-gray-200 bg-white p-4 sm:flex-row dark:border-gray-800 dark:bg-gray-950"><select name="accion" aria-label="Filtrar por acción" class="rounded-lg border-gray-300 px-3 py-2"><option value="">Todas las acciones</option>@foreach(['created' => 'Creado', 'updated' => 'Actualizado', 'status_changed' => 'Estado cambiado', 'stock_changed' => 'Stock cambiado', 'deleted' => 'Eliminado', 'restored' => 'Restaurado'] as $value => $label)<option value="{{ $value }}" @selected(request('accion') === $value)>{{ $label }}</option>@endforeach</select><select name="entidad" aria-label="Filtrar por entidad" class="rounded-lg border-gray-300 px-3 py-2"><option value="">Todas las entidades</option>@foreach([App\Models\Producto::class => 'Productos', App\Models\Categoria::class => 'Categorías'] as $value => $label)<option value="{{ $value }}" @selected(request('entidad') === $value)>{{ $label }}</option>@endforeach</select><button class="rounded-lg bg-gray-800 px-4 py-2 font-semibold text-white">Filtrar</button><a href="{{ route('admin.auditoria.index') }}" class="rounded-lg border px-4 py-2 text-center">Limpiar</a></form>
    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950"><table class="w-full min-w-[850px] text-left text-sm"><thead><tr class="border-b text-xs uppercase text-gray-500 dark:border-gray-800"><th class="px-5 py-3">Fecha</th><th>Usuario</th><th>Acción</th><th>Modelo</th></tr></thead><tbody>
    @forelse($audits as $audit)
        @php
            $entidad = class_basename($audit->auditable_type) === 'Categoria' ? 'Categoría' : 'Producto';
            $acciones = [
                'created' => 'creado',
                'updated' => 'actualizado',
                'status_changed' => 'estado cambiado',
                'stock_changed' => 'stock cambiado',
                'deleted' => 'eliminado',
                'restored' => 'restaurado',
            ];
            $descripcionAccion = $entidad.' '.($acciones[$audit->action] ?? $audit->action);
        @endphp
        <tr class="border-b last:border-0 dark:border-gray-800"><td class="px-5 py-3">{{ $audit->created_at->format('d/m/Y H:i:s') }}</td><td>{{ $audit->user?->name ?: 'Sistema' }}</td><td><span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-900">{{ $descripcionAccion }}</span></td><td><a class="text-amber-800 hover:underline" href="{{ route('admin.auditoria.show', $audit) }}">{{ $entidad }} #{{ $audit->auditable_id }}</a></td></tr>
    @empty
        <tr><td colspan="4" class="px-5 py-10 text-center text-gray-500">No hay acciones registradas.</td></tr>
    @endforelse
    </tbody></table></div>{{ $audits->links() }}
</div>
@endsection
