<?php

namespace App\Http\Controllers;

use App\Models\InformatiiGenerale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InformatiiGeneraleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $informatii = InformatiiGenerale::orderBy('variabila')->get();

        return view('informatiiGenerale.index', compact('informatii'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        InformatiiGenerale::create($data);

        return redirect()->route('informatii-generale.index')->with('status', 'Informația a fost adăugată cu succes!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InformatiiGenerale $informatiiGenerale): RedirectResponse
    {
        $data = $this->validateData($request, $informatiiGenerale->id);

        $informatiiGenerale->update($data);

        return redirect()->route('informatii-generale.index')->with('status', 'Informația a fost actualizată cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InformatiiGenerale $informatiiGenerale): RedirectResponse
    {
        $informatiiGenerale->delete();

        return redirect()->route('informatii-generale.index')->with('status', 'Informația a fost ștearsă cu succes!');
    }

    protected function validateData(Request $request, ?int $id = null): array
    {
        $uniqueRule = 'unique:informatii_generale,variabila';

        if ($id) {
            $uniqueRule .= ',' . $id;
        }

        return $request->validate([
            'variabila' => ['required', 'string', 'max:191', $uniqueRule],
            'valoare' => ['nullable', 'string'],
        ]);
    }
}
