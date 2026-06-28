<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DapurController extends Controller
{
    public function dashboard()
    {
        $orders = Order::with([
            'bangsal',
            'orderDetails.patient'
        ])
            ->whereDate('tanggal_pesanan', today())
            ->latest()
            ->get();

        return view('dapur.dashboard', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'bangsal',
            'orderDetails.patient'
        ]);

        return view('dapur.detail', compact('order'));
    }

    public function history()
    {
        // Get unique dates for pagination, sorted from newest to oldest
        $paginatedDates = Order::select('tanggal_pesanan')
            ->groupBy('tanggal_pesanan')
            ->orderBy('tanggal_pesanan', 'desc')
            ->paginate(12);

        // Convert dates to Y-m-d format strings
        $dateStrings = $paginatedDates->pluck('tanggal_pesanan')->map(fn($d) => $d->format('Y-m-d'))->toArray();

        // Retrieve orders for the paginated dates
        $orders = Order::with(['bangsal', 'orderDetails.patient', 'creator'])
            ->whereIn('tanggal_pesanan', $dateStrings)
            ->latest()
            ->get();

        // Group the retrieved orders by Y-m-d date string
        $groupedOrders = $orders->groupBy(fn($order) => $order->tanggal_pesanan->format('Y-m-d'));

        return view('dapur.history', compact('paginatedDates', 'groupedOrders'));
    }

    public function exportDailyPdf($date)
    {
        $carbonDate = Carbon::parse($date);

        // Fetch all orders on this date
        $orders = Order::with(['bangsal', 'orderDetails.patient', 'creator'])
            ->whereDate('tanggal_pesanan', $carbonDate)
            ->get();

        if ($orders->isEmpty()) {
            abort(404, 'Tidak ada data pesanan pada tanggal ini.');
        }

        $pdf = Pdf::loadView('dapur.pdf', compact('orders', 'carbonDate'));

        $filename = sprintf(
            'Rekap-Makanan-RSUD-Andi-Makkasau-%s.pdf',
            $carbonDate->format('Y-m-d')
        );

        return $pdf->stream($filename);
    }

    /**
     * Export single order request as PDF for Dapur Gizi.
     */
    public function exportSingleOrderPdf(Order $order)
    {
        // 1. Load semua relasi yang dibutuhkan agar tidak terkena N+1 query issue
        $order->load([
            'bangsal',
            'orderDetails.patient',
            'creator'
        ]);

        // 2. Gunakan Carbon untuk format tanggal di dalam PDF jika dibutuhkan
        $carbonDate = $order->tanggal_pesanan;

        // 3. Arahkan ke view PDF (kamu bisa pakai view dapur.pdf yang sama, 
        // atau buat file baru dapur-single.pdf jika strukturnya berbeda)
        $pdf = Pdf::loadView('dapur.single-order-pdf', compact('order', 'carbonDate'));

        // 4. Susun nama file agar unik berdasarkan nama bangsal dan tanggalnya
        $filename = sprintf(
            'Form-Makanan-%s-%s.pdf',
            str_replace(' ', '-', $order->bangsal->nama_bangsal),
            $carbonDate->format('Y-m-d')
        );

        // 5. Stream PDF ke browser
        return $pdf->stream($filename);
    }
}