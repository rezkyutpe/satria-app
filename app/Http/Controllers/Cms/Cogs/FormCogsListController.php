<?php

namespace App\Http\Controllers\Cms\Cogs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\View\Cogs\vwCogsList;
use App\Models\Table\Cogs\Bom;
use App\Models\Table\Cogs\COGSHeader;
use Exception;

class FormCogsListController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('cogs-list') == 0) {
                Auth::logout();
                return redirect('login')->with('err_message', 'Access COGS denied!');
            }
            return $next($request);
        });
    }
    
    public function CogsList()
    {
        try{ 
            if(isset($this->PermissionActionMenu('cogs-list')->v) AND $this->PermissionActionMenu('cogs-list')->v==1){
                $data = array(
                    'title' => 'COGS List',
                    'subtitle_product' => "Product Category",
                );
                return view('cogs/cogslist')->with('data', $data);
            }
            else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

    public function SearchCogsList(Request $request)
    {
        try{ 
            $req = strtolower($request->req);
            if ($req == "all"){
                return response()->json([
                    'category' => vwCogsList::select('CategoryName')->distinct('CategoryName')->whereNotNull('CategoryName')->get(),
                    'cost_estimator' => vwCogsList::select('CostEstimator')->distinct('CostEstimator')->whereNotNull('CostEstimator')->get(),
                ], 200);
            }
            else{ 
                return response()->json([
                    'category' => vwCogsList::select('CategoryName')->where('CategoryName', 'LIKE', '%'.$req.'%')->get(),
                    'cost_estimator' => vwCogsList::select('CostEstimator')->where('CostEstimator', 'LIKE', '%'.$req.'%')->get(),
                ], 200);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

    public function SearchCOGSPN()
    {
        return response()->json([
                'PNReference' => Bom::select('Material')->distinct('Material')->get(),
                'COGSID' => is_null(COGSHeader::select('ID')->latest()->first()) ? 1 : (COGSHeader::select('ID')->latest()->first())->ID + 1,
            ], 200);
    }

    public function CogsListDetail($category)
    {
        try{ 
            if(isset($this->PermissionActionMenu('cogs-list')->v) AND $this->PermissionActionMenu('cogs-list')->v==1){
                $category = trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', (string)$category));
                $data = array(
                    'actionmenu' => $this->PermissionActionMenu('cogs-price-calculation-request'),
                    'title1' => 'Cogs List',
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
                return view('cogs/cogslistdetail')->with('data', $data);
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
