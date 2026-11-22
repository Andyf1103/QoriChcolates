<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'subject' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:2000'],
            'website' => ['nullable', 'size:0'],
        ]);

        Contacto::create([
            'nombre' => $validated['name'],
            'email' => $validated['email'],
            'asunto' => $validated['subject'],
            'mensaje' => $validated['message'],
        ]);

        return back()
            ->withInput([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
            ])
            ->with('success', 'Tu consulta fue registrada. Gracias por escribirnos.');
    }
}
