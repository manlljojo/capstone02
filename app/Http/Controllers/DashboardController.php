<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Room;
use App\Models\Asset;
use App\Models\Bhp;
use App\Models\ProcurementDraft;
use App\Models\ProcurementItem;
use App\Models\MaintenanceLog;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->isAdmin()) {
            $data['total_users'] = User::count();
            $data['total_rooms'] = Room::count();
            $data['recent_users'] = User::orderBy('created_at', 'desc')->take(5)->get();
            $data['recent_rooms'] = Room::orderBy('created_at', 'desc')->take(5)->get();
        } 
        elseif ($user->isKalab()) {
            $data['total_drafts'] = ProcurementDraft::where('created_by', $user->id)->count();
            $data['pending_drafts'] = ProcurementDraft::where('created_by', $user->id)->where('status', 'pending_review')->count();
            $data['finalized_drafts'] = ProcurementDraft::where('created_by', $user->id)->where('status', 'finalized')->count();
            $data['recent_drafts'] = ProcurementDraft::where('created_by', $user->id)
                ->orderBy('updated_at', 'desc')->take(5)->get();
        } 
        elseif ($user->isKaprodi()) {
            $data['pending_reviews'] = ProcurementDraft::where('status', 'pending_review')->count();
            $data['finalized_drafts'] = ProcurementDraft::where('status', 'finalized')->count();
            $data['recent_reviews'] = ProcurementDraft::where('status', 'pending_review')
                ->orderBy('created_at', 'desc')->take(5)->get();
        } 
        elseif ($user->isStafAdmin()) {
            // Finalized drafts
            $data['finalized_drafts_count'] = ProcurementDraft::where('status', 'finalized')->count();
            // Assets that don't have code or room_id allocated
            $data['unlabeled_assets'] = Asset::whereNull('code')->orWhereNull('room_id')->count();
            $data['total_assets'] = Asset::count();
            $data['recent_assets'] = Asset::orderBy('created_at', 'desc')->take(5)->get();
        } 
        elseif ($user->isStafLab()) {
            $data['total_assets'] = Asset::count();
            $data['broken_assets'] = Asset::where('status', 'rusak')->count();
            $data['maintenance_assets'] = Asset::where('status', 'maintenance')->count();
            $data['low_stock_bhp'] = Bhp::where('stock', '<', 5)->count();
            $data['recent_maintenances'] = MaintenanceLog::with(['asset', 'creator'])
                ->orderBy('maintenance_date', 'desc')->take(5)->get();
        }

        return view('dashboard', compact('data'));
    }
}
