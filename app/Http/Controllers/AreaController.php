<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::all();
        return view('admin.areas', compact('areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:areas|max:255',
        ]);

        Area::create($request->all());

        return back()->with('success', 'Área creada con éxito.');
    }
}