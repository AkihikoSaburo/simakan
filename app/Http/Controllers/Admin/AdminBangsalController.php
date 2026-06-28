<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bangsal;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Patient;

class AdminBangsalController extends Controller
{
    /**
     * Display a listing of bangsal.
     */
    public function index()
    {
        $bangsals = Bangsal::orderBy('nama_bangsal', 'asc')->get();
        return view('admin.bangsals.index', compact('bangsals'));
    }

    /**
     * Show the form for creating a new bangsal.
     */
    public function create()
    {
        return view('admin.bangsals.create');
    }

    /**
     * Store a newly created bangsal in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_bangsal' => ['required', 'string', 'max:255', 'unique:bangsals,nama_bangsal'],
        ], [
            'nama_bangsal.required' => 'Nama bangsal wajib diisi.',
            'nama_bangsal.unique' => 'Nama bangsal tersebut sudah ada.',
        ]);

        Bangsal::create([
            'nama_bangsal' => $request->nama_bangsal,
        ]);

        return redirect()
            ->route('admin.bangsals.index')
            ->with('success', 'Bangsal baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified bangsal.
     */
    public function edit(Bangsal $bangsal)
    {
        return view('admin.bangsals.edit', compact('bangsal'));
    }

    /**
     * Update the specified bangsal in storage.
     */
    public function update(Request $request, Bangsal $bangsal)
    {
        $request->validate([
            'nama_bangsal' => ['required', 'string', 'max:255', 'unique:bangsals,nama_bangsal,' . $bangsal->id],
        ], [
            'nama_bangsal.required' => 'Nama bangsal wajib diisi.',
            'nama_bangsal.unique' => 'Nama bangsal tersebut sudah ada.',
        ]);

        $bangsal->update([
            'nama_bangsal' => $request->nama_bangsal,
        ]);

        return redirect()
            ->route('admin.bangsals.index')
            ->with('success', 'Nama bangsal berhasil diperbarui.');
    }

    /**
     * Remove the specified bangsal from storage.
     */
    public function destroy(Bangsal $bangsal)
    {
        // 1. Reset relasi user: Staf yang tadinya di bangsal ini diubah menjadi null (Belum Ditentukan)
        $bangsal->users()->update(['bangsal_id' => null]);

        // 2. Lakukan Soft Delete (Otomatis masuk ke gudang arsip karena ada trait SoftDeletes)
        $bangsal->delete();

        return redirect()
            ->route('admin.bangsals.index')
            ->with('success', 'Bangsal ' . $bangsal->nama_bangsal . ' berhasil diarsipkan. Akses pengguna terikat telah di-reset.');
    }

    // Method untuk menampilkan semua CARD bangsal yang diarsipkan
    public function arsipIndex()
    {
        // Mengambil bangsal yang di-soft-delete beserta hitungan relasi historisnya
        $bangsalsDiarsip = Bangsal::onlyTrashed()
            ->withCount(['orders', 'patients'])
            ->latest('deleted_at')
            ->get();

        return view('admin.bangsals.arsip', compact('bangsalsDiarsip'));
    }

    // Method untuk melihat detail isi history pesanan dari bangsal yang diarsipkan
    public function arsipShow($id)
    {
        $bangsal = Bangsal::onlyTrashed()->findOrFail($id);

        // Tampung kueri dasar pesanan dengan eager loading
        $orders = Order::where('bangsal_id', $id)
            ->with(['creator', 'orderDetails'])
            ->latest();

        // JIKA FORM FILTER TANGGAL DIKLIK, SARING DATA SESUAI REQUEST
        if (request()->filled('date')) {
            $orders->whereDate('tanggal_pesanan', request('date'));
        }

        // Eksekusi pagination setelah proses kondisional filter selesai
        $orders = $orders->paginate(10);

        return view('admin.bangsals.arsip-show', compact('bangsal', 'orders'));
    }

    public function arsipOrderDetail(Order $order)
    {
        // Pastikan kueri memuat relasi data yang dibutuhkan
        $order->load(['bangsal' => function ($q) {
            $q->withTrashed(); }, 'creator', 'orderDetails.patient']);

        return view('admin.bangsals.arsip-detail', compact('order'));
    }
}
