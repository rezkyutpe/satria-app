<?php
  
namespace App\Http\Controllers\Cms\PoNonSAP;
use App\Http\Controllers\Controller;
  
use Illuminate\Http\Request;
use App\Models\Table\PoNonSAP\MstPo;
use PDF;
use Illuminate\Support\Facades\Http;
  
class ProManController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
      $this->middleware('auth');


    }
    public function ProGetInit(Request $request)
    {     
        $response = Http::get('http://webportal.patria.co.id/apisunfish/allempMagang.php');
        $arr = json_decode($response,true);
        $datas=array();
        foreach ($arr['emp'] as $key ) {
            if($key['nrp']=="1103009"){
                array_push($datas, $key); 
            }
        }
        return $datas[0];
    }
    public function getPro($pro){
        $response = Http::get('http://10.48.10.43/imaapi/api/GetPROCustProduct?nopro='.$pro);
        $data = json_decode($response,true);
        $pro = count(MstPo::where('pro',$pro)->get())+1;
        $arr = array(
            'data' => $data,
            'pro' => $pro
        );
        return response()->json($arr);     
    }
    
}