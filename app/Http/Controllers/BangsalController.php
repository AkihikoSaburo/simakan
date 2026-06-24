<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class BangsalController extends Controller
{
    /**
     * Display the ward dashboard.
     */
    public function dashboard(): View
    {
        $bangsal = auth()->user()->bangsal;

        $orders = $bangsal->orders()
            ->with(['orderDetails.patient', 'creator'])
            ->latest('tanggal_pesanan')
            ->get();

        $todayOrders = $bangsal->orders()
            ->whereDate('tanggal_pesanan', today())
            ->with(['orderDetails.patient', 'creator'])
            ->get();

        return view('bangsal.dashboard', compact('orders', 'todayOrders'));
    }

    /**
     * Show the form for creating a new food order request.
     */
    public function create(): View
    {
        return view('bangsal.form-input');
    }

    /**
     * Store a newly created food order request.
     */
    public function store(Request $request): RedirectResponse
    {
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

        try {
            DB::transaction(function () use ($request, $bangsalId) {
                $order = Order::create([
                    'bangsal_id' => $bangsalId,
                    'created_by' => auth()->id(),
                    'tanggal_pesanan' => today(),
                ]);

                foreach ($request->pasiens as $dataPasien) {
                    $nama = $dataPasien['nama_pasien'];
                    $noRm = $dataPasien['no_rm'];
                    $kamarKelas = $dataPasien['kamar_kelas'];

                    $patient = Patient::firstOrCreate(
                        ['no_rm' => $noRm],
                        [
                            'bangsal_id' => $bangsalId,
                            'nama' => $nama,
                            'kamar' => $kamarKelas,
                            'tanggal_lahir' => '2000-01-01',
                        ]
                    );

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
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses permintaan makanan: ' . $e->getMessage());
        }
    }

    /**
     * Show the details of a specific food order request.
     */
    public function show(Order $order): View
    {
        if ($order->bangsal_id !== auth()->user()->bangsal_id) {
            abort(403);
        }

        $order->load([
            'bangsal',
            'orderDetails.patient',
            'creator',
        ]);

        return view('bangsal.detail', compact('order'));
    }

    /**
     * Export a specific food order request as PDF.
     */
    public function exportPdf(Order $order): Response
    {
        if ($order->bangsal_id !== auth()->user()->bangsal_id) {
            abort(403);
        }

        $order->load([
            'bangsal',
            'orderDetails.patient',
            'creator',
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('bangsal.pdf', compact('order'));

        $filename = sprintf(
            'Form-Makanan-%s-%s.pdf',
            str_replace(' ', '-', $order->bangsal->nama_bangsal),
            $order->tanggal_pesanan->format('Y-m-d')
        );

        return $pdf->stream($filename);
    }

    /**
     * Show the form for editing an existing food order request.
     */
    public function edit(Order $order): View
    {
        if ($order->bangsal_id !== auth()->user()->bangsal_id) {
            abort(403);
        }

        $order->load([
            'orderDetails.patient',
        ]);

        return view('bangsal.form-input', compact('order'));
    }

    /**
     * Update an existing food order request.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        if ($order->bangsal_id !== auth()->user()->bangsal_id) {
            abort(403);
        }

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

        try {
            DB::transaction(function () use ($request, $order, $bangsalId) {
                $order->orderDetails()->delete();

                foreach ($request->pasiens as $dataPasien) {
                    $nama = $dataPasien['nama_pasien'];
                    $noRm = $dataPasien['no_rm'];
                    $kamarKelas = $dataPasien['kamar_kelas'];

                    $patient = Patient::firstOrCreate(
                        ['no_rm' => $noRm],
                        [
                            'bangsal_id' => $bangsalId,
                            'nama' => $nama,
                            'kamar' => $kamarKelas,
                            'tanggal_lahir' => '2000-01-01',
                        ]
                    );

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

            return redirect()->route('bangsal.dashboard')->with('success', 'Permintaan makanan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui permintaan makanan: ' . $e->getMessage());
        }
    }

    /**
     * Search patients in the database.
     */
    public function cariPasien(Request $request): \Illuminate\Http\JsonResponse
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