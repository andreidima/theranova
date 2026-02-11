<?php

namespace App\Http\Controllers\Tech;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ImpersonareController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->search;
        $currentUserId = (int) Auth::id();

        $users = User::query()
            ->when($search, function ($query, $search) {
                foreach (explode(' ', trim($search)) as $term) {
                    if ($term === '') {
                        continue;
                    }

                    $query->where(function ($innerQuery) use ($term) {
                        $innerQuery->where('name', 'like', '%' . $term . '%')
                            ->orWhere('email', 'like', '%' . $term . '%');
                    });
                }
            })
            ->where('activ', 1)
            ->where('id', '!=', $currentUserId)
            ->where('id', '!=', 1)
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        return view('tech.impersonare.index', [
            'users' => $users,
            'search' => $search,
            'isImpersonating' => $request->session()->has('impersonator_id'),
        ]);
    }

    public function start(Request $request, User $user): RedirectResponse
    {
        if ($request->session()->has('impersonator_id')) {
            return back()->with('warning', 'Exista deja o sesiune de impersonare activa.');
        }

        if ((int) $user->id === (int) Auth::id()) {
            return back()->with('error', 'Nu te poti impersona pe tine.');
        }

        if ((int) $user->id === 1) {
            return back()->with('error', 'Utilizatorul #1 nu poate fi impersonat.');
        }

        if ((int) $user->activ !== 1) {
            return back()->with('error', 'Poti impersona doar utilizatori activi.');
        }

        $request->session()->put('impersonator_id', (int) Auth::id());
        Auth::login($user);

        return redirect('/acasa')->with(
            'status',
            'Ai intrat ca utilizatorul "' . $user->name . '".'
        );
    }

    public function stop(Request $request): RedirectResponse
    {
        $impersonatorId = (int) $request->session()->get('impersonator_id');

        if (!$impersonatorId) {
            return back()->with('warning', 'Nu exista nicio sesiune de impersonare activa.');
        }

        $impersonator = User::query()->find($impersonatorId);

        if (!$impersonator) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->with('error', 'Utilizatorul original nu mai exista.');
        }

        $request->session()->forget('impersonator_id');
        Auth::login($impersonator);

        $redirectUrl = $impersonator->hasRole('tech.impersonare')
            ? route('tech.impersonare.index')
            : '/acasa';

        return redirect($redirectUrl)->with('status', 'Ai iesit din sesiunea de impersonare.');
    }
}
