@extends('admin.layout')
@section('title', 'Detalle de auditoría')
@section('heading', 'Detalle de auditoría')
@section('content')
<main class="mx-auto max-w-4xl space-y-5 p-4 sm:p-6">
    <a href="{{ route('admin.auditoria.index') }}" class="text-sm text-amber-800 hover:underline">← Volver a auditoría</a>
    <section class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-950">
        <dl class="grid gap-4 sm:grid-cols-2">
            <div><dt class="text-xs uppercase text-gray-500">Fecha</dt><dd class="mt-1 font-medium">{{ $audit->created_at->format('d/m/Y H:i:s') }}</dd></div>
            <div><dt class="text-xs uppercase text-gray-500">Usuario</dt><dd class="mt-1 font-medium">{{ $audit->user?->name ?: 'Sistema' }}</dd></div>
            <div><dt class="text-xs uppercase text-gray-500">Acción</dt><dd class="mt-1 font-medium">{{ $audit->action }}</dd></div>
            <div><dt class="text-xs uppercase text-gray-500">Entidad</dt><dd class="mt-1 font-medium">{{ class_basename($audit->auditable_type) }} #{{ $audit->auditable_id }}</dd></div>
            <div><dt class="text-xs uppercase text-gray-500">IP</dt><dd class="mt-1 font-medium">{{ $audit->ip_address ?: '—' }}</dd></div>
        </dl>
    </section>
    <section class="grid gap-4 md:grid-cols-2">
        <div class="rounded-xl border border-red-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-950"><h2 class="font-semibold text-red-700">Antes</h2><pre class="mt-3 overflow-auto text-xs">{{ json_encode($audit->before, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: 'Sin datos' }}</pre></div>
        <div class="rounded-xl border border-green-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-950"><h2 class="font-semibold text-green-700">Después</h2><pre class="mt-3 overflow-auto text-xs">{{ json_encode($audit->after, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: 'Sin datos' }}</pre></div>
    </section>
</main>
@endsection
