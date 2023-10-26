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


class NewCOGSController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('cogs-price-calculation-request') == 0 || $this->PermissionMenu('cogs-confirm-purchase-order') == 0) {
                Auth::logout();
                return redirect('login')->with('err_message', 'Access APCR / CPO denied!');
            }
            return $next($request);
        });
    }

    public function SearchProductCategoryManual()
    {
        return response()->json([
                'ProductCategory' => ProductCategory::select('*')->orderBy('CategoryName')->get(),
            ], 200);
    }

     public function SearchProductCategoryAuto()
    {
        return response()->json([
                'ProductCategory' => vwPartNumber::select('Category')->groupBy('Category')->orderBy('Category')->get(),
            ], 200);
    }

    public function SearchCalculationType()
    {
        $CalculationType = (object) ['New PN', 'Repeat Order'];
        return response()->json([
            'CalculationType' => $CalculationType,
        ], 200);
    }

    public function SearchBOM(Request $request)
    {
        return response()->json([
                'PNReference' => vwPartNumber::select('Material','Description','Category')->distinct('Material')->where('Category',$request->category)->orderBy('Material')->get(),
            ], 200); 
    }

    public function SearchCogsHeader(Request $request)
    {
        $COGSID = $request->cogsid;
        return response()->json([
                'CogsHeader' => COGSHeader::select('*')->where('ID',$COGSID)->get(),
            ], 200);
    }

    public function DeleteCOGS(Request $request)
    {
        try{ 
            $COGSID = $request->COGSIDdelete;
            $Menu = $request->Menu;

            //DELETE DATA
            $existCOGSRawMaterial = !(COGSRawMaterial::where('COGSID',$COGSID)->get())->isEmpty();
            $existCOGSSFComponent = !(COGSSFComponent::where('COGSID',$COGSID)->get())->isEmpty();
            $existCOGSConsumables = !(COGSConsumables::where('COGSID',$COGSID)->get())->isEmpty();
            $existCOGSProcess = !(COGSProcess::where('COGSID',$COGSID)->get())->isEmpty();
            $existCOGSOthers = !(COGSOthers::where('COGSID',$COGSID)->get())->isEmpty();
            if ($existCOGSRawMaterial){
                COGSRawMaterial::where('COGSID',$COGSID)->delete();
            }
            if ($existCOGSSFComponent){
                COGSSFComponent::where('COGSID',$COGSID)->delete();
            }
            if ($existCOGSConsumables){
                COGSConsumables::where('COGSID',$COGSID)->delete();
            }
            if ($existCOGSProcess){
                COGSProcess::where('COGSID',$COGSID)->delete();
            }
            if ($existCOGSOthers){ 
                COGSOthers::where('COGSID',$COGSID)->delete();
            }
            
            $deleteCOGSHeader = COGSHeader::where('ID',$COGSID)->delete();
            if ($Menu == "apcr"){
                if($deleteCOGSHeader){
                    return redirect('cogs-price-calculation-request')->with('suc_message', 'Data COGS berhasil dihapus!');
                }else{
                    return redirect('cogs-price-calculation-request')->with('err_message', 'Data COGS gagal dihapus!');
                }
            }
            if ($Menu == "cpo"){
                if($deleteCOGSHeader){
                    return redirect('cogs-confirm-purchase-order')->with('suc_message', 'Data COGS berhasil dihapus!');
                }else{
                    return redirect('cogs-confirm-purchase-order')->with('err_message', 'Data COGS gagal dihapus!');
                }
            }
            if ($Menu == "classification"){
                $Submenu = $request->Submenu;
                if($deleteCOGSHeader){
                    return redirect('cogs-product-classification-detail/'.$Submenu)->with('suc_message', 'Data COGS berhasil dihapus!');
                }else{
                    return redirect('cogs-product-classification-detail/'.$Submenu)->with('err_message', 'Data COGS gagal dihapus!');
                }
            }
            if ($Menu == "list"){
                $Submenu = $request->Submenu;
                if($deleteCOGSHeader){
                    return redirect('cogs-list-detail/'.$Submenu)->with('suc_message', 'Data COGS berhasil dihapus!');
                }else{
                    return redirect('cogs-list-detail/'.$Submenu)->with('err_message', 'Data COGS gagal dihapus!');
                }
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

}
 
