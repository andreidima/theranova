<?php

namespace App\Http\Controllers\Bonusuri;

use App\Http\Controllers\Controller;
use App\Models\Lucrare;
use App\Models\LucrareBonusInterval;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ConfigurareController extends Controller
{
    public function index(): View
    {
        $lucrari = Lucrare::query()
            ->with([
                'intervaleBonus' => function ($query) {
                    $query->withCount('bonusuri');
                },
            ])
            ->withCount(['fiseCaz', 'bonusuri', 'intervaleBonus'])
            ->orderBy('denumire')
            ->get();

        $lucrariActive = $lucrari->where('activ', true)->values();

        return view('bonusuri.configurare', [
            'lucrari' => $lucrari,
            'lucrariActive' => $lucrariActive,
        ]);
    }

    public function adaugaLucrare(Request $request): RedirectResponse
    {
        $request->validate([
            'denumire' => 'required|string|max:200',
            'cod' => 'nullable|string|max:120',
            'activ' => 'nullable|boolean',
        ]);

        $denumire = trim((string) $request->input('denumire'));
        $codInput = trim((string) $request->input('cod', ''));
        $cod = $codInput !== '' ? $this->normalizeCode($codInput) : $this->genereazaCodLucrareUnic($denumire);

        if (Lucrare::withTrashed()->where('cod', $cod)->exists()) {
            return back()->with('error', 'Codul lucrarii exista deja.');
        }

        Lucrare::create([
            'denumire' => $denumire,
            'cod' => $cod,
            'activ' => $request->boolean('activ', true),
        ]);

        return back()->with('status', 'Lucrarea a fost adaugata.');
    }

    public function adaugaInterval(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lucrare_id' => [
                'required',
                Rule::exists('lucrari', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at')->where('activ', 1);
                }),
            ],
            'min_valoare' => 'required|integer|min:0|max:999999',
            'max_valoare' => 'nullable|integer|min:0|max:999999',
            'bonus_fix' => 'required|integer|min:0|max:999999',
            'bonus_procent' => 'required|integer|min:0|max:100',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
            'activ' => 'nullable|boolean',
        ]);

        if (isset($validated['max_valoare']) && $validated['max_valoare'] !== null && (float) $validated['max_valoare'] < (float) $validated['min_valoare']) {
            return back()->with('error', 'Max valoare trebuie sa fie mai mare sau egala cu Min valoare.');
        }

        LucrareBonusInterval::create([
            'lucrare_id' => (int) $validated['lucrare_id'],
            'min_valoare' => (int) $validated['min_valoare'],
            'max_valoare' => isset($validated['max_valoare']) ? (int) $validated['max_valoare'] : null,
            'bonus_fix' => (int) $validated['bonus_fix'],
            'bonus_procent' => (int) $validated['bonus_procent'],
            'valid_from' => $validated['valid_from'] ?? null,
            'valid_to' => $validated['valid_to'] ?? null,
            'activ' => $request->boolean('activ', true),
        ]);

        return back()->with('status', 'Intervalul a fost adaugat.');
    }

    public function actualizeazaInterval(Request $request, LucrareBonusInterval $interval): RedirectResponse
    {
        $validated = $request->validate([
            'min_valoare' => 'required|integer|min:0|max:999999',
            'max_valoare' => 'nullable|integer|min:0|max:999999',
            'bonus_fix' => 'required|integer|min:0|max:999999',
            'bonus_procent' => 'required|integer|min:0|max:100',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
            'activ' => 'nullable|boolean',
        ]);

        if (isset($validated['max_valoare']) && $validated['max_valoare'] !== null && (float) $validated['max_valoare'] < (float) $validated['min_valoare']) {
            return back()->with('error', 'Max valoare trebuie sa fie mai mare sau egala cu Min valoare.');
        }

        $interval->update([
            'min_valoare' => (int) $validated['min_valoare'],
            'max_valoare' => isset($validated['max_valoare']) ? (int) $validated['max_valoare'] : null,
            'bonus_fix' => (int) $validated['bonus_fix'],
            'bonus_procent' => (int) $validated['bonus_procent'],
            'valid_from' => $validated['valid_from'] ?? null,
            'valid_to' => $validated['valid_to'] ?? null,
            'activ' => $request->boolean('activ', false),
        ]);

        return back()->with('status', 'Intervalul a fost actualizat.');
    }

    public function stergeInterval(LucrareBonusInterval $interval): RedirectResponse
    {
        $numarBonusuri = $interval->bonusuri()->count();
        if ($numarBonusuri > 0) {
            return back()->with('error', 'Intervalul nu poate fi sters: este folosit in ' . $numarBonusuri . ' bonus(uri).');
        }

        $interval->delete();

        return back()->with('status', 'Intervalul a fost sters.');
    }

    public function actualizeazaLucrare(Request $request, Lucrare $lucrare): RedirectResponse
    {
        $validated = $request->validate([
            'denumire' => 'required|string|max:200',
            'cod' => 'required|string|max:120',
            'activ' => 'nullable|boolean',
        ]);

        $denumireNoua = trim((string) $validated['denumire']);
        $denumireVeche = (string) $lucrare->denumire;
        $cod = $this->normalizeCode((string) $validated['cod']);
        if ($cod === '') {
            return back()->with('error', 'Codul lucrarii este invalid.');
        }

        $codFolosit = Lucrare::withTrashed()
            ->where('id', '!=', $lucrare->id)
            ->where('cod', $cod)
            ->exists();
        if ($codFolosit) {
            return back()->with('error', 'Codul lucrarii exista deja.');
        }

        $lucrare->update([
            'denumire' => $denumireNoua,
            'cod' => $cod,
            'activ' => $request->boolean('activ', false),
        ]);

        if ($denumireVeche !== $denumireNoua && DB::getSchemaBuilder()->hasTable('fise_caz') && Schema::hasColumn('fise_caz', 'tip_lucrare_solicitata_id')) {
            DB::table('fise_caz')
                ->where('tip_lucrare_solicitata_id', $lucrare->id)
                ->update(['tip_lucrare_solicitata' => $denumireNoua]);
        }

        return back()->with('status', 'Lucrarea a fost actualizata.');
    }

    public function stergeLucrare(Lucrare $lucrare): RedirectResponse
    {
        $numarFise = $lucrare->fiseCaz()->count();
        if ($numarFise > 0) {
            return back()->with('error', 'Lucrarea nu poate fi stearsa: este folosita in ' . $numarFise . ' fișa(e) caz.');
        }

        $numarBonusuri = $lucrare->bonusuri()->count();
        if ($numarBonusuri > 0) {
            return back()->with('error', 'Lucrarea nu poate fi stearsa: are ' . $numarBonusuri . ' bonus(uri) istorice.');
        }

        $lucrare->delete();

        return back()->with('status', 'Lucrarea a fost stearsa (soft delete).');
    }

    protected function genereazaCodLucrareUnic(string $denumire): string
    {
        $base = $this->normalizeCode($denumire);
        if ($base === '') {
            $base = 'LUCRARE';
        }

        $cod = $base;
        $index = 2;

        while (Lucrare::withTrashed()->where('cod', $cod)->exists()) {
            $cod = $base . ' ' . $index;
            $index++;
        }

        return $cod;
    }

    protected function normalizeCode(string $value): string
    {
        $value = str_replace(['_', '-'], ' ', $value);
        $value = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $value) ?? '';
        $value = preg_replace('/\s+/u', ' ', $value) ?? '';
        $value = trim($value);

        return Str::upper($value);
    }
}
