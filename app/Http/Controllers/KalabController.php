<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcurementDraft;
use App\Models\ProcurementItem;
use App\Models\Asset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KalabController extends Controller
{
    public function index()
    {
        // View active drafts (draft & pending_review)
        $drafts = ProcurementDraft::where('created_by', Auth::id())
            ->whereIn('status', ['draft', 'pending_review'])
            ->orderBy('year', 'desc')
            ->get();
        return view('kalab.drafts.index', compact('drafts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => ['required', 'integer', 'min:2020', 'max:2100'],
        ]);

        // Check if draft for this year already exists in active status
        $exists = ProcurementDraft::where('created_by', Auth::id())
            ->where('year', $request->year)
            ->whereIn('status', ['draft', 'pending_review'])
            ->exists();

        if ($exists) {
            return redirect()->route('kalab.drafts.index')->with('error', 'Draf pengadaan untuk tahun ' . $request->year . ' sudah ada!');
        }

        ProcurementDraft::create([
            'year' => $request->year,
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('kalab.drafts.index')->with('success', 'Draf pengadaan tahun ' . $request->year . ' berhasil dibuat!');
    }

    public function show(ProcurementDraft $draft)
    {
        // Enforce ownership
        if ($draft->created_by !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $items = $draft->items()->with('replacedAsset')->get();
        
        // Active assets for replacement option
        $assets = Asset::whereIn('status', ['baik', 'rusak', 'maintenance'])->orderBy('name')->get();

        return view('kalab.drafts.show', compact('draft', 'items', 'assets'));
    }

    public function submit(ProcurementDraft $draft)
    {
        if ($draft->created_by !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        if ($draft->status !== 'draft') {
            return back()->with('error', 'Hanya draf dengan status "Draft" yang dapat diajukan!');
        }

        if ($draft->items()->count() === 0) {
            return back()->with('error', 'Draf pengadaan tidak boleh kosong. Harap tambahkan barang terlebih dahulu!');
        }

        $draft->update(['status' => 'pending_review']);

        return redirect()->route('kalab.drafts.index')->with('success', 'Draf pengadaan berhasil diajukan ke Kaprodi!');
    }

    // Add item to draft
    public function storeItem(Request $request, ProcurementDraft $draft)
    {
        if ($draft->created_by !== Auth::id() || $draft->status !== 'draft') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'type' => ['required', Rule::in(['asset', 'bhp'])],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:1'],
            'purchase_link' => ['nullable', 'url', 'max:1000'],
            'replaced_asset_id' => ['nullable', 'exists:assets,id'],
        ]);

        ProcurementItem::create([
            'procurement_draft_id' => $draft->id,
            'type' => $request->type,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'purchase_link' => $request->purchase_link,
            'replaced_asset_id' => $request->type === 'asset' ? $request->replaced_asset_id : null,
            'status' => 'pending',
            'received_quantity' => 0
        ]);

        return back()->with('success', 'Barang berhasil ditambahkan ke draf pengadaan!');
    }

    // Update item
    public function updateItem(Request $request, ProcurementDraft $draft, ProcurementItem $item)
    {
        if ($draft->created_by !== Auth::id() || $draft->status !== 'draft' || $item->procurement_draft_id !== $draft->id) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'type' => ['required', Rule::in(['asset', 'bhp'])],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:1'],
            'purchase_link' => ['nullable', 'url', 'max:1000'],
            'replaced_asset_id' => ['nullable', 'exists:assets,id'],
        ]);

        $item->update([
            'type' => $request->type,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'purchase_link' => $request->purchase_link,
            'replaced_asset_id' => $request->type === 'asset' ? $request->replaced_asset_id : null,
        ]);

        return back()->with('success', 'Data barang berhasil diubah!');
    }

    // Delete item
    public function destroyItem(ProcurementDraft $draft, ProcurementItem $item)
    {
        if ($draft->created_by !== Auth::id() || $draft->status !== 'draft' || $item->procurement_draft_id !== $draft->id) {
            abort(403, 'Akses ditolak.');
        }

        $item->delete();
        return back()->with('success', 'Barang berhasil dihapus dari draf pengadaan!');
    }

    // History (finalized drafts)
    public function history()
    {
        $drafts = ProcurementDraft::where('created_by', Auth::id())
            ->where('status', 'finalized')
            ->orderBy('year', 'desc')
            ->get();
        return view('kalab.drafts.history', compact('drafts'));
    }
}
