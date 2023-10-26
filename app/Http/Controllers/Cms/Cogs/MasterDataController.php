<?php

namespace App\Http\Controllers\Cms\Cogs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Controller;
use App\Models\Table\Cogs\Kurs;
use App\Models\Table\Cogs\Bom;
use App\Models\Table\Cogs\Cpo;
use App\Models\Table\Cogs\Apcr;
use App\Models\Table\Cogs\ApcrApi;
use App\Models\Table\Cogs\ApcrAttachment;
use App\Models\Table\Cogs\Pir;
use App\Models\Table\Cogs\Weight;
use App\Models\Table\Cogs\Process;
use App\Models\Table\Cogs\ProductCategory;
use App\Models\Table\Cogs\RawMaterialPrice;
use App\Models\Table\Cogs\RoleData;
use App\Models\Table\Cogs\PN;

use Yajra\DataTables\Facades\DataTables;

use Exception;

class MasterDataController extends Controller
{
    protected $Filename = "";
    protected $PCRID = "";
    protected $PLI = "";
    protected $Employee = "";
    protected $Owner  = "";


    public function printarray($data_array)
    {
        foreach($data_array as $data){
            var_dump($data);
            echo "<br><br>";
        }
    }


    public function get_last_datetime($data) 
    {
        return date_format(date_create(substr($data[array_key_last(json_decode(json_encode($data),true))]["updated_at"],0,-3)),"Y-m-d H:i:s");
    }

