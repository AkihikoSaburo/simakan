<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

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