<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RefSubunitkerja;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {

        // ambil semua data sub unit kerja
        $subunitkerjas = RefSubunitkerja::all();

        // kirim ke inertia register.vue
        return Inertia::render('Auth/Register', [
            'subunitkerjas' => $subunitkerjas
        ]);

        /**$subunitkerjas = RefSubunitkerja::select('id', 'sub_unitkerja as name')->get();
        
        return Inertia::render('Auth/Register', [
            'subunitkerjas' => $subunitkerjas,
        ]);*/
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nip' => 'required|string|max:16',
            'nama' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'subunitkerja_id' => 'nullable|exists:ref_subunitkerjas,id',
        ]);

        // Jika role = admin, cek apakah subunitkerja ini sudah ada admin
        if ($request->role === 'admin' && ! $request->subunitkerja_id) {
            throw ValidationException::withMessages(['subunitkerja_id' => 'Pilih Sub Unit Kerja OPD untuk akun admin']);
        }

        if ($request->role === 'admin') {
            $exists = User::where('subunitkerja_id', $request->subunitkerja_id)
                      ->where('role', 'admin')
                      ->exists();

            if ($exists) {
                throw ValidationException::withMessages(['subunitkerja_id' => 'Sub Unit Kerja OPD ini sudah memiliki admin']);
            }
        }

        $user = User::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'subunitkerja_id' => $request->subunitkerja_id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