    public function ImportAPCRAPIData()
    {
        try{ 
            $response = Http::withHeaders([
                'Authorization' => '239|1Uvr9w1NsdDscnqa9aCF6aLsm57F7PrLWRSXsqyY',
            ])->get('http://webportal.patria.co.id/satria-api-man/public/api/cogs-apcr-api');
            $message = $response["message"];
            if ($response->successful()){
                ApcrApi::truncate();
                $data = $response["data"];
                foreach ($data as $data){
                    $existPCRID = ApcrApi::where('PCRID',$data["PCRID"])->first();
                    if (!$existPCRID){
                        ApcrApi::create([
                            'Owner'=>$data["Owner"],
                            'CreatedOn'=>$data["CreatedOn"],
                            'PCRID'=>$data["PCRID"],
                            'Opportunity'=>$data["Opportunity"],
                            'PIC'=>$data["PIC"],
                            'NeedRevision'=>$data["NeedRevision"],
                            'PLI_ID'=>$data["PLI_ID"],
                            'NeedRevisionPLI_ID'=>$data["NeedRevisionPLI_ID"],
                            'COGS'=>$data["COGS"],
                            'Price'=>$data["Price"],
                            'GP'=>$data["GP"],
                            'ApprovalStatus'=>$data["ApprovalStatus"],
                            'Status'=>$data["Status"],
                            'Stagging'=>$data["Stagging"],
                        ]);
                    }else{
                        continue;
                    }
                }
                return "succes update apcr api data";
                // redirect('cogs-view-activepricecalculationrequest')->with('suc_message',$message." & diupdate !");
            }
            else{
                return "failed update apcr api data";
                // return redirect('cogs-view-activepricecalculationrequest')->with('err_message',$message);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }   
    }

    
    public function ImportAPCRAPIAttachment()
    {
        try{ 
            $response = Http::withHeaders([
                'Authorization' => '239|1Uvr9w1NsdDscnqa9aCF6aLsm57F7PrLWRSXsqyY',
            ])->get('http://webportal.patria.co.id/satria-api-man/public/api/cogs-apcr-attachment');
            $message = $response["message"];
            if ($response->successful()){
                ApcrAttachment::truncate();
                $data = $response["data"];
                foreach ($data as $data){
                    $existAttachmentID = ApcrAttachment::where('AttachmentID',$data["AttachmentID"])->first();
                    if (!$existAttachmentID){
                        ApcrAttachment::create([
                            'PCRID'=>$data["PCRID"], 
                            'AttachmentID'=>$data["AttachmentID"], 
                            'AttachmentName'=>$data["AttachmentName"],
                            'AttachmentFile'=>$data["AttachmentFile"],
                        ]);
                    }else{
                        continue;
                    }
                }
                return "succes update apcr api attachment";
                // return redirect('cogs-view-activepricecalculationrequest')->with('suc_message',$message." & diupdate !");
            }
            else{
                return "succes update apcr api attachment";
                // return redirect('cogs-view-activepricecalculationrequest')->with('err_message',$message);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }   
    }

    
    public function ImportKurs()
    {
        try{ 
            $response = Http::withHeaders([
                'Authorization' => '239|1Uvr9w1NsdDscnqa9aCF6aLsm57F7PrLWRSXsqyY',
            ])->get('http://webportal.patria.co.id/satria-api-man/public/api/cogs-kurs');
            $message = $response["message"];
            if ($response->successful()){
                Kurs::truncate();
                $data = $response["data"];
                foreach ($data as $data){
                    Kurs::create([
                        'MataUang'=>$data["MataUang"],
                        'Nilai'=>$data['Nilai'],
                        'KursJual'=>$data['KursJual'],
                        'KursBeli'=>$data['KursBeli'],
                        'KursTengah'=>$data['KursTengah'],
                    ]);
                }
                return redirect('cogs-view-kurs')->with('suc_message',$message." & diupdate !");
            }
            else{
                return redirect('cogs-view-kurs')->with('err_message',$message);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }   
    }

    public function Kurs()
    {
        try{ 
            if(isset($this->PermissionActionMenu('cogs-view-kurs')->v) AND $this->PermissionActionMenu('cogs-view-kurs')->v==1){
                if (Kurs::exists()){
                    $data = Kurs::all();
                    $updated_at = $this->get_last_datetime($data);
                    $actionmenu = $this->PermissionActionMenu('cogs-view-kurs');
                    return view('cogs/masterdata/kurs', compact('data','updated_at','actionmenu'));
                }
                else{
                    return view('cogs/masterdata/kurs', )->with('updated_at',0);
                }
            }
            else{
                return redirect('/')->with('err_message', 'Access Denied !');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }     
    }

    public function ImportPN()
    {
        try{ 
            $response = Http::withHeaders([
                'Authorization' => '239|1Uvr9w1NsdDscnqa9aCF6aLsm57F7PrLWRSXsqyY',
            ])->get('http://webportal.patria.co.id/satria-api-man/public/api/cogs-pn');
            $message = $response["message"];
            if ($response->successful()){
                PN::truncate();
                $data = $response["data"];
                foreach ($data as $data){
                    PN::create([
                        'PartNumber' =>$data["PartNumber"],
                        'Description' =>$data["Description"],
                        'Category' =>$data["Category"],
                    ]);
                }
                return redirect('cogs-view-pn')->with('suc_message',$message." & diupdate !");
            }
            else{
                return redirect('cogs-view-pn')->with('err_message',$message);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function PN()
    {
        try{ 
            if(isset($this->PermissionActionMenu('cogs-view-pn')->v) AND $this->PermissionActionMenu('cogs-view-pn')->v==1){
                if (PN::exists()){
                    $data = PN::all();
                    $updated_at = $this->get_last_datetime($data);
                    $actionmenu = $this->PermissionActionMenu('cogs-view-pn');
                    return view('cogs/masterdata/pn', compact('data','updated_at','actionmenu'));
                }
                else{
                    return view('cogs/masterdata/pn', )->with('updated_at',0);
                }
            }
            else{
                return redirect('/')->with('err_message', 'Access Denied !');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }          
    }

    public function ImportBillOfMaterial()
    {
        try{ 
            $response = Http::withHeaders([
                'Authorization' => '239|1Uvr9w1NsdDscnqa9aCF6aLsm57F7PrLWRSXsqyY',
            ])->get('http://webportal.patria.co.id/satria-api-man/public/api/cogs-cs11');
            $message = $response["message"];
            if ($response->successful()){
                Bom::truncate();
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
                $bom = array_chunk($new_data, 1000, true);
                foreach ($bom as $bom) {
                    Bom::insert($bom);
                }
                return redirect('cogs-view-billofmaterial')->with('suc_message',$message." & diupdate !");
            }
            else{
                return redirect('cogs-view-billofmaterial')->with('err_message',$message);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }

    }


    public function BillOfMaterial()
    {
        try{ 
            if(isset($this->PermissionActionMenu('cogs-view-billofmaterial')->v) AND $this->PermissionActionMenu('cogs-view-billofmaterial')->v==1){
                if (Bom::exists()){
                    $data = Bom::select('Material','Description','Plant','Item','ComponentCategory','ComponentNumber','ObjectDescription','Qty','Un','created_at','updated_at')->get();
                    $updated_at = $this->get_last_datetime($data);
                    $actionmenu = $this->PermissionActionMenu('cogs-view-billofmaterial');
                    return view('cogs/masterdata/billofmaterial', compact('data','updated_at','actionmenu'));
                }
                else{
                    return view('cogs/masterdata/billofmaterial')->with('updated_at',0);
                }
            }
            else{
                return redirect('/')->with('err_message', 'Access Denied !');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }   
    }
 

    public function ImportWeightMaterial()
    {
        try{ 
            $response = Http::withHeaders([
                'Authorization' => '239|1Uvr9w1NsdDscnqa9aCF6aLsm57F7PrLWRSXsqyY',
            ])->get('http://webportal.patria.co.id/satria-api-man/public/api/cogs-weight-material');
            $message = $response["message"];
            if ($response->successful()){
                Weight::truncate();
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
                $wom = array_chunk($new_data, 1000, true);
                foreach ($wom as $wom) {
                    Weight::insert($wom);
                }
                return redirect('cogs-view-weightofmaterial')->with('suc_message',$message." & diupdate !");
            }
            else{
                return redirect('cogs-view-weightofmaterial')->with('err_message',$message);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }  
    }


    public function WeightMaterial()
    {
        try{ 
            if(isset($this->PermissionActionMenu('cogs-view-weightofmaterial')->v) AND $this->PermissionActionMenu('cogs-view-weightofmaterial')->v==1){
                if (Weight::exists()){
                    $data = Weight::select('PN','Description','Weight','Category','created_at','updated_at')->get();
                    $updated_at = $this->get_last_datetime($data);
                    $actionmenu = $this->PermissionActionMenu('cogs-view-weightofmaterial');
                    return view('cogs/masterdata/weightofmaterial', compact('data','updated_at','actionmenu'));
                }
                else{
                    return view('cogs/masterdata/weightofmaterial')->with('updated_at',0);
                }
            }
            else{
                return redirect('/')->with('err_message', 'Access Denied !');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }


    public function ImportActivePriceCalculationRequest()
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
                return redirect('cogs-view-activepricecalculationrequest')->with('suc_message',$message." & diupdate !");
            }
            else{
                return redirect('cogs-view-activepricecalculationrequest')->with('err_message',$message);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }


    public function ActivePriceCalculationRequest()
    {
        try{ 
            if(isset($this->PermissionActionMenu('cogs-view-activepricecalculationrequest')->v) AND $this->PermissionActionMenu('cogs-view-activepricecalculationrequest')->v==1){
                if (Apcr::exists()){
                    $data = Apcr::select('Owner','CreatedOn','PCRID','Opportunity','PIC','PLI_ID','COGS','Price','GP','ApprovalStatus','Status','Stagging','created_at','updated_at')->get();
                    $updated_at = $this->get_last_datetime($data);
                    $actionmenu = $this->PermissionActionMenu('cogs-view-activepricecalculationrequest');
                    return view('cogs/masterdata/activepricecalculationrequest', compact('data','updated_at','actionmenu'));
                }
                else{
                    return view('cogs/masterdata/activepricecalculationrequest')->with('updated_at',0);
                }
            }
            else{
                return redirect('/')->with('err_message', 'Access Denied !');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

    public function ImportConfirmPurchaseOrder()
    {
        try{ 
            $response = Http::withHeaders([
                'Authorization' => '239|1Uvr9w1NsdDscnqa9aCF6aLsm57F7PrLWRSXsqyY',
            ])->get('http://webportal.patria.co.id/satria-api-man/public/api/cogs-cpo');
            $message = $response["message"];
            if ($response->successful()){
                Cpo::truncate();
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
                    Cpo::insert($cpo);
                }
                return redirect('cogs-view-confirmpurchaseorder')->with('suc_message',$message." & diupdate !");
            }
            else{
                return redirect('cogs-view-confirmpurchaseorder')->with('err_message',$message);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

    public function ConfirmPurchaseOrder()
    {
        try{ 
            if(isset($this->PermissionActionMenu('cogs-view-confirmpurchaseorder')->v) AND $this->PermissionActionMenu('cogs-view-confirmpurchaseorder')->v==1){
                if (Cpo::exists()){
                    $data = Cpo::select('*')->get();
                    $updated_at = $this->get_last_datetime($data);
                    $actionmenu = $this->PermissionActionMenu('cogs-view-confirmpurchaseorder');
                    return view('cogs/masterdata/confirmpurchaseorder', compact('data','updated_at','actionmenu'));
                }
                else{
                    return view('cogs/masterdata/confirmpurchaseorder')->with('updated_at',0);
                }
            }
            else{
                return redirect('/')->with('err_message', 'Access Denied !');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

    
    public function ImportPurchasingInfoRecord()
    {
        try{ 
            ini_set('memory_limit', '2048M');
            $response = Http::withHeaders([
                'Authorization' => '239|1Uvr9w1NsdDscnqa9aCF6aLsm57F7PrLWRSXsqyY',
            ])->get('http://webportal.patria.co.id/satria-api-man/public/api/cogs-zrpir');
            $message = $response["message"];
            if ($response->successful()){
                Pir::truncate();
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

                $pir = array_chunk($new_data, 5000, true);
                foreach ($pir as $pir) {
                    Pir::insert($pir);
                }
                return redirect('cogs-view-purchasinginforecord')->with('suc_message',$message." & diupdate !");
            }
            else{
                return redirect('cogs-view-purchasinginforecord')->with('err_message',$message);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }


    public function PurchasingInfoRecord()
    {
        try{ 
            if(isset($this->PermissionActionMenu('cogs-view-purchasinginforecord')->v) AND $this->PermissionActionMenu('cogs-view-purchasinginforecord')->v==1){
                if (Pir::exists()){
                    $data = Pir::select('PurchasingInfoRecord','PIRDate','MaterialDescription','Vendor','VendorName','UoM','NetPrice','Currency','created_at','updated_at')->get();
                    $updated_at = $this->get_last_datetime($data);
                    $actionmenu = $this->PermissionActionMenu('cogs-view-purchasinginforecord');
                    return view('cogs/masterdata/purchasinginforecord', compact('data','updated_at','actionmenu'));
                }
                else{
                    return view('cogs/masterdata/purchasinginforecord')->with('updated_at',0);
                }
            }
            else{
                return redirect('/')->with('err_message', 'Access Denied !');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }


    public function ImportMasterProcess()
    {
        try{ 
            $response = Http::withHeaders([
                'Authorization' => '239|1Uvr9w1NsdDscnqa9aCF6aLsm57F7PrLWRSXsqyY',
            ])->get('http://webportal.patria.co.id/satria-api-man/public/api/cogs-master-process');
            $message = $response["message"];
            if ($response->successful()){
                Process::truncate();
                $data = $response["data"];
                $new_data = [];
                foreach ($data as $data){
                    if (!array_key_exists('created_at', $data)){
                        $data['created_at'] = date('Y-m-d H:i:s');
                    }
                    if (!array_key_exists('updated_at', $data)){
                        $data['updated_at'] = date('Y-m-d H:i:s');
                    }
                    $new_col_data = [];
                    foreach ($data as $key => $data){
                        if ($key == 'ID'){
                            $new_col_data['No'] = $data;
                            continue;
                        }
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

                $process = array_chunk($new_data, 1000, true);
                foreach ($process as $process) {
                    Process::insert($process);
                }
                return redirect('cogs-view-masterprocess')->with('suc_message',$message." & diupdate !");
            }
            else{
                return redirect('cogs-view-masterprocess')->with('err_message',$message);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }


    public function MasterProcess()
    {
        try{ 
            if(isset($this->PermissionActionMenu('cogs-view-masterprocess')->v) AND $this->PermissionActionMenu('cogs-view-masterprocess')->v==1){
                if (Process::exists()){
                    $data = Process::select('ID','Name','TotalDay','ProductName','ProcessOrder','ProcessGroup','ProcessName','ManHour','ManPower','CycleTime','created_at','updated_at')->get();
                    $updated_at = $this->get_last_datetime($data);
                    $actionmenu = $this->PermissionActionMenu('cogs-view-masterprocess');
                    return view('cogs/masterdata/masterprocess', compact('data','updated_at','actionmenu'));
                }
                else{
                    return view('cogs/masterdata/masterprocess')->with('updated_at',0);
                }
            }
            else{
                return redirect('/')->with('err_message', 'Access Denied !');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }
    
    public function SearchPir(Request $request)
    {
        if($request->ajax()){
            return DataTables::of(Pir::all())->toJson();  
        }else{
            var_dump(gettype(Pir::all()));
        }
    }

    public function ProductCategory()
    {
        try{ 
            if(isset($this->PermissionActionMenu('cogs-view-productcategory')->v) AND $this->PermissionActionMenu('cogs-view-productcategory')->v==1){
                $data = ProductCategory::all();
                $actionmenu = $this->PermissionActionMenu('cogs-view-rawmaterialprice');
                return view('cogs/masterdata/productcategory', compact('data','actionmenu'));
            }
            else{
                return redirect('/')->with('err_message', 'Access Denied !');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

    public function SearchProductCategory(Request $request)
    {
        $data = ProductCategory::where('ID', $request->id)->first();
        echo json_encode($data);
    }

    public function InsertProductCategory(Request $request)
    {
        try{ 
            $CategoryName = $request->CategoryName;
            $db_CategoryName = (ProductCategory::select('CategoryName')->where('CategoryName', $CategoryName)->first());
            if(!$db_CategoryName){
                    ProductCategory::create([
                    'CategoryName' =>  $CategoryName,
                    'CreatedBy' =>  Auth::user()->name,
                    'UpdatedBy' =>  Auth::user()->name,
                ]);
                return redirect('cogs-view-productcategory')->with('suc_message','Data berhasil ditambahkan !');
            }
            else{
                return redirect('cogs-view-productcategory')->with('err_message','Data gagal ditambahkan !');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

    public function DeleteProductCategory(Request $request)
     {
        try{ 
            $ID = $request->id;
            $delete = ProductCategory::where('ID',$ID)->delete();
            if($delete){
                return redirect('cogs-view-productcategory')->with('suc_message','Data berhasil dihapus !');
            }
            else{
                return redirect('cogs-view-productcategory')->with('err_message','Data gagal dihapus !');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

    
    public function EditProductCategory(Request $request)
    {
        try{ 
            $ID = $request->id;
            $CategoryName = $request->CategoryName;
            $db_CategoryName = (ProductCategory::select('CategoryName')->where('CategoryName', $CategoryName)->first());
            if(!$db_CategoryName){
                if(ProductCategory::where('ID', $ID)->update(['CategoryName' => $CategoryName, 'UpdatedBy' => Auth::user()->name])){
                    return redirect('cogs-view-productcategory')->with('suc_message','Data berhasil diubah !');
                }
                else{
                    return redirect('cogs-view-productcategory')->with('err_message','Data gagal diubah !');
                }
            }
            else{
                return redirect('cogs-view-productcategory')->with('err_message','Data gagal diubah !');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    // public function Role()
    // {
    //     if(isset($this->PermissionActionMenu('cogs-view-role')->v) AND $this->PermissionActionMenu('cogs-view-role')->v==1){
    //         $data = RoleData::all();
    //         return view('cogs/masterdata/roledata', compact('data'));
    //     }
    //     else{
    //         return redirect('/')->with('err_message', 'Access Denied !');
    //     }
    // }

    // public function SearchRole(Request $request)
    // {
    //     $data = RoleData::where('ID', $request->id)->first();
    //     echo json_encode($data);
    // }

    //  public function InsertRole(Request $request)
    // {
    //     $RoleName = $request->RoleName;
    //     $db_RoleName = (RoleData::select('RoleName')->where('RoleName', $RoleName)->first());
    //     if(!$db_RoleName){
    //             RoleData::create([
    //             'RoleName' =>  $RoleName,
    //             'CreatedBy' =>  Auth::user()->name,
    //             'UpdatedBy' =>  Auth::user()->name,
    //         ]);
    //         return redirect('cogs-view-role')->with('suc_message','Data berhasil ditambahkan !');
    //     }
    //     else{
    //         return redirect('cogs-view-role')->with('err_message','Data gagal ditambahkan !');
    //     }
    // }

    // public function DeleteRole(Request $request)
    //  {
    //     $ID = $request->id;
    //     $delete = RoleData::where('ID',$ID)->delete();
    //     if($delete){
    //         return redirect('cogs-view-role')->with('suc_message','Data berhasil dihapus !');
    //     }
    //     else{
    //         return redirect('cogs-view-role')->with('err_message','Data gagal dihapus !');
    //     }
    // }

    // public function EditRole(Request $request)
    // {
    //     $ID = $request->id;
    //     $RoleName = $request->RoleName;
    //     $db_RoleName = (RoleData::select('RoleName')->where('RoleName', $RoleName)->first());
    //     if(!$db_RoleName){
    //         if(RoleData::where('ID', $ID)->update(['RoleName' => $RoleName, 'UpdatedBy' => Auth::user()->name])){
    //             return redirect('cogs-view-role')->with('suc_message','Data berhasil diubah !');
    //         }
    //         else{
    //             return redirect('cogs-view-role')->with('err_message','Data gagal diubah !');
    //         }
    //     }
    //     else{
    //         return redirect('cogs-view-role')->with('err_message','Data gagal diubah !');
    //     }
    // }

    public function RawMaterialPrice(){ 
        try{ 
            if(isset($this->PermissionActionMenu('cogs-view-rawmaterialprice')->v) AND $this->PermissionActionMenu('cogs-view-rawmaterialprice')->v==1){
                $data = RawMaterialPrice::select('*')->get();
                $kurs = Kurs::all();
                $category = Weight::select('Category')->groupBy('Category')->orderBy('category','ASC')->get();
                $actionmenu = $this->PermissionActionMenu('cogs-view-rawmaterialprice');
                return view('cogs/masterdata/rawmaterialprice', compact('data','kurs','category','actionmenu'));
            }
            else{
                return redirect('/')->with('err_message', 'Access Denied !');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }
    
    public function SearchRawMaterialPrice(Request $request)
    {
        $data = RawMaterialPrice::where('ID', $request->id)->first();
        echo json_encode($data);
    }
    
     public function InsertRawMaterialPrice(Request $request)
    {
        try{ 
            $db_RawMaterialPrice = (RawMaterialPrice::select('Category')->where('Category', $request->Category)->first());
            if(!$db_RawMaterialPrice){
                RawMaterialPrice::create([
                    'Category' =>  $request->Category,
                    'Un' => $request->Un,
                    'PriceExmill'=> $request->PriceExmill,
                    'CurrencyExmill' => $request->CurrencyExmill,
                    'PriceExstock'=> $request->PriceExstock,
                    'CurrencyExstock' => $request->CurrencyExstock,
                    'CreatedBy' =>  Auth::user()->name,
                    'UpdatedBy' =>  Auth::user()->name,
                ]);
                return redirect('cogs-view-rawmaterialprice')->with('suc_message','Data berhasil ditambahkan !');
            }
            else{
                return redirect('cogs-view-rawmaterialprice')->with('err_message','Data gagal ditambahkan !');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

    public function DeleteRawMaterialPrice(Request $request)
     {
        try{ 
            $ID = $request->IDdelete;
            $delete = RawMaterialPrice::where('ID',$ID)->delete();
            if($delete){
                return redirect('cogs-view-rawmaterialprice')->with('suc_message','Data berhasil dihapus !');
            }
            else{
                return redirect('cogs-view-rawmaterialprice')->with('err_message','Data gagal dihapus !');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

    public function EditRawMaterialPrice(Request $request)
    {
        try{ 
            $ID = $request->IDedit;
            if(RawMaterialPrice::where('ID', $ID)->update([
                'Category' => $request->editCategory, 
                'Un' => $request->editUn, 
                'PriceExmill' => $request->editPriceExmill, 
                'CurrencyExmill' => $request->editCurrencyExmill, 
                'PriceExstock' => $request->editPriceExstock, 
                'CurrencyExstock' => $request->editCurrencyExstock, 
                'UpdatedBy' => Auth::user()->name])){
                return redirect('cogs-view-rawmaterialprice')->with('suc_message','Data berhasil diubah !');
            }
            else{
                return redirect('cogs-view-rawmaterialprice')->with('err_message','Data gagal diubah !');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

    public function API_PCR(Request $request){
        $response = Http::post('https://prod-21.southeastasia.logic.azure.com/workflows/5a86c69583f94d8baea2b066cd810397/triggers/manual/paths/invoke?api-version=2016-06-01&sp=%2Ftriggers%2Fmanual%2Frun&sv=1.0&sig=JbkAin-OVPiWjbmJ3T0msrzENi9_KXGCw22guJSIwRQ');
        $response_json = $response->json();
        for ($i =0; $i < count($response_json);$i++){
            foreach (explode(",",trim($response_json[$i])) as $j => $data){
                $Filename = "";
                // var_dump($j);var_dump($data);echo"<br>";
                if ($j == 0){
                    $this->PCRID = trim(str_replace("'","",str_replace("[PCRID : ","",$data)));
                    var_dump($this->PCRID);echo"<br>";
                    ApcrApi::where('PCRID',$this->PCRID)->firstOrCreate([
                        'PCRID' => $this->PCRID
                    ]); 
                    ApcrAttachment::where('PCRID',$this->PCRID)->firstOrCreate([
                        'PCRID' => $this->PCRID
                    ]); 
                }
                if ($j == 1){
                    $this->PLI = trim(str_replace("'","",str_replace("PLI: ","",$data)));
                    var_dump($this->PLI);echo"<br>";
                    ApcrApi::where('PCRID',$this->PCRID)->update([
                        'PLI_ID' => $this->PLI
                    ]); 
                }
                if ($j == 2){
                    $this->Employee = trim(str_replace("'","",str_replace("Employee: ","",$data)));
                    var_dump($this->Employee);echo"<br>";
                    ApcrApi::where('PCRID',$this->PCRID)->update([
                        'PIC' => $this->Employee
                    ]); 
                }
                if ($j == 3){
                    $this->Owner = trim(str_replace("'","",str_replace("Owner: ","",$data)));
                    var_dump($this->Owner);echo"<br>";
                    ApcrApi::where('PCRID',$this->PCRID)->update([
                        'Owner' => $this->Owner
                    ]); 
                }
                if ($j == 4){
                    $this->Filename = trim(str_replace("'","",str_replace("Filename: ","",$data)));
                    var_dump($this->Filename);echo"<br>";
                    ApcrAttachment::where('PCRID',$this->PCRID)->where('AttachmentName',$this->Filename)->updateOrCreate([
                        'PCRID' => $this->PCRID,
                        'AttachmentID' => 0,
                        'AttachmentName' => $this->Filename
                    ]); 
                }
                if ($j == 5){
                    $DocumentBody = trim(str_replace(array("'", "]"),"",str_replace("Document Body: ","",$data)));
                    $DocumentBodyDecoded = base64_decode($DocumentBody);
                    $Directory = public_path().'/cogs/attachment/'.$this->Filename;
                    var_dump($Directory);echo"<br>";
                    $File = fopen($Directory, "w") or die("Unable to open file!");
                    file_put_contents($Directory, $DocumentBodyDecoded);
                    ApcrAttachment::where('PCRID',$this->PCRID)->where('AttachmentName',$this->Filename)->update([
                        'AttachmentFile' => $DocumentBody,
                    ]); 
                }  
            }
            echo"<br>";
        }
    }
}   
