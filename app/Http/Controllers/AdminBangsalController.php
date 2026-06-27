<?php

namespace App\Http\Controllers;

use App\Models\Bangsal;
use Illuminate\Http\Request;

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
        $bangsal->delete();

        return redirect()
            ->route('admin.bangsals.index')
            ->with('success', 'Bangsal berhasil dihapus.');
    }
}
