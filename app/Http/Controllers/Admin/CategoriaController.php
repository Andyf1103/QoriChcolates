<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoriaRequest;
use App\Models\Categoria;
use App\Support\RecordsAdminAudit;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    use RecordsAdminAudit;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.categorias.index', [
            'categorias' => Categoria::withCount('productos')
                ->when($request->boolean('papelera'), fn ($q) => $q->onlyTrashed())
                ->when(! $request->boolean('papelera'), fn ($q) => $q->withoutTrashed())
                ->when($request->filled('buscar'), fn ($q) => $q->where('nombre', 'like', '%'.$request->string('buscar').'%'))
                ->orderBy('nombre')->paginate(15)->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoriaRequest $request)
    {
        $data = $request->validated();
        $categoria = Categoria::create($data);
        $this->recordAudit('created', $categoria, null, $categoria->toArray());

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        return redirect()->route('admin.categorias.edit', $categoria);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoriaRequest $request, Categoria $categoria)
    {
        $data = $request->validated();
        $before = $categoria->toArray();
        $categoria->update($data);
        $this->recordAudit('updated', $categoria, $before, $categoria->fresh()->toArray());

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        if ($categoria->productos()->exists()) {
            return back()->with('error', 'No se puede eliminar una categoría que tiene productos asociados.');
        }

        $before = $categoria->toArray();
        $categoria->delete();
        $this->recordAudit('deleted', $categoria, $before, null);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría enviada a la papelera. Puedes restaurarla.');
    }

    public function restore(int $categoria)
    {
        $deleted = Categoria::withTrashed()->findOrFail($categoria);
        if (Categoria::where('nombre', $deleted->nombre)->where('id', '!=', $deleted->id)->exists()
            || Categoria::where('slug', $deleted->slug)->where('id', '!=', $deleted->id)->exists()) {
            return back()->with('error', 'No se puede restaurar: ya existe una categoría con el mismo nombre o slug.');
        }
        $deleted->restore();
        $this->recordAudit('restored', $deleted, null, $deleted->toArray());

        return back()->with('success', 'Categoría restaurada correctamente.');
    }

}
