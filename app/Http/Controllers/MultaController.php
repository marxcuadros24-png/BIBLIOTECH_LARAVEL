<?php

namespace App\Http\Controllers;

use App\Models\Multa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MultaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $estado = $request->input('estado');

        $query = Multa::with(['prestamo.libro', 'usuario']);

        if ($user->esEstudiante()) {
            $query->where('id_usuario', $user->id);
        }

        if (!empty($estado)) {
            $query->where('estado', $estado);
        }

        $multas = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $totales = [
            'pendiente' => Multa::when($user->esEstudiante(), fn($q) => $q->where('id_usuario', $user->id))
                ->where('estado', 'pendiente')->sum('monto'),
            'pagada' => Multa::when($user->esEstudiante(), fn($q) => $q->where('id_usuario', $user->id))
                ->where('estado', 'pagada')->sum('monto'),
            'condonada' => Multa::when($user->esEstudiante(), fn($q) => $q->where('id_usuario', $user->id))
                ->where('estado', 'condonada')->sum('monto'),
        ];

        return view('multas.index', compact('multas', 'estado', 'totales'));
    }

    public function pagar($id)
    {
        $multa = Multa::findOrFail($id);

        if ($multa->esta_cerrada) {
            return back()->with('error', "Esta multa ya se encuentra cerrada con estado '{$multa->estado}'.");
        }

        $multa->update([
            'estado' => 'pagada',
            'fecha_pago' => Carbon::now(),
        ]);

        $simbolo = config('biblioteca.moneda', 'S/');
        return back()->with('exito', "Multa #{$multa->id} pagada correctamente ({$simbolo} {$multa->monto}).");
    }

    public function condonar($id)
    {
        $multa = Multa::findOrFail($id);

        if ($multa->esta_cerrada) {
            return back()->with('error', "Esta multa ya se encuentra cerrada con estado '{$multa->estado}'.");
        }

        $multa->update([
            'estado' => 'condonada',
        ]);

        return back()->with('exito', "Multa #{$multa->id} condonada exitosamente.");
    }
}
