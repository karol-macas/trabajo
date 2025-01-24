<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use App\Models\Empleados;
use Illuminate\Http\Request;

class DailyController extends Controller
{
    /**
     * Show a form to create a new daily scrum entry.
     */
    public function create()
    {
        $daily = Daily ::all();
        $empleados = Empleados::all(); // Fetch all employees for the selection
        return view('Daily.create', compact('daily','empleados'));
    }

    /**
     * Store a newly created daily scrum entry in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'ayer' => 'required|string',
            'hoy' => 'required|string',
            'dificultades' => 'nullable|string',
        ]);

        // Create the Daily entry with the current date for 'fecha'
        Daily::create([
            'empleado_id' => $request->empleado_id,
            'fecha' => now()->toDateString(), // Fecha actual
            'ayer' => $request->ayer,
            'hoy' => $request->hoy,
            'dificultades' => $request->dificultades,
        ]);


        return redirect()->route('daily.index')->with('success', 'Daily Scrum entry created successfully.');
    }

    /**
     * Display a list of daily scrum entries.
     */
    public function index()
    {
        $dailies = Daily::with('empleado')->latest()->paginate(10); // Fetch entries with employee info
        return view('Daily.index', compact('dailies'));
    }

    /**
     * Show the form to edit a daily scrum entry.
     */

    public function edit(Daily $daily)
    {
        $empleados = Empleados::all(); // Fetch all employees for the selection
        return view('Daily.edit', compact('daily', 'empleados'));
    }


    /**
     * Update the daily scrum entry in the database.
     */

    public function update(Request $request, Daily $daily)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'ayer' => 'required|string',
            'hoy' => 'required|string',
            'dificultades' => 'nullable|string',
        ]);

        $daily->update($request->all());

        return redirect()->route('Daily.index')->with('success', 'Daily Scrum entry updated successfully.');
    }

    public function show(Daily $daily)
    {
        return view('Daily.show', compact('daily'));
    }


    /**
     * Delete the daily scrum entry from the database.
     */

    public function destroy(Daily $daily)
    {
        $daily->delete(); // Elimina el registro
        return redirect()->route('daily.index')->with('success', 'Daily Scrum entry deleted successfully.');
    }
}
