<?php

namespace App\Http\Controllers\Cms\Cogs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


use App\Models\Table\Cogs\Bom;
use App\Models\Table\Cogs\COGSHeader;
use App\Models\View\Cogs\vwAPCR;
use App\Models\View\Cogs\vwCPO;
use App\Models\View\Cogs\vwClassification;

use Exception;


class ProductClassificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('cogs-product-classification') == 0) {
                Auth::logout();
                return redirect('login')->with('err_message', 'Access COGS denied!');
            }
            return $next($request);
        });
    }
    
    public function ProductClassification()
    {
        try{ 
            if(isset($this->PermissionActionMenu('cogs-product-classification')->v) AND $this->PermissionActionMenu('cogs-product-classification')->v==1){
                $data = array(
                    'title' => 'Product Classification',
                    'subtitle_product' => "Product Category",
                    'subtitle_pic' => "PIC Category",
                );
                return view('cogs/productclassification')->with('data', $data);
            }
            else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

    public function SearchProductClassification(Request $request)
    {
        try{ 
            $req = strtolower($request->req);
            if ($req == "all"){
                return response()->json([
                    'category' => vwClassification::select('CategoryName')->distinct('CategoryName')->whereNotNull('CategoryName')->get(),
                    'cost_estimator' => vwClassification::select('CostEstimator')->distinct('CostEstimator')->whereNotNull('CostEstimator')->get(),
                ], 200);
            }
            else{ 
                return response()->json([
                    'category' => vwClassification::select('CategoryName')->where('CategoryName', 'LIKE', '%'.$req.'%')->get(),
                    'cost_estimator' => vwClassification::select('CostEstimator')->where('CostEstimator', 'LIKE', '%'.$req.'%')->get(),
                ], 200);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }
 
    public function ProductClassificationDetail($category)
    {
        try{ 
            if(isset($this->PermissionActionMenu('cogs-product-classification')->v) AND $this->PermissionActionMenu('cogs-product-classification')->v==1){
                
                $category = trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', (string)$category));
                if($category == "L B T"){
                    $category = "LBT";
                }
                $data = array(
                    'actionmenu' => $this->PermissionActionMenu('cogs-price-calculation-request'),
                    'title1' => 'Product Classification',
                    'title2' => $category,
                    'subtitle' => 'Data ' . $category,
                    'dataCreated' => COGSHeader::select(
                        '*',
                        'cogs_header.updated_at AS COGSUpdate', 
                        'cogs_header.ID AS COGSID',
                        'apcr.Owner AS APCROwner',
                        'cpo.Owner AS CPOOwner',)
                    ->leftjoin('apcr','cogs_header.PCRID','=','apcr.PCRID')
                    ->leftjoin('cpo','cogs_header.CPOID','=','cpo.OrderID')
                    ->where('cogs_header.CostEstimator',$category)
                    ->orWhere('cogs_header.ProductCategory',$category)
                    ->orderBy('cogs_header.QuotationPrice','DESC')
                    ->get(),
                );
                return view('cogs/productclassificationdetail')->with('data', $data);
            }
            else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }
}
