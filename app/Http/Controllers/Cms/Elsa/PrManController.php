<?php

namespace App\Http\Controllers\Cms\Elsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\Elsa\InventoryRequest;
use App\Models\Table\Elsa\MstDept;
use App\Models\View\Elsa\VwInventory;
use App\Models\Table\Elsa\MstInventory;
use App\Models\Table\Elsa\InventoryRequestOut;
use App\Models\View\VwPermissionAppsMenu;
use Excel;
use App\Exports\PrExport;
use Exception;
class PrManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('pr-management') == 0){
                return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function PrMgmtInit(Request $request)
    {
        try{
            if($this->PermissionActionMenu('pr-management')->r==1){
            // $checkuser = InventoryRequest::where('satria_id',Auth::user()->id)->first();
            // if(!empty($checkuser)){
                
                $inventory = VwInventory::where('dept',Auth::user()->dept)->where('flag',1)->orderBy('inventory_nama', 'asc')->get();
                $pr = InventoryRequest::where('dept',Auth::user()->dept)->orderBy('created_at', 'desc')->get();
                $dept= MstDept::whereIn('id',['8927','8884','8915'])->get();
                $response = $this->getUserSF(999);
                $arr = json_decode($response,true);
                $no = 1;
                foreach($pr as $data){
                    $data->no = $no;
                    $no++;
                }
                $data = array(
                    'dept' => $dept,
                    'inventory' => $inventory,
                    'pr' => $pr,
                    'emp' => $arr['emp'],
                    'actionmenu' => $this->PermissionActionMenu('pr-management'),
                );
                return view('elsa/pr-management/index')->with('data', $data);
               
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }

    public function PrMgmtList(Request $request)
    {
        try{
            if($this->PermissionActionMenu('pr-management')->r==1){
            // $checkuser = InventoryRequest::where('satria_id',Auth::user()->id)->first();
            // if(!empty($checkuser)){
                
                $inventory = VwInventory::where('dept',Auth::user()->dept)->where('flag',1)->orderBy('inventory_nama', 'asc')->get();
                $pr = InventoryRequest::where('dept',Auth::user()->dept)->orderBy('created_at', 'desc')->get();
                // $dept= MstDept::whereIn('id',['8927','8884','8915'])->get();
                $response = $this->getUserSF(999);
                $no = 1;
                foreach($pr as $data){
                    $data->no = $no;
                    $no++;
                }
                $data = array(
                    // 'dept' => $dept,
                    'inventory' => $inventory,
                    'prdata' => $pr,
                    'emp' => $response,
                    'actionmenu' => $this->PermissionActionMenu('pr-management'),
                );
                return view('elsa-assist/admin-pr')->with('data', $data);
               
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }


    public function exportPr(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('pr-management')->v == 1) {
                $start = isset($request->start) ? $request->start : null;
                $end = isset($request->end) ? $request->end : null;
                $subject = isset($request->subject) ? $request->subject : null;
                $nama_file = 'export-pr-' . "-" . date('dmYHis') . '.xlsx';
                return Excel::download(new PrExport($start, $end, $subject), $nama_file);
                // echo $start.$end.$subject;
            } else {
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function PrMgmtInsert(Request $request)
    {
        try{
            if($this->PermissionActionMenu('pr-management')->c==1){
                $no = InventoryRequest::where('created_at', 'like', date('Y-m-d'). "%")->count();
                $pr_num = 'PR'.date('Ymd').str_pad($no, 4, "0", STR_PAD_LEFT); //PR20210101000
                // if(!empty($inventory)){
                    $create = InventoryRequest::create([
                        'pr_number'=>$pr_num,
                        'pr_nrp'=>$request->nrp,
                        'pr_name'=>$request->name,
                        'pr_dept'=>$request->department,
                        'dept'=>$request->dept,
                        'pr_category'=>$request->category,
                        'pr_quantity'=>$request->qty,
                        'pr_description'=>$request->message,
                        'pr_remark'=>'',
                        'created_by' => Auth::user()->id,
                    ]);
                    print($create);
                    if($create){
                        return redirect()->back()->with('suc_message', 'Request PR Berhasil!');
                    }else{
                        return redirect()->back()->with('err_message', 'Request PR Gagal!');
                    }
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
       
    }
    public function PrMgmtUpdate(Request $request)
    {
        try{
            if($this->PermissionActionMenu('pr-management')->u==1){
                $pr = InventoryRequest::where('pr_id', $request->pr_id)->first();
                // $inventory = MstInventory::where('inventory_id', $request->pr_inventory_id)->first();
                if(!empty($pr)){
                    $update = InventoryRequest::where('pr_id', $request->pr_id)
                    ->update([
                        'pr_inventory_id'=>$request->pr_inventory_id,
                        'pr_remark'=>$request->pr_remark,
                        'pr_received'=>$request->received_by,
                        // 'accepted'=>isset($request->approval) ? Auth::user()->email : '',
                        // 'accepted_date'=>isset($request->approval) ? date('Y-m-d H:i:s') : '',
                        // 'accepted_remark'=>isset($request->approval) ? $request->pr_remark : '',
                        'approved'=>isset($pr->approved) ? $pr->approved : Auth::user()->email ,
                        'approved_date'=>isset($pr->approved) ? $pr->approved_date : date('Y-m-d H:i:s') ,
                        'approved_remark'=>isset($pr->approved) ? $pr->approved_remark : $request->pr_remark ,
                        'status'=>isset($request->approval) ? $request->approval : $request->status,
                        'updated_by' => Auth::user()->id,
                    ]);
                    if($update){
                        // if($inventory){
                        //     MstInventory::where('inventory_id', $request->pr_inventory_id)->update([
                        //         'inventory_qty'=>$inventory->inventory_qty-$pr->pr_quantity,
                        //         'updated_by' => Auth::user()->id,
                        //     ]);
                        //     $this->setInventoryHistory($request->inventory_id,'Reduce From PR',$request->pr_remark,$pr->pr_quantity);
                        // }
                        return redirect()->back()->with('suc_message', 'Data berhasil diupdate!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
        
    }
   
    public function PrMgmtDelete(Request $request)
    {
        try{
            if($this->PermissionActionMenu('pr-management')->d==1){
                $pr = InventoryRequest::where('id', $request->id)->first();
                if(!empty($pr)){
                    InventoryRequest::where('id', $request->id)->update([
                        'status'=>0,
                        'updated_by' => Auth::user()->id,
                    ]);
                    return redirect()->back()->with('suc_message', 'Data telah dihapus!');
                } else {
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }  
    }
    public function PrMgmtPrInventoryOut(Request $request)
    {
        
        // echo json_encode($request->input());
        try{
                $inventory = MstInventory::where('inventory_id', $request->input('pr_out_inventory'))->first();
            $input=InventoryRequestOut::create([
                'pr_id' => $request->input('pr_out_prid'),
                'id_inventory' => $request->input('pr_out_inventory'),
                'user' => $request->input('pr_out_name'),
                'text' => $request->input('pr_out_note'),
                'qty' => $request->input('pr_out_qty'),
                'created_by' => Auth::user()->id,
                ]
            );
            if($input){
                MstInventory::where('inventory_id', $request->input('pr_out_inventory'))->update([
                    'inventory_qty'=>$inventory->inventory_qty-$request->input('pr_out_qty'),
                    'updated_by' => Auth::user()->id,
                ]);
                $this->setInventoryHistory($request->input('pr_out_inventory'),'Reduce From PR', $request->input('pr_out_note'),$request->input('pr_out_qty'));
            }
            return response()->json(['message'=>'success']);
           
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function PrMgmtPrInventoryOutUpdate(Request $request)
    {
        try{
            $inventoryreqout = InventoryRequestOut::where('id', $request->input('edit_pr_out_id'))->first();
            $inventory = MstInventory::where('inventory_id', $inventoryreqout->id_inventory)->first();

            $input=InventoryRequestOut::where('id', $request->input('edit_pr_out_id'))->update([
                'user' => $request->input('edit_pr_out_name'),
                'text' => $request->input('edit_pr_out_note'),
                'qty' => $request->input('edit_pr_out_qty'),
                'created_by' => Auth::user()->id,
                ]
            );
            if($input){
                MstInventory::where('inventory_id', $inventoryreqout->id_inventory)->update([
                    'inventory_qty'=>$inventory->inventory_qty+$inventoryreqout->qty- $request->input('edit_pr_out_qty'),
                    'updated_by' => Auth::user()->id,
                ]);
                $this->setInventoryHistory($inventoryreqout->id_inventory,'Update Reduce From PR', $request->input('edit_pr_out_note'),$request->input('edit_pr_out_qty'));
            }
            return response()->json(['message'=>'success']);
           
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function PrMgmtPrInventoryOutDelete(Request $request)
    {
        try{
            $inventoryreqout = InventoryRequestOut::where('id', $request->id)->first();
            $inventory = MstInventory::where('inventory_id', $inventoryreqout->id_inventory)->first();
            $delete = InventoryRequestOut::where('id', $request->id)->delete();
            if($delete){
                MstInventory::where('inventory_id', $inventoryreqout->id_inventory)->update([
                    'inventory_qty'=>$inventory->inventory_qty+$inventoryreqout->qty,
                    'updated_by' => Auth::user()->id,
                ]);
                $this->setInventoryHistory($inventoryreqout->id_inventory,'UnReduce From PR', $inventoryreqout->text,$inventoryreqout->qty);
            }
            return response()->json(['message'=>'success']);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
}
