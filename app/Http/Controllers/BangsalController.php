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

        $todayOrders = $bangsal->orders()
            ->whereDate('tanggal_pesanan', today())
            ->with('orderDetails')
            ->get();

        return view('bangsal.dashboard', compact('orders', 'todayOrders'));
    }

    public function create()
    {
        return view('bangsal.form-input');
    }

    public function store(Request $request)
    {
        // 1. SESUAIKAN VALIDASI: Menyesuaikan dengan struktur data array 'pasiens' dari form Alpine
        $request->validate([
            'pasiens' => 'required|array|min:1',
            'pasiens.*.nama_pasien' => 'required|string',
            'pasiens.*.no_rm' => 'required|string',
            'pasiens.*.kamar_kelas' => 'required|string',
            'pasiens.*.bentuk_makanan' => 'nullable|array',
            'pasiens.*.diet' => 'nullable|string',
            'pasiens.*.keterangan' => 'nullable|string',
        ]);

        $bangsalId = auth()->user()->bangsal_id;

        DB::transaction(function () use ($request, $bangsalId) {
            $order = Order::create([
                'bangsal_id' => $bangsalId,
                'created_by' => auth()->id(),
                'tanggal_pesanan' => today(),
            ]);

            // 2. SESUAIKAN LOOP: Iterasi langsung dari array objek pasiens
            foreach ($request->pasiens as $dataPasien) {
                $nama = $dataPasien['nama_pasien'];
                $noRm = $dataPasien['no_rm'];
                $kamarKelas = $dataPasien['kamar_kelas'];

                // Find or create patient
                $patient = Patient::firstOrCreate(
                    ['no_rm' => $noRm],
                    [
                        'bangsal_id' => $bangsalId,
                        'nama' => $nama,
                        'kamar' => $kamarKelas,
                        'tanggal_lahir' => '2000-01-01', // Fallback
                    ]
                );

                // Update bangsal and kamar if patient already exists but moved
                if ($patient->bangsal_id !== $bangsalId || $patient->kamar !== $kamarKelas || $patient->nama !== $nama) {
                    $patient->update([
                        'bangsal_id' => $bangsalId,
                        'kamar' => $kamarKelas,
                        'nama' => $nama,
                    ]);
                }

                $bentukMakanan = $dataPasien['bentuk_makanan'] ?? [];

                OrderDetail::create([
                    'order_id' => $order->id,
                    'patient_id' => $patient->id,
                    'nasi' => in_array('Nasi', $bentukMakanan),
                    'bubur' => in_array('Bubur', $bentukMakanan),
                    'makanan_cair' => in_array('Msk. Cair / Susu', $bentukMakanan),
                    'bs' => in_array('Bubur Saring', $bentukMakanan),
                    'sonde' => in_array('Sonde', $bentukMakanan),
                    'diet_pasien' => $dataPasien['diet'] ?? null,
                    'keterangan' => $dataPasien['keterangan'] ?? null,
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
    public function cariPasien(Request $request)
    {
        $search = $request->query('nama');

        $bangsalId = auth()->user()->bangsal_id;

        if (!$search || strlen($search) < 3) {
            return response()->json([]);
        }

        $pasiens = Patient::where('bangsal_id', $bangsalId)
            ->where('nama', 'LIKE', '%' . $search . '%')
            ->select('no_rm', 'nama', 'kamar')
            ->take(5)
            ->get();

        return response()->json($pasiens);
    }
}