<?php

namespace App\Http\Controllers\Cms\CompletenessComponent;

use App\Exports\CompletenessComponent\InventoryCCR;
use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\Comments;
use App\Models\Table\CompletenessComponent\Inventory;
use App\Models\Table\CompletenessComponent\LogHistory;
use App\Models\Table\CompletenessComponent\MaterialTemporary;
use App\Models\View\CompletenessComponent\VwComments;
use App\Models\View\PoTracking\VwDataPOCCR;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;


class InventoryManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('inventory-component') == 0) {
                return redirect()->back()->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }

    public function InventoryManagementInit(Request $request)
    {
        try{
            $date   = Carbon::now();
            if ($this->PermissionActionMenu('inventory-component')->r == 1) {
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->id,
                    'menu'  => 'Inventory Management',
                    'date'  => $date->toDateString()
                ],[
                    'time'  => $date->toTimeString()
                ]);

                if ($request->ajax()) {
                    $inventory = Inventory::leftJoin('satria_potracking.vw_sum_data_po_ccr', 'inventory.material_number', '=', 'satria_potracking.vw_sum_data_po_ccr.material')
                        ->distinct()
                        ->where('storage_location', '<', 2000)
                        ->orderBy('material_number', 'ASC')
                        ->orderBy('stock', 'DESC')
                        ->get();
                    return DataTables::of($inventory)->toJson(); //merubah response dalam bentuk Json
                }

                $inventory = Inventory::leftJoin('satria_potracking.vw_sum_data_po_ccr', 'inventory.material_number', '=', 'satria_potracking.vw_sum_data_po_ccr.material')
                    ->distinct()
                    ->orderBy('material_number', 'ASC')
                    ->orderBy('stock', 'DESC')
                    ->get();
                $data = array(
                    'title'         => 'Inventory Management',
                    'item'          => $inventory,
                    'actionmenu'    => $this->PermissionActionMenu('inventory-component')
                );
                return view('completeness-component/inventory_management')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');

        }
    }

    
    public function MaterialView($matnr)
    {
        try{
            if ($this->PermissionActionMenu('inventory-component')->r == 1) {
                // Info Material sesuai MATNR
                $detail      = Inventory::select('material_number', 'material_description', 'base_unit')->where('material_number', $matnr)->first();
                if ($detail == null) {
                    // return redirect('completeness-component')->with('error', 'Data Material Not Found!');
                    return redirect()->back()->with('error', 'Storage Location Not Found in Inventory')->with('title', 'SLoc Not Found!');
                } else {
                    // Menampilkan semua komentar berdasarkan MATNR
                    $vw_comment       = VwComments::select('comment', 'user_by', 'created_at', 'nama_pengirim')->distinct()->where('MATNR', $matnr)->where('user_to', Auth::user()->id)->get();
                    // Menampilkan Material di semua lokasi untuk tabel inventory
                    $material         = Inventory::select('plant', 'storage_location', 'in_qc', 'stock')->where('material_number', $matnr)->where('storage_location', '<', 2000)->get();
                    // API CCR
                    $sum_potracking   = VwDataPOCCR::where('Material', $matnr)->sum('OpenQuantity');
                    $po_tracking      = VwDataPOCCR::select('Number', 'ItemNumber', 'Quantity', 'OpenQuantity', 'ConfirmedDate', 'Date', 'ReleaseDate', 'PurchaseOrderCreator', 'VendorCode', 'Name', 'SecurityDate')->where('Material', $matnr)->orderBy('ConfirmedDate', 'ASC')->orderBy('Number', 'ASC')->orderBy('ItemNumber', 'ASC')->get();
                    // Ubah Kondisi is_read jadi 1
                    $comments    = VwComments::where('MATNR', $matnr)->get();
                    foreach ($comments as $comment) {
                        if ($comment->user_to == Auth::user()->id && $comment->is_read == NULL) {
                            Comments::where('MATNR', $matnr)->where('user_to', Auth::user()->id)->where('is_read', NULL)->update(['is_read' => 1]);
                        }
                    }
                    $data       = array(
                        'sum_potracking'        => $sum_potracking,
                        'data_po'               => $po_tracking,
                        'apps'                  => $detail,
                        'material'              => $material,
                        'vw_comment'            => $vw_comment,
                        'title'                 => 'Material Details',
                        'actionmenu_komentar'   => $this->PermissionActionMenu('ccr-komentar')
                    );
                    return view('completeness-component/detail_material')->with('data', $data);
                }
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function InventoryDownload()
    {
        try{
            if ($this->PermissionActionMenu('inventory-component')->v == 1) {
                return Excel::download(new InventoryCCR(), 'InventoryManagement.xlsx');
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function detailStock(Request $request)
    {
        try {
            $desc   = MaterialTemporary::select('MATNR as material_number', 'MAKTX as material_desc')->where('MATNR', $request->material)->first();
            $detail = Inventory::select('plant', 'storage_location', 'stock')
                ->where('material_number', $request->material)
                ->where('storage_location', '<', '2000')
                ->orderBy('storage_location', 'ASC')
                ->get();
            
            $data   = array(
                'deskripsi'     => $desc,
                'detail_stock'  => $detail
            );
            echo json_encode($data);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
