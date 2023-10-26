<?php

namespace App\Http\Controllers\Cms\Qrgad;

use App\Http\Controllers\Controller;
use App\Models\Table\Qrgad\TbInventory;
use App\Models\Table\Qrgad\TbKonsumable;
use App\Models\View\Qrgad\VwTabelInventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function __construct()
    {
        // hak akses : admin dan super gad

        // $this->middleware(function ($request, $next) {
        //     if($this->permissionMenu('aplikasi-management')) {
        //         return redirect("/")->with("error_msg", "Akses ditolak");
        //     }
        //     return $next($request);
        // });

        $this->middleware(function ($request, $next) {
            
            $level = Auth::user()->role_id;
            if($level != "LV00000001" && $level != "LV00000002" ) {
                return redirect("/dashboard")->with("data", [
                    "alert" => "danger-notallowed-Anda tidak memiliki akses"
                ]);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if($this->permissionActionMenu('aplikasi-management')->r==1){
            // $tabelinventory = VwTabelInventory::all();
            $breadcrumb = [
                [
                    'nama' => "Table Inventory",
                    'url' => "/inventory"
                ],
            ];

            $data = array(
                // "actionmenu" => $this->permissionActionMenu('aplikasi-management')
            );
        
            return view('Qrgad/inventory/index', [
                "breadcrumbs" => $breadcrumb
            ])->with('data', $data);

        // } else {
        //     return redirect("/")->with("error_msg", "Akses ditolak");
        // }
    }

    public function read($status)
    {
        // if($this->permissionActionMenu('aplikasi-management')->r==1){

            switch($status){
                //stok available
                case '1' : 
                    $tabelinventory = VwTabelInventory::where('stock', '>', DB::raw('minimal_stock'))->get();
                    break;
                case '2' : 
                    $tabelinventory = VwTabelInventory::where('stock', '<=', DB::raw('minimal_stock'))->get();
                    break;
                default : 
                    $tabelinventory = VwTabelInventory::all();
                    break;
            }
        
            return view('Qrgad/inventory/read', [
                "tabelinventory" => $tabelinventory
            ]);

        // } else {
        //     return redirect("/")->with("error_msg", "Akses ditolak");
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if($this->permissionActionMenu('aplikasi-management')->c==1){
            $breadcrumb = [
                [
                    'nama' => "Table Inventory",
                    'url' => "/inventory"
                ],
                [
                    'nama' => "Tambah",
                    'url' => "/inventory/create"
                ],
            ];

            $data = array(
                // "actionmenu" => $this->permissionActionMenu('aplikasi-management')
            );
       
            return view('Qrgad/inventory/create', [
                "breadcrumbs" => $breadcrumb
            ])->with('data', $data);

        // } else {
        //     return redirect("/")->with("error_msg", "Akses ditolak");
        // }
    }

    public function tambah($id)
    {
        // if($this->permissionActionMenu('aplikasi-management')->c==1){
            
            $konsumable = TbKonsumable::findOrFail($id);
            $breadcrumb = [
                [
                    'nama' => "Table Inventory",
                    'url' => "/inventory"
                ],
                [
                    'nama' => "Tambah",
                    'url' => "/inventory-tambah/".$id
                ],
            ];

            $data = array(
                // "actionmenu" => $this->permissionActionMenu('aplikasi-management')
            );
            
            return view('Qrgad/inventory/create', [
                "breadcrumbs" => $breadcrumb,
                "k" => $konsumable,
                "konsumable" => $konsumable->nama,
                "id" => $id
            ])->with('data', $data);


        // } else {
        //     return redirect("/")->with("error_msg", "Akses ditolak");
        // }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if($this->permissionActionMenu('aplikasi-management')->c==1){
            $kode = TbInventory::idOtomatis();
            $validated = $request->validate([
                "id" => "",
                "konsumable" => "",
                "jumlah_stock" =>"required",
                "nama_toko" => "required",
                "harga_item" => "required"
            ]);

            $validated['email'] = Auth::user()->email;
            $validated['id'] = $kode;

            $create = TbInventory::create([
                "id" => $kode,
                "konsumable" => $validated['konsumable'],
                "jumlah_stock" =>$validated['jumlah_stock'],
                "nama_toko" => $validated['nama_toko'],
                "harga_item" => $validated['harga_item']
            ]);

            $alert = '';

            if($create){
                $alert = 'success-add-inventory';
            } else {
                $alert = 'danger-add-inventory';
            }
    
            return redirect('/inventory')->with('alert', $alert);

        // } else {
        //     return redirect("/")->with("error_msg", "Akses ditolak");
        // }
    }

    public function report()
    {
        // if($this->permissionActionMenu('aplikasi-management')->r==1){
            $breadcrumb = [
                [
                    'nama' => "Report Inventory",
                    'url' => "/inventory-report"
                ],
            ];

            if (request()->start_date != "" || request()->end_date != ""){
                if(request()->start_date < request()->end_date){
                    $start_date = Carbon::parse(request()->start_date)->toDateTimeString();
                    $end_date = Carbon::parse(request()->end_date)->toDateTimeString();
                    $tabelinventory = VwTabelInventory::whereBetween('last_entry',[$start_date,$end_date])->get();
                } else {
                    $alert = 'danger-tanggalgagal- ';
                    
                    return redirect('/report-inventory')->with('alert', $alert);
                }
            } else {
                $tabelinventory = VwTabelInventory::all();
            }
        
            $data = array(
                // "actionmenu" => $this->permissionActionMenu('aplikasi-management')
            );

            return view('Qrgad/inventory/report', [
                "tabelinventory" => $tabelinventory,
                "breadcrumbs" => $breadcrumb
            ])->with('data', $data);

        // } else {
        //     return redirect("/")->with("error_msg", "Akses ditolak");
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
