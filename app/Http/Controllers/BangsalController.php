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
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class BangsalController extends Controller
{
    /**
     * Display the ward dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();

        // JARING PENGAMAN: Jika user tidak terikat dengan bangsal aktif apa pun
        if (!$user->bangsal_id || !$user->bangsal) {
            return view('bangsal.no_bangsal');
        }

        $bangsal = $user->bangsal;

        // Ambil pesanan hari ini dari bangsal aktif ini dengan eager loading relasi untuk mencegah N+1
        $todayOrders = Order::where('bangsal_id', $bangsal->id)
            ->whereDate('tanggal_pesanan', today())
            ->with(['orderDetails.patient', 'creator'])
            ->latest()
            ->get();

        return view('bangsal.dashboard', compact('todayOrders', 'bangsal'));
    }

    public function riwayat(Request $request): View
    {
        $user = auth()->user();
        if (!$user->bangsal_id || !$user->bangsal) {
            abort(403, 'Akun Anda tidak dikaitkan dengan bangsal aktif mana pun.');
        }

        $bangsal = $user->bangsal;

        $orders = $bangsal->orders()
            ->with(['orderDetails.patient', 'creator'])
            ->when($request->filled('date'), function ($query) use ($request) {
                return $query->whereDate('tanggal_pesanan', $request->date);
            })
            ->latest('tanggal_pesanan')
            ->paginate(10)
            ->withQueryString();

        return view('bangsal.riwayat', compact('orders'));
    }

    /**
     * Show the form for creating a new food order request.
     */
    public function create(): View
    {
        $user = auth()->user();
        if (!$user->bangsal_id || !$user->bangsal) {
            abort(403, 'Akun Anda tidak dikaitkan dengan bangsal aktif mana pun.');
        }

        return view('bangsal.form-input');
    }

    /**
     * Store a newly created food order request.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        if (!$user->bangsal_id || !$user->bangsal) {
            abort(403, 'Akun Anda tidak dikaitkan dengan bangsal aktif mana pun.');
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

        $bangsalId = $user->bangsal_id;

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
        $user = auth()->user();
        
        // Proteksi IDOR: Hanya ijinkan jika user adalah pemilik bangsal yang bersangkutan atau admin/superadmin/dapur
        if ($user->role === 'bangsal' && $order->bangsal_id !== $user->bangsal_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat manifes dari bangsal lain.');
        }

        $order->load([
            'bangsal' => function ($query) {
                $query->withTrashed();
            },
            'orderDetails.patient',
            'creator',
        ]);

        return view('bangsal.detail', compact('order'));
    }

    /**
     * Export single order request as PDF for Dapur Gizi.
     */
    public function exportSingleOrderPdf($id)
    {
        $order = Order::findOrFail($id);
        $user = auth()->user();

        // Proteksi IDOR: Mencegah pengguna menebak ID pesanan milik bangsal lain
        if ($user->role === 'bangsal' && $order->bangsal_id !== $user->bangsal_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mencetak manifes dari bangsal lain.');
        }

        $order->load([
            'bangsal' => function ($query) {
                $query->withTrashed();
            },
            'creator',
            'orderDetails.patient'
        ]);

        $pdf = Pdf::loadView('bangsal.pdf', compact('order'));
        return $pdf->stream('Form-Makanan-' . $order->id . '.pdf');
    }

    /**
     * Show the form for editing an existing food order request.
     */
    public function edit(Order $order): View
    {
        $user = auth()->user();
        if ($user->role === 'bangsal' && $order->bangsal_id !== $user->bangsal_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengedit manifes dari bangsal lain.');
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
        $user = auth()->user();
        if ($user->role === 'bangsal' && $order->bangsal_id !== $user->bangsal_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk memperbarui manifes dari bangsal lain.');
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

        $bangsalId = $user->bangsal_id;

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
        $user = auth()->user();
        if (!$user->bangsal_id || !$user->bangsal) {
            return response()->json([]);
        }

        $search = $request->query('nama');
        $bangsalId = $user->bangsal_id;

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