<?php

namespace App\Http\Controllers\Cms\Elsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;

use App\Models\User;
use App\Models\View\Elsa\VwInventory;
use App\Models\Table\Elsa\InventoryProblem;
use App\Models\Table\Elsa\InventoryIncome;
use App\Models\Table\Elsa\InventoryRequest;
use App\Models\Table\Elsa\MstInventory;
use App\Models\Table\Elsa\MstVendor;
use App\Models\Table\Elsa\InventoryCat;
use App\Models\Table\Elsa\MstBrand;
use App\Models\View\VwPermissionAppsMenu;
class InventoryManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('inventory-management') == 0){
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
            return $next($request);
            });
    }

    public function InventoryMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('inventory-management')->r==1){
        // $checkuser = MstInventory::where('satria_id',Auth::user()->id)->first();
        // if(!empty($checkuser)){
            
            $category = InventoryCat::where('dept',Auth::user()->dept)->where('flag',1)->orderBy('name', 'asc')->get();
            $vendor = MstVendor::where('flag',1)->orderBy('name', 'asc')->get();
            $brand = MstBrand::get();
            $inventory = VwInventory::where('dept',Auth::user()->dept)->where('flag',1)->orderBy('inventory_nama', 'asc')->get();
            $no = 1;
            foreach($inventory as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'category' => $category,
                'vendor' => $vendor,
                'brand' => $brand,
                'inventory' => $inventory,
                'actionmenu' => $this->PermissionActionMenu('inventory-management'),
            );
            return view('elsa/inventory-management/index')->with('data', $data);
        
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }      
    }
    
    public function InventoryMgmtTracking($id)
    {
        if($this->PermissionActionMenu('inventory-management')->r==1){
        // $checkuser = MstInventory::where('satria_id',Auth::user()->id)->first();
        // if(!empty($checkuser)){
            
            $inventory = VwInventory::where('dept',Auth::user()->dept)->where('flag',1)->where('inventory_id',$id)->first();
            $problem = InventoryProblem::where('dept',Auth::user()->dept)->where('inventory_id',$id)->get();
            $income = InventoryIncome::where('dept',Auth::user()->dept)->where('inventory_id',$id)->get();
            $request = InventoryRequest::where('dept',Auth::user()->dept)->where('pr_inventory_id',$id)->get();
            $lastprice = InventoryIncome::where('dept',Auth::user()->dept)->where('inventory_id',$id)->orderBy('created_at','desc')->first();
            $qtymonth = InventoryIncome::where('dept',Auth::user()->dept)->where('inventory_id',$id)->where('created_at', 'like', "%". date('Y-m') ."%")->orderBy('created_at','desc')->sum('qty');
            
            $data = array(
                'qtymonth' => $qtymonth,
                'lastprice' => $lastprice,
                'request' => $request,
                'problem' => $problem,
                'income' => $income,
                'inventory' => $inventory,
                'actionmenu' => $this->PermissionActionMenu('inventory-management'),
            );
            return view('elsa/inventory-management/tracking')->with('data', $data);
          
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }    
    }
    public function InventoryMgmtInsert(Request $request)
    {
        if ($this->PermissionActionMenu('inventory-management')->c == 1) {
            $inventory = MstInventory::where('inventory_nama', $request->inventory_nama)->where('dept', Auth::user()->dept)->first();
            // dd($request->all());
            if (empty($inventory)) {
                if ($request->has('inventory_file')) {
                    $inventory_file = $request->file('inventory_file');
                    $fileName = uniqid() . '_File_' . $inventory_file->getClientOriginalName();

                    if (Storage::disk('public')->exists('inventoryFile')) {
                        $inventory_file->move(public_path('inventoryFile'), $fileName);
                    } else {
                        Storage::disk('public')->makeDirectory('inventoryFile');
                        $inventory_file->move(public_path('inventoryFile'), $fileName);
                    }

                    $create = MstInventory::create([
                        'inventory_nama' => $request->inventory_nama,
                        'inventory_group' => $request->inventory_group,
                        'inventory_file' => $fileName,
                        'inventory_qty' => $request->inventory_qty,
                        'inventory_qty_min' => $request->inventory_qty_min,
                        'inventory_brand_id' => $request->inventory_brand_id,
                        'inventory_category_id' => $request->inventory_category_id,
                        'inventory_satuan' => $request->inventory_satuan,
                        'dept' => Auth::user()->dept,
                        'flag' => 1,
                        'created_by' => Auth::user()->id,
                    ]);
                } else {
                    $create = MstInventory::create([
                        'inventory_nama' => $request->inventory_nama,
                        'inventory_group' => $request->inventory_group,
                        'inventory_file' => $request->inventory_file,
                        'inventory_qty' => $request->inventory_qty,
                        'inventory_qty_min' => $request->inventory_qty_min,
                        'inventory_brand_id' => $request->inventory_brand_id,
                        'inventory_category_id' => $request->inventory_category_id,
                        'inventory_satuan' => $request->inventory_satuan,
                        'dept' => Auth::user()->dept,
                        'flag' => 1,
                        'created_by' => Auth::user()->id,
                    ]);
                }

                if ($create) {
                    return redirect('inventory-management')->with('suc_message', 'Data berhasil ditambahkan!');
                } else {
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            } else {
                return redirect()->back()->with('err_message', 'Name Already Exist!');
            }
        } else {
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function InventoryMgmtUpdate(Request $request)
    {
        if ($this->PermissionActionMenu('inventory-management')->u == 1) {
            // dd($request->all());
            $inventory = MstInventory::where('inventory_id', $request->inventory_id)->first();
            if (!empty($inventory)) {
                if ($request->has('inventory_file')) {
                    // dd($inventory->inventory_file);
                    $inventory_file = $request->file('inventory_file');
                    $fileName = uniqid() . '_File_' . $inventory_file->getClientOriginalName();
                    $inventory_file->move(public_path('inventoryFile'), $fileName);

                    File::delete(public_path('inventoryFile/' . $inventory->inventory_file));

                    $update = MstInventory::where('inventory_id', $request->inventory_id)
                        ->update([
                            'inventory_nama' => $request->inventory_nama,
                            'inventory_group' => $request->inventory_group,
                            'inventory_file' => $fileName,
                            'inventory_qty' => $request->inventory_qty,
                            'inventory_qty_min' => $request->inventory_qty_min,
                            'inventory_brand_id' => $request->inventory_brand_id,
                            'inventory_category_id' => $request->inventory_category_id,
                            'inventory_satuan' => $request->inventory_satuan,
                            'updated_by' => Auth::user()->id,
                        ]);
                } else {
                    $update = MstInventory::where('inventory_id', $request->inventory_id)
                        ->update([
                            'inventory_nama' => $request->inventory_nama,
                            'inventory_group' => $request->inventory_group,
                            'inventory_qty' => $request->inventory_qty,
                            'inventory_qty_min' => $request->inventory_qty_min,
                            'inventory_brand_id' => $request->inventory_brand_id,
                            'inventory_category_id' => $request->inventory_category_id,
                            'inventory_satuan' => $request->inventory_satuan,
                            'updated_by' => Auth::user()->id,
                        ]);
                }
                if ($update) {
                    return redirect('inventory-management')->with('suc_message', 'Data berhasil diupdate!');
                } else {
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        } else {
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function InventoryMgmtIncome(Request $request)
    {
        if($this->PermissionActionMenu('inventory-management')->u==1){
            $inventory = MstInventory::where('inventory_id', $request->inventory_id)->first();
            if(!empty($inventory)){
                $create = InventoryIncome::create([
                    'inventory_id'=>$request->inventory_id,
                    'note'=>$request->in_note,
                    'qty'=>$request->in_qty,
                    'price'=>$request->in_price,
                    'vendor_id'=>$request->vendor,
                    'dept'=>Auth::user()->dept,
                    'created_by' => Auth::user()->id,
                ]);
                if($create){
                    MstInventory::where('inventory_id', $request->inventory_id)->update([
                        'inventory_qty'=>$request->stock+$request->in_qty,
                        'updated_by' => Auth::user()->id,
                    ]);
                    $this->setInventoryHistory($request->inventory_id,'Income',$request->in_note,$request->in_qty);
                    return redirect()->back()->with('suc_message', 'Income Stock Inventory Berhasil!');
                }else{
                    return redirect()->back()->with('err_message', 'Income Stock Inventory Gagal!');
                }
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }       
    }
    
    public function InventoryMgmtRequest(Request $request)
    {
       
            $no = InventoryRequest::where('created_at', 'like', date('Y-m-d'). "%")->count();
            $pr_num = 'PR'.date('Ymd').str_pad($no, 4, "0", STR_PAD_LEFT); //PR20210101000
            // if(!empty($inventory)){
                $create = InventoryRequest::create([
                    'pr_number'=>$pr_num,
                    'pr_nrp'=>Auth::user()->email,
                    'pr_name'=>Auth::user()->name,
                    'pr_dept'=>Auth::user()->department,
                    'dept'=>$request->dept,
                    'pr_category'=>$request->category,
                    'pr_quantity'=>$request->qty,
                    'pr_description'=>$request->message,
                    'pr_remark'=>'',
                    'created_by' => Auth::user()->id,
                ]);
                print($create);
                if($create){
                    // MstInventory::where('inventory_id', $request->inventory_id)->update([
                    //     'inventory_qty'=>$request->stock-$request->out_qty,
                    //     'updated_by' => Auth::user()->id,
                    // ]);
                    return redirect()->back()->with('suc_message', 'Request PR Berhasil!');
                }else{
                    return redirect()->back()->with('err_message', 'Request PR Gagal!');
                }
            // } else {
            // }
    }
    public function InventoryMgmtReduce(Request $request)
    {
        if($this->PermissionActionMenu('inventory-management')->u==1){
            $inventory = MstInventory::where('inventory_id', $request->inventory_id)->first();
            if(!empty($inventory)){
                $create = InventoryProblem::create([
                    'inventory_id'=>$request->inventory_id,
                    'prob_note'=>$request->out_note,
                    'prob_type'=>$request->type,
                    'prob_qty'=>$request->out_qty,
                    'dept'=>Auth::user()->dept,
                    'created_by' => Auth::user()->id,
                ]);
                if($create){
                    MstInventory::where('inventory_id', $request->inventory_id)->update([
                        'inventory_qty'=>$request->stock-$request->out_qty,
                        'updated_by' => Auth::user()->id,
                    ]);
                    $this->setInventoryHistory($request->inventory_id,'Reduce '.$request->type,$request->out_note,$request->out_qty);
                    return redirect()->back()->with('suc_message', 'Reduce Stock Inventory Berhasil!');
                }else{
                    return redirect()->back()->with('err_message', 'Reduce Stock Inventory Gagal!');
                }
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }   
    }
    public function InventoryMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('inventory-management')->d==1){
            $inventory = MstInventory::where('inventory_id', $request->inventory_id)->first();
            if(!empty($inventory)){
                MstInventory::where('inventory_id', $request->inventory_id)->update([
                    'flag'=>0,
                    'updated_by' => Auth::user()->id,
                ]);
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }   
    }
    
    public function getInventory($id){

        $empData['data'] = MstInventory::where('inventory_id',$id)->first();
  
        return response()->json($empData);
     
    }
}
