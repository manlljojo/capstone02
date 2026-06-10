<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bhp;
use App\Models\Asset;
use App\Models\MaintenanceLog;
use App\Models\MaintenanceBhpUsage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StafLabController extends Controller
{
    // ==========================================
    // BHP STOCK MANAGEMENT
    // ==========================================

    public function indexBhp()
    {
        $bhps = Bhp::orderBy('name')->get();
        return view('staf_lab.bhp.index', compact('bhps'));
    }

    public function storeBhp(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:bhps'],
            'stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
        ]);

        Bhp::create($request->only('name', 'stock', 'unit'));

        return redirect()->route('staf_lab.bhp.index')->with('success', 'Barang Habis Pakai (BHP) berhasil ditambahkan!');
    }

    public function updateBhp(Request $request, Bhp $bhp)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('bhps')->ignore($bhp->id)],
            'stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
        ]);

        $bhp->update($request->only('name', 'stock', 'unit'));

        return redirect()->route('staf_lab.bhp.index')->with('success', 'Data stok BHP berhasil diperbarui!');
    }

    public function destroyBhp(Bhp $bhp)
    {
        $bhp->delete();
        return redirect()->route('staf_lab.bhp.index')->with('success', 'BHP berhasil dihapus!');
    }

    // ==========================================
    // ASSET MAINTENANCE LOGS
    // ==========================================

    public function indexMaintenance()
    {
        $logs = MaintenanceLog::with(['asset', 'creator', 'bhpUsages.bhp'])
            ->orderBy('maintenance_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        return view('staf_lab.maintenance.index', compact('logs'));
    }

    public function createMaintenance()
    {
        // Only load assets that are not archived
        $assets = Asset::where('status', '!=', 'diarsipkan')->orderBy('name')->get();
        $bhps = Bhp::where('stock', '>', 0)->orderBy('name')->get();
        
        return view('staf_lab.maintenance.create', compact('assets', 'bhps'));
    }

    public function storeMaintenance(Request $request)
    {
        $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'maintenance_date' => ['required', 'date'],
            'description' => ['required', 'string'],
            'status_after' => ['required', Rule::in(['baik', 'rusak', 'maintenance', 'diarsipkan'])],
            'bhp_usages' => ['nullable', 'array'],
            'bhp_usages.*.bhp_id' => ['nullable', 'exists:bhps,id'],
            'bhp_usages.*.quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $asset = Asset::findOrFail($request->asset_id);
        $status_before = $asset->status;

        // Validate BHP stock availability first
        if ($request->filled('bhp_usages')) {
            foreach ($request->bhp_usages as $usage) {
                if (isset($usage['bhp_id']) && isset($usage['quantity'])) {
                    $bhp = Bhp::findOrFail($usage['bhp_id']);
                    if ($bhp->stock < $usage['quantity']) {
                        return back()->withInput()->with('error', 'Stok untuk BHP "' . $bhp->name . '" tidak mencukupi (Stok: ' . $bhp->stock . ').');
                    }
                }
            }
        }

        // 1. Create Maintenance Log
        $log = MaintenanceLog::create([
            'asset_id' => $asset->id,
            'maintenance_date' => $request->maintenance_date,
            'description' => $request->description,
            'status_before' => $status_before,
            'status_after' => $request->status_after,
            'created_by' => Auth::id(),
        ]);

        // 2. Process BHP usage and deduct stock
        if ($request->filled('bhp_usages')) {
            foreach ($request->bhp_usages as $usage) {
                if (isset($usage['bhp_id']) && isset($usage['quantity'])) {
                    $bhp = Bhp::findOrFail($usage['bhp_id']);
                    
                    // Deduct stock
                    $bhp->decrement('stock', $usage['quantity']);

                    // Create usage record
                    MaintenanceBhpUsage::create([
                        'maintenance_log_id' => $log->id,
                        'bhp_id' => $bhp->id,
                        'quantity_used' => $usage['quantity'],
                    ]);
                }
            }
        }

        // 3. Update Asset status
        $asset->update(['status' => $request->status_after]);

        return redirect()->route('staf_lab.maintenance.index')->with('success', 'Log pemeliharaan berhasil dicatat! Status aset dan stok BHP telah diperbarui.');
    }
}
