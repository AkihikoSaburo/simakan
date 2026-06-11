<?php

namespace App\Http\Controllers;

use App\Models\Order;

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
        $orders = Order::with('bangsal')
            ->latest()
            ->paginate(20);

        return view('dapur.history', compact('orders'));
    }
}