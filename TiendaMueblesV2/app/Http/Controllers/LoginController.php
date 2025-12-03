<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function show()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:4',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if ($usuario) {
            if ($usuario->bloqueado_hasta && now()->lessThan($usuario->bloqueado_hasta)) {
                $segundos_restantes = now()->diffInSeconds($usuario->bloqueado_hasta);
                return back()->withErrors([
                    'errorBloqueo' => "Tu cuenta está bloqueada temporalmente. Inténtalo más tarde"
                ])->with('intentos', $usuario->intentos_fallidos);
            }

            if ($usuario->bloqueado_hasta && now()->greaterThanOrEqualTo($usuario->bloqueado_hasta)) {
                $usuario->intentos_fallidos = 0;
                $usuario->bloqueado_hasta = null;
                $usuario->save();
            }

            if (! \Hash::check($request->password, $usuario->password)) {
                $usuario->intentos_fallidos += 1;

                if ($usuario->intentos_fallidos >= 3) {
                    $usuario->bloqueado_hasta = now()->addMinutes(5);
                }

                $usuario->save();

                $intentos_restantes = max(3 - $usuario->intentos_fallidos, 0);

                return back()->withErrors([
                    'errorCredenciales' => "Credenciales incorrectas. Te quedan $intentos_restantes intento(s)."
                ])->with('intentos', $usuario->intentos_fallidos);
            }

            $usuario->intentos_fallidos = 0;
            $usuario->bloqueado_hasta = null;
            $usuario->save();

            $sesionId = Str::uuid()->toString();
            Session::put('usuario_id', $usuario->id);
            Session::put('email', $usuario->email);
            Session::put('sesionId', $sesionId);
            Session::put('rol', $usuario->rol->nombre);
            Session::put('login_at', now()->toDateTimeString());

            if ($request->has('recuerdame')) {
                Auth::login($usuario, true);
            } else {
                Auth::login($usuario, false);
            }

            return redirect()->route('principal');
        }

        return back()->withErrors([
            'errorCredenciales' => "Credenciales incorrectas."
        ])->with('intentos', 0);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('principal')->with('success', 'Sesión cerrada.');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email'     => 'required|email|unique:usuarios,email',
            'password'  => 'required|min:4|confirmed',
        ]);

        $usuario = Usuario::create([
            'nombre'         => $request->nombre,
            'apellidos'      => $request->apellidos,
            'email'          => $request->email,
            'password'       => \Hash::make($request->password),
            'rol_id'         => 3,
        ]);

        return redirect()->route('login');
    }
}
