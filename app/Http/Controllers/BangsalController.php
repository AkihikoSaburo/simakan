<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BangsalController extends Controller
{
    public function dashboard()
    {
        $bangsal = auth()->user()->bangsal;

        $orders = $bangsal->orders()
            ->with('orderDetails')
            ->latest('tanggal_pesanan')
            ->get();

        return view('bangsal.dashboard', compact('orders'));
    }

    public function create()
    {
        return view('bangsal.form-input');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pasien' => 'required|array',
            'nama_pasien.*' => 'required|string',
            'no_rm' => 'required|array',
            'no_rm.*' => 'required|string',
            'kamar_kelas' => 'required|array',
            'kamar_kelas.*' => 'required|string',
        ]);

        $bangsalId = auth()->user()->bangsal_id;

        DB::transaction(function () use ($request, $bangsalId) {
            $order = Order::create([
                'bangsal_id' => $bangsalId,
                'created_by' => auth()->id(),
                'tanggal_pesanan' => today(),
            ]);

            foreach ($request->nama_pasien as $index => $nama) {
                // Find or create patient
                $patient = Patient::firstOrCreate(
                    ['no_rm' => $request->no_rm[$index]],
                    [
                        'bangsal_id' => $bangsalId,
                        'nama' => $nama,
                        'kamar' => $request->kamar_kelas[$index],
                        'tanggal_lahir' => '2000-01-01', // Fallback for required field missing in form
                    ]
                );

                // Update bangsal and kamar if patient already exists but moved
                if ($patient->bangsal_id !== $bangsalId || $patient->kamar !== $request->kamar_kelas[$index] || $patient->nama !== $nama) {
                    $patient->update([
                        'bangsal_id' => $bangsalId,
                        'kamar' => $request->kamar_kelas[$index],
                        'nama' => $nama,
                    ]);
                }

                $bentukMakanan = $request->bentuk_makanan[$index] ?? [];

                OrderDetail::create([
                    'order_id' => $order->id,
                    'patient_id' => $patient->id,
                    'nasi' => in_array('Nasi', $bentukMakanan),
                    'bubur' => in_array('Bubur', $bentukMakanan),
                    'makanan_cair' => in_array('Msk. Cair / Susu', $bentukMakanan),
                    'bs' => in_array('Bubur Saring', $bentukMakanan),
                    'sonde' => in_array('Sonde', $bentukMakanan),
                    'diet_pasien' => $request->diet[$index] ?? null,
                    'keterangan' => $request->keterangan[$index] ?? null,
                ]);
            }
        });

        return redirect()->route('bangsal.dashboard')->with('success', 'Permintaan makanan berhasil dikirim ke Dapur Gizi.');
    }

    public function show(Order $order)
    {
        // Pastikan hanya boleh melihat order milik bangsal sendiri
        if ($order->bangsal_id !== auth()->user()->bangsal_id) {
            abort(403);
        }

        $order->load([
            'bangsal',
            'orderDetails.patient',
        ]);

        return view('bangsal.detail', compact('order'));
    }
}