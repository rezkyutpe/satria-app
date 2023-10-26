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
use Exception;

class PriceCalculationRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('cogs-price-calculation-request') == 0) {
                Auth::logout();
                return redirect('login')->with('err_message', 'Access APCR denied!');
            }
            return $next($request);
        });
    }

    public function get_last_datetime($data) 
    {
        return date_format(date_create(substr($data[array_key_last(json_decode(json_encode($data),true))]["updated_at"],0,-3)),"Y-m-d H:i:s");
    }

    public function PriceCalculationRequest()
    {
        try{ 
            if(isset($this->PermissionActionMenu('cogs-price-calculation-request')->v) AND $this->PermissionActionMenu('cogs-price-calculation-request')->v==1){
                $data = array(
                    'title' => 'Price Calculation Request',
                    'data_pcr' => vwAPCR::select('*')->where('Status','=','Open')->orderBy('CreatedOn','DESC')->get(),
                    'updated_at_apcr' => $this->get_last_datetime(vwAPCR::all()),
                    'actionmenu' => $this->PermissionActionMenu('cogs-price-calculation-request'),
                );
                // $this->RefreshApcr();
                return view('cogs/pricecalculationrequest')->with('data', $data);
            }
            else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

    public function SearchAPCR(Request $request)
    {
        return response()->json([
                'APCR' => Apcr::where('PCRID', $request->pcrid)->first(),
                'PNReference' => Bom::select('Material')->distinct('Material')->get(),
                'COGSID' => is_null(COGSHeader::select('ID')->latest()->first()) ? 1 : (COGSHeader::select('ID')->latest()->first())->ID + 1,
            ], 200);
    }

    
    public function RefreshAPCR()
    {
        try{
            $response = Http::withHeaders([
                'Authorization' => '239|1Uvr9w1NsdDscnqa9aCF6aLsm57F7PrLWRSXsqyY',
            ])->get('http://webportal.patria.co.id/satria-api-man/public/api/cogs-apcr');
            $message = $response["message"];
            if ($response->successful()){
                Apcr::truncate();
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
                $apcr = array_chunk($new_data, 1000, true);
                foreach ($apcr as $apcr) {
                    Apcr::insert($apcr);
                }
                return redirect('cogs-price-calculation-request')->with('suc_message', "Data APCR berhasil diupdate !");
            }
            else{
                return redirect('cogs-price-calculation-request')->with('err_message',"Data APCR gagal diupdate");
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }
}
