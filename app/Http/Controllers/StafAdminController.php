<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcurementDraft;
use App\Models\ProcurementItem;
use App\Models\ItemReceipt;
use App\Models\Asset;
use App\Models\Bhp;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StafAdminController extends Controller
{
    // ==========================================
    // ITEM RECEIPTS
    // ==========================================

    public function indexApproved()
    {
        $drafts = ProcurementDraft::where('status', 'finalized')
            ->orderBy('year', 'desc')
            ->get();
        return view('staf_admin.approved.index', compact('drafts'));
    }

    public function showApproved(ProcurementDraft $draft)
    {
        if ($draft->status !== 'finalized') {
            abort(403, 'Akses ditolak.');
        }

        // Only display items that were approved
        $items = $draft->items()->where('status', 'approved')->get();

        return view('staf_admin.approved.show', compact('draft', 'items'));
    }

    public function storeReceipt(Request $request, ProcurementDraft $draft, ProcurementItem $item)
    {
        if ($draft->status !== 'finalized' || $item->procurement_draft_id !== $draft->id) {
            abort(403, 'Akses ditolak.');
        }

        $remaining = $item->quantity - $item->received_quantity;

        $request->validate([
            'received_date' => ['required', 'date'],
            'quantity_received' => ['required', 'integer', 'min:1', 'max:' . $remaining],
        ]);

        // 1. Record receipt log
        ItemReceipt::create([
            'procurement_item_id' => $item->id,
            'received_date' => $request->received_date,
            'quantity_received' => $request->quantity_received,
            'received_by' => Auth::id(),
        ]);

        // 2. Update received quantity
        $item->increment('received_quantity', $request->quantity_received);

        // 3. Process goods based on type
        if ($item->type === 'asset') {
            // Create Asset records
            for ($i = 0; $i < $request->quantity_received; $i++) {
                Asset::create([
                    'name' => $item->name,
                    'price' => $item->price,
                    'purchase_date' => $request->received_date,
                    'status' => 'baik',
                    'room_id' => null,
                    'code' => null,
                    'barcode_photo' => null,
                ]);
            }

            // Archive replaced asset if present
            if ($item->replaced_asset_id) {
                $replaced = Asset::find($item->replaced_asset_id);
                if ($replaced && $replaced->status !== 'diarsipkan') {
                    $replaced->update(['status' => 'diarsipkan']);
                }
            }
        } 
        elseif ($item->type === 'bhp') {
            // Add or create BHP stock
            $bhp = Bhp::where('name', 'like', $item->name)->first();
            if ($bhp) {
                $bhp->increment('stock', $request->quantity_received);
            } else {
                Bhp::create([
                    'name' => $item->name,
                    'stock' => $request->quantity_received,
                    'unit' => 'pcs', // Default unit
                ]);
            }
        }

        return back()->with('success', 'Penerimaan barang berhasil dicatat! Data stok/aset telah diperbarui.');
    }

    // ==========================================
    // ASSET LABELING (INVENTORY UPDATE)
    // ==========================================

    public function indexAssets()
    {
        $assets = Asset::with('room')->orderBy('id', 'desc')->get();
        $rooms = Room::orderBy('name')->get();
        return view('staf_admin.assets.index', compact('assets', 'rooms'));
    }

    public function updateAsset(Request $request, Asset $asset)
    {
        $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'code' => ['required', 'string', 'max:50', Rule::unique('assets')->ignore($asset->id)],
            'barcode_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $asset->room_id = $request->room_id;
        $asset->code = $request->code;

        // File upload using public path to prevent windows symlink issues
        if ($request->hasFile('barcode_photo')) {
            $image = $request->file('barcode_photo');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Ensure folder exists
            if (!file_exists(public_path('barcodes'))) {
                mkdir(public_path('barcodes'), 0777, true);
            }

            $image->move(public_path('barcodes'), $filename);
            $asset->barcode_photo = 'barcodes/' . $filename;
        }

        $asset->save();

        return redirect()->route('staf_admin.assets.index')->with('success', 'Aset berhasil dilabeli dan dialokasikan ke ruangan!');
    }
}
