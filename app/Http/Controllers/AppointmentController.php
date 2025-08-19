<?php

namespace App\Http\Controllers;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'PatientId' => 'required|exists:patients,id',
            'AppointmentDate' => 'required|date',
            'AppointmentTime' => 'required|date_format:H:i',
            'Reason' => 'nullable|string|max:500',
        ]);

        $conflict = Appointment::where('patient_id', $data['PatientId'])
            ->where('appointment_date', $data['AppointmentDate'])
            ->where('appointment_time', $data['AppointmentTime'])
            ->first();

        if ($conflict) {
            return response()->json(['message' => 'Patient already has an appointment at this time'], 409);
        }

        $appointment = Appointment::create([
            'patient_id' => $data['PatientId'],
            'appointment_date' => $data['AppointmentDate'],
            'appointment_time' => $data['AppointmentTime'],
            'reason' => $data['Reason'] ?? null,
        ]);

        return response()->json([
            'AppointmentId' => $appointment->id,
            'PatientId' => $appointment->patient_id,
            'AppointmentDate' => $appointment->appointment_date,
            'AppointmentTime' => $appointment->appointment_time,
            'Reason' => $appointment->reason,
            'Message' => 'Appointment created successfully'
        ], 201);
    }

    public function index()
    {
        return Appointment::all();
    }

    public function show($id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }
        return $appointment;
    }
}

