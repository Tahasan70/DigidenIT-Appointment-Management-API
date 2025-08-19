<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
namespace App\Http\Controllers;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function store(Request $request)
    {
        $patient = Patient::create($request->validate([
            'name' => 'required|string|max:200',
            'contact_info' => 'nullable|string|max:500',
        ]));

        return response()->json($patient, 201);
    }

    public function index()
    {
        return Patient::all();
    }

    public function show($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }
        return $patient;
    }
}
