<?php

namespace App\Http\Controllers\Cms\Cogs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request; 

use App\Models\Table\Cogs\Apcr;
use App\Models\Table\Cogs\Bom;
use App\Models\Table\Cogs\COGSHeader;
use App\Models\Table\Cogs\ProductCategory;
use App\Models\Table\Cogs\COGSSFComponent;
use App\Models\Table\Cogs\COGSRawMaterial;
use App\Models\Table\Cogs\COGSConsumables;
use App\Models\Table\Cogs\COGSProcess;
use App\Models\Table\Cogs\COGSOthers;
use App\Models\Table\Cogs\PartNumber;
use App\Models\View\Cogs\vwAPCR;
use App\Models\View\Cogs\vwPartNumber;

use App\Models\Table\Cogs\Cpo;
use App\Models\View\Cogs\vwCPO;
use Exception;

class ConfirmPurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('cogs-confirm-purchase-order') == 0) {
                Auth::logout();
                return redirect('login')->with('err_message', 'Access CPO denied!');
            }
            return $next($request);
        });
    }

    public function get_last_datetime($data) 
    {
        return date_format(date_create(substr($data[array_key_last(json_decode(json_encode($data),true))]["updated_at"],0,-3)),"Y-m-d H:i:s");
    }

    public function ConfirmPurchaseOrder()
    {
        try{ 
            if(isset($this->PermissionActionMenu('cogs-confirm-purchase-order')->v) AND $this->PermissionActionMenu('cogs-confirm-purchase-order')->v==1){
                $data = array(
                    'title' => 'Confirm Purchase Order',
                    'data_cpo' => vwCPO::select('*')->orderBy('CreatedOn','DESC')->get(),
                    'updated_at_cpo' => $this->get_last_datetime(vwCPO::all()),
                    'actionmenu' => $this->PermissionActionMenu('cogs-confirm-purchase-order'),
                );
                return view('cogs/confirmpurchaseorder')->with('data', $data);
            }
            else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

    public function SearchCPO(Request $request)
    {
        return response()->json([
                'CPO' => Cpo::where('OrderID', $request->cpoid)->first(),
                'PNReference' => Bom::select('Material')->distinct('Material')->get(),
                'COGSID' => is_null(COGSHeader::select('ID')->latest()->first()) ? 1 : (COGSHeader::select('ID')->latest()->first())->ID + 1,
            ], 200);
    }

     public function RefreshCPO() 
    {
        try{ 
            $response = Http::withHeaders([
                'Authorization' => '239|1Uvr9w1NsdDscnqa9aCF6aLsm57F7PrLWRSXsqyY',
            ])->get('http://webportal.patria.co.id/satria-api-man/public/api/cogs-cpo');
            $message = $response["message"];
            if ($response->successful()){
                CPO::truncate();
                $data = $response["data"];
                $new_data = [];
                foreach ($data as $data){
                    $new_col_data = [];
                    foreach ($data as $key => $data){
                        if ($key == "created_at"){
                            $new_col_data[$key] = date('Y-m-d H:i:s');
                        }
                        elseif ($key == "updated_at"){
                            $new_col_data[$key] = date('Y-m-d H:i:s');
                        }
                        else{
                            $new_col_data[$key] = $data;
                        }
                    }
                    array_push($new_data, $new_col_data);
                }
                $cpo = array_chunk($new_data, 1000, true);
                foreach ($cpo as $cpo) {
                    CPO::insert($cpo);
                }
                return redirect('cogs-confirm-purchase-order')->with('suc_message', "Data CPO berhasil diupdate !");
            }
            else{
                return redirect('cogs-confirm-purchase-order')->with('err_message',"Data CPO gagal diupdate");
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

}
