<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgendaController extends Controller
{
    /**
     * Menampilkan semua data agenda
     */
    public function index()
    {
        $agenda = Agenda::with('surat')->get();

        return response()->json([
            'status' => 'success',
            'data' => $agenda
        ], 200);
    }

    /**
     * Menyimpan data agenda baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_agenda' => 'required|unique:agendas',
            'surat_id' => 'required|exists:surats,id',
            'tanggal_agenda' => 'required|date',
            'pengirim' => 'nullable|string',
            'penerima' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $agenda = Agenda::create([
            'nomor_agenda' => $request->nomor_agenda,
            'surat_id' => $request->surat_id,
            'tanggal_agenda' => $request->tanggal_agenda,
            'pengirim' => $request->pengirim,
            'penerima' => $request->penerima,
        ]);

        return response()->json([
            'message' => 'Agenda berhasil dibuat',
            'data' => $agenda
        ], 201);
    }

    /**
     * Menampilkan detail data agenda
     */
    public function show($id)
    {
        $agenda = Agenda::with('surat')->find($id);

        if (!$agenda) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Agenda not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $agenda
        ]);
    }

    /**
     * Mengupdate data agenda
     */
    public function update(Request $request, $id)
    {
        $agenda = Agenda::find($id);

        if (!$agenda) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Agenda not found'
            ], 404);
        }

        // Validasi fleksibel (partial update)
        $validator = Validator::make($request->all(), [
            'nomor_agenda' => 'sometimes|required|unique:agendas,nomor_agenda,'.$agenda->id,
            'surat_id' => 'sometimes|required|exists:surats,id',
            'tanggal_agenda' => 'sometimes|required|date',
            'pengirim' => 'sometimes|nullable|string',
            'penerima' => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Update hanya data yang dikirim
        $agenda->update($request->only([
            'nomor_agenda',
            'surat_id',
            'tanggal_agenda',
            'pengirim',
            'penerima',
        ]));

        return response()->json([
            'message' => 'Agenda berhasil diperbarui',
            'data' => $agenda
        ], 200);
    }

    /**
     * Menghapus data agenda
     */
    public function destroy($id)
    {
        $agenda = Agenda::find($id);

        if (!$agenda) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Agenda not found'
            ], 404);
        }

        $agenda->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Agenda berhasil dihapus'
        ]);
    }
}
