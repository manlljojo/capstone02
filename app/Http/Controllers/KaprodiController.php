<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcurementDraft;
use App\Models\ProcurementItem;

class KaprodiController extends Controller
{
    public function index()
    {
        $pending_drafts = ProcurementDraft::where('status', 'pending_review')
            ->orderBy('year', 'desc')
            ->get();
            
        $finalized_drafts = ProcurementDraft::where('status', 'finalized')
            ->orderBy('year', 'desc')
            ->get();

        return view('kaprodi.review.index', compact('pending_drafts', 'finalized_drafts'));
    }

    public function show(ProcurementDraft $draft)
    {
        if (!in_array($draft->status, ['pending_review', 'finalized'])) {
            abort(403, 'Akses ditolak.');
        }

        $items = $draft->items()->with('replacedAsset')->get();

        return view('kaprodi.review.show', compact('draft', 'items'));
    }

    // Approve an item
    public function approveItem(ProcurementDraft $draft, ProcurementItem $item)
    {
        if ($draft->status !== 'pending_review' || $item->procurement_draft_id !== $draft->id) {
            abort(403, 'Akses ditolak.');
        }

        $item->update(['status' => 'approved']);

        return back()->with('success', 'Barang "' . $item->name . '" disetujui untuk pengadaan.');
    }

    // Reject an item
    public function rejectItem(ProcurementDraft $draft, ProcurementItem $item)
    {
        if ($draft->status !== 'pending_review' || $item->procurement_draft_id !== $draft->id) {
            abort(403, 'Akses ditolak.');
        }

        $item->update(['status' => 'rejected']);

        return back()->with('success', 'Barang "' . $item->name . '" ditolak.');
    }

    // Finalize/lock draft
    public function finalize(ProcurementDraft $draft)
    {
        if ($draft->status !== 'pending_review') {
            return back()->with('error', 'Hanya pengajuan status "Pending Review" yang dapat difinalisasi.');
        }

        // Count pending items
        $pendingCount = $draft->items()->where('status', 'pending')->count();
        if ($pendingCount > 0) {
            return back()->with('error', 'Harap lakukan review (setujui/tolak) terhadap semua barang sebelum memfinalisasi.');
        }

        $draft->update(['status' => 'finalized']);

        return redirect()->route('kaprodi.review.index')->with('success', 'Draf pengadaan tahun ' . $draft->year . ' berhasil difinalisasi dan dikunci!');
    }
}
