<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $audits = Audit::with('user')
            ->when($request->filled('accion'), fn ($q) => $q->where('action', $request->string('accion')))
            ->when($request->filled('entidad'), fn ($q) => $q->where('auditable_type', $request->string('entidad')))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.auditoria.index', compact('audits'));
    }

    public function show(Audit $audit): View
    {
        return view('admin.auditoria.show', [
            'audit' => $audit->load('user'),
        ]);
    }
}
