<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Option;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function show(Family $family)
    {
        // Utilizamos el scope byFamily para filtrar opciones
        $options = Option::byFamily($family->id)->with('features')->get();

        // Si no hay opciones específicas para esta familia, cargar todas las opciones
        if ($options->isEmpty()) {
            $options = Option::with('features')->get();
        }

        return view('families.show', compact('family', 'options'));
    }
}
