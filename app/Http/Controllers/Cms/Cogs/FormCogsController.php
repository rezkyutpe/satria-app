<?php

namespace App\Http\Controllers\Cms\Cogs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;


use App\Models\Table\Cogs\Pir;
use App\Models\Table\Cogs\Bom;
use App\Models\Table\Cogs\Kurs;
use App\Models\Table\Cogs\COGSHeader;
use App\Models\Table\Cogs\ProductCategory;
use App\Models\Table\Cogs\COGSSFComponent;
use App\Models\Table\Cogs\COGSRawMaterial;
use App\Models\Table\Cogs\RawMaterialPrice;
use App\Models\Table\Cogs\COGSConsumables;
use App\Models\Table\Cogs\COGSProcess;
use App\Models\Table\Cogs\COGSOthers;
use App\Models\Table\Cogs\ApcrAttachment;
use App\Models\View\Cogs\vwRawMaterial;
use App\Models\View\Cogs\vwRawMaterialGroup;
use App\Models\View\Cogs\vwSFComponent;
use App\Models\View\Cogs\vwConsumables;
use App\Models\View\Cogs\vwProcess;
use App\Models\View\Cogs\vwProcessGroup;
use App\Models\View\PoTracking\VwDataPOCogs;
use Exception;

use PDF;

class FormCogsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('cogs') == 0) {
                Auth::logout();
                return redirect('login')->with('err_message', 'Access COGS denied!');
            }
            return $next($request);
        });
    }

    public function get_last_datetime($data) 
    {
        return date_format(date_create(substr($data[array_key_last(json_decode(json_encode($data),true))]["updated_at"],0,-3)),"Y-m-d H:i:s");
    }

    public function CreateFormCOGS(Request $request)
    {
        try{ 
            $COGSID = $request->COGSID;
            $PCRID = $request->PCRID;
            $CPOID = $request->CPOID;
            $BOMMaterialExist = Bom::select('Material', 'Description')->where('Material',$request->PNReference)->first();
            if ($BOMMaterialExist){
                $PNReference = $BOMMaterialExist->Material;
                $PNReferenceDesc = $BOMMaterialExist->Description;
            }
            else{
                $PNReference = $request->PNReference;
                if (is_null($PNReference)){
                    $PNReference = "";
                }
                $PNReferenceDesc = "";
            }
            
            $CogsDataNotExists = !COGSHeader::exists();
            $CogsDataExistsAndIDNotExist = COGSHeader::exists() AND is_null(COGSHeader::select('ID')->where('ID',$COGSID)->first());
            $CogsDataExistsAndPCRIDNotExist = COGSHeader::exists() AND is_null(COGSHeader::select('PCRID')->where('PCRID',$PCRID)->first());
            $CogsDataExistsAndCPOIDNotExist = COGSHeader::exists() AND is_null(COGSHeader::select('CPOID')->where('CPOID',$CPOID)->first());

            if($CogsDataNotExists || ($CogsDataExistsAndIDNotExist AND ($CogsDataExistsAndPCRIDNotExist OR $CogsDataExistsAndCPOIDNotExist))){
                COGSHeader::create([
                    'ID' => $COGSID,
                    'ProductCategory'=> $request->ProductCategory,
                    'PCRID' =>$PCRID,
                    'CPOID' =>$CPOID,
                    'CalculationType' => $request->CalculationType,
                    'Opportunity' => $request->Opportunity,
                    'PNReference' => $PNReference, 
                    'PNReferenceDesc' => $PNReferenceDesc, 
                    'PICTriatra' => $request->PICTriatra,
                    'CostEstimator' => $request->CostEstimator,
                    'MarketingDeptHead' => $request->MarketingDeptHead,
                    'SCMDivisionHead' => $request->SCMDivisionHead,
                    'CalculatedBy' => 'Gross Profit',
                    'GrossProfit' => 0,
                    'QuotationPrice' => 0,
                    'PDF' => "",
                    'PEDNumber' => $request->PEDNumber,
                    'UnitWeight' => $request->UnitWeight,
                    'CreatedBy' => Auth::user()->name, 
                    'UpdatedBy' => Auth::user()->name,
                ]);
            }
            else {
                return redirect('cogs-price-calculation-request')->with('err_message', 'Form COGS gagal dibuat !');
            }
            return redirect('cogs-form-cogs/'.$COGSID.'/create');
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

    public function FormCOGS($COGSID, $access)
    {
        try{ 
            $COGSHeader = COGSHeader::select('*')->where('ID',$COGSID)->first();
            $COGSID = $COGSHeader->ID;
            // $ProductCategory = ProductCategory::select('CategoryName')->where('ID',$COGSHeader->IDProductCategory)->first();
            $PNReference = $COGSHeader->PNReference;
            //RAWMATERIAL
            $DataCOGSRawMaterial = COGSRawMaterial::select('*')->where('COGSID',$COGSID)->orderBy('Spesification','ASC')->get();
            $TotalWeight = 0;
            $TotalFinalCostRawMaterial = 0;
            if($DataCOGSRawMaterial){
                foreach($DataCOGSRawMaterial as $item){
                $TotalWeight += round($item->Weight,2);
                $TotalFinalCostRawMaterial += round($item->FinalCost,2);
                }
            }
            //SFCOMPONENT
            $DataCOGSSFComponent = COGSSFComponent::select('*')->where('COGSID',$COGSID)->orderBy('Component','ASC')->get();
            $TotalFinalPriceSFComponent = 0;
            if($DataCOGSSFComponent){
                foreach($DataCOGSSFComponent as $item){
                    $TotalFinalPriceSFComponent += $item->FinalPrice;
                }
            }
            //CONSUMABLES
            $DataCOGSConsumables = COGSConsumables::select('*')->where('COGSID',$COGSID)->orderBy('Component','ASC')->get();
            $TotalFinalPriceConsumables = 0;
            if($DataCOGSConsumables){
                foreach($DataCOGSConsumables as $item){
                    $TotalFinalPriceConsumables += $item->FinalPrice;
                }
            }
            //MASTERPROCESS
            $DataCOGSMasterProcess = COGSProcess::select('*')->where('COGSID',$COGSID)->orderBy('Process','ASC')->get();
            $TotalHours = 0;
            $TotalCostProcess = 0;
            if($DataCOGSMasterProcess){
                foreach($DataCOGSMasterProcess as $item){
                    $TotalHours += $item->Hours;
                    $TotalCostProcess += $item->Cost;
                }
            }   
            //OTHERS
            $DataCOGSOthers = COGSOthers::select('*')->where('COGSID',$COGSID)->orderBy('PartNumber','ASC')->get();
            $TotalFinalPriceOthers = 0;
            if($DataCOGSOthers){
                foreach($DataCOGSOthers as $item){
                    $TotalFinalPriceOthers += $item->TotalPrice;
                }
            }
            COGSHeader::where('ID',$COGSID)->update([
                'TotalRawMaterial' => $TotalFinalCostRawMaterial,
                'TotalSFComponent'=> $TotalFinalPriceSFComponent,
                'TotalConsumables'=> $TotalFinalPriceConsumables,
                'TotalProcess'=> $TotalCostProcess,
                'TotalOthers'=> $TotalFinalPriceOthers,
            ]);
            $DataCalculatedBy = ['Gross Profit','Quotation Price'];
            $TotalDirectMaterial = $TotalFinalCostRawMaterial + $TotalFinalPriceSFComponent + $TotalFinalPriceConsumables + $TotalFinalPriceOthers;
            $TotalDirectLabour =  $TotalCostProcess;
            $TotalCOGS =  $TotalDirectMaterial + $TotalDirectLabour;
            $created_at = date_format($COGSHeader->created_at,"d-M-Y (H:i:s)") ;
            $updated_at = date_format($COGSHeader->updated_at,"d-M-Y (H:i:s)") ;
            if($COGSHeader->PCRID != NULL) {

                $Attachment =  Http::withHeaders([
                    'Authorization' => '239|1Uvr9w1NsdDscnqa9aCF6aLsm57F7PrLWRSXsqyY',
                ])->get('http://webportal.patria.co.id/satria-api-man/public/api/apcr_attachment_bypcrid/'.$COGSHeader->PCRID)["data"];
            }else{
                $Attachment = "";
            }
            // var_dump($Attachment);
            // exit();
            
            // $Attachment = ApcrAttachment::select('*')->where('PCRID',$COGSHeader->PCRID)->get();
            // if (!$Attachment){
            //     CpoAttachment::select('*')->where('CPOID',$COGSHeader->CPOID)->get();
            // }
            $data = array(
                    'COGSID' => $COGSID,
                    // 'IDProductCategory' => $COGSHeader->IDProductCategory,
                    // 'ProductCategory' => $ProductCategory == true ? $ProductCategory->CategoryName : "",
                    'ProductCategory' => $COGSHeader->ProductCategory,
                    'PCRID' => $COGSHeader->PCRID,
                    'CPOID' => $COGSHeader->CPOID,
                    'CalculationType' => $COGSHeader->CalculationType,
                    'Opportunity' => $COGSHeader->Opportunity,
                    'PNReference' => $COGSHeader->PNReferenceDesc == "" ? $COGSHeader->PNReference : $COGSHeader->PNReference . " (" . $COGSHeader->PNReferenceDesc . ")",
                    'PNReferenceCode' => $PNReference,
                    'PICTriatra' => $COGSHeader->PICTriatra,
                    'CostEstimator' => $COGSHeader->CostEstimator,
                    'MarketingDeptHead' => $COGSHeader->MarketingDeptHead,
                    'SCMDivisionHead' => $COGSHeader->SCMDivisionHead,
                    'PEDNumber' => $COGSHeader->PEDNumber,
                    'UnitWeight' => $COGSHeader->UnitWeight,
                    'CreatedBy' => $COGSHeader->CreatedBy,
                    'UpdatedBy' => $COGSHeader->UpdatedBy,
                    'created_at' => $created_at,
                    'updated_at' =>  $updated_at,
                    'DataCOGSRawMaterial' => $DataCOGSRawMaterial,
                    'TotalWeight' => $TotalWeight,
                    'TotalFinalCostRawMaterial' => $TotalFinalCostRawMaterial,
                    'DataCOGSSFComponent' => $DataCOGSSFComponent,
                    'DataCOGSSFComponent' => $DataCOGSSFComponent,
                    'TotalFinalPriceSFComponent' => $TotalFinalPriceSFComponent,
                    'DataCOGSConsumables' => $DataCOGSConsumables,
                    'TotalFinalPriceConsumables' => $TotalFinalPriceConsumables,
                    'DataCOGSMasterProcess' => $DataCOGSMasterProcess,
                    'TotalHours' => $TotalHours,
                    'TotalCostProcess' => $TotalCostProcess,
                    'DataCOGSOthers' => $DataCOGSOthers,
                    'TotalFinalPriceOthers' => $TotalFinalPriceOthers,
                    'DataCalculatedBy' => $DataCalculatedBy,
                    'TotalDirectMaterial' => $TotalDirectMaterial,
                    'TotalDirectLabour' => $TotalDirectLabour,
                    'TotalCOGS' => $TotalCOGS,
                    'DataKurs' => Kurs::all(),
                    'updated_at_kurs' => $this->get_last_datetime(Kurs::all()),
                    'Attachment' => $Attachment,
            
                );
            if ($access == 'create'){
                if ($this->ImportKursFormCOGS($COGSID,'refresh')){
                    $data['DataKurs'] = Kurs::all();
                    $data['updated_at_kurs'] = $this->get_last_datetime(Kurs::all());
                    return view('cogs/formcogs')->with('data', $data)->with('suc_message', 'Form COGS berhasil dibuat & Kurs berhasil diperbaharui !');
                }   
            }
            else if ($access == 'view'){
                if ($this->ImportKursFormCOGS($COGSID,'refresh')){
                    $data['DataKurs'] = Kurs::all();
                    $data['updated_at_kurs'] = $this->get_last_datetime(Kurs::all());
                    return view('cogs/formcogs')->with('data', $data)->with('suc_message','Kurs berhasil diperbaharui !');;
                }  
            }else if ($access == 'importkurs'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Kurs berhasil diperbaharui !');
            }else if ($access == 'successupdateheader'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Header berhasil diperbaharui !');
            }else if ($access == 'failedupdateheader'){
                return view('cogs/formcogs')->with('data', $data)->with('err_message','Header gagal diperbaharui !');
            }else if  ($access == 'getSFComponent'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data S/F & Component berhasil diambil !');
            }else if  ($access == 'getRawMaterial'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data Raw Material berhasil diambil !');
            }else if  ($access == 'getConsumables'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data Consumables berhasil diambil !');
            }else if  ($access == 'getMasterProcess'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data Master Process berhasil diambil !');
            }else if  ($access == 'editSFComponent'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data S/F & Component berhasil diupdate !');   
            }else if  ($access == 'editRawMaterial'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data Raw Material berhasil diupdate !');   
            }else if  ($access == 'editConsumables'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data Consumables berhasil diupdate !');   
            }else if  ($access == 'editProcess'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data Process berhasil diupdate !');   
            }else if  ($access == 'editOthers'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data Others berhasil diupdate !');   
            }else if  ($access == 'addSFComponent'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data S/F & Component berhasil ditambahkan !');   
            }else if  ($access == 'addRawMaterial'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data Raw Material berhasil ditambahkan !');   
            }else if  ($access == 'addConsumables'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data Consumables berhasil ditambahkan !');   
            }else if  ($access == 'addProcess'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data Process berhasil ditambahkan !');   
            }else if  ($access == 'addOthers'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data Others berhasil ditambahkan !');   
            }else if  ($access == 'deleteSFComponent'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data S/F & Component berhasil dihapus !');   
            }else if  ($access == 'deleteRawMaterial'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data Raw Material berhasil dihapus !');   
            }else if  ($access == 'deleteConsumables'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data Consumables berhasil dihapus !');   
            }else if  ($access == 'deleteProcess'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data Process berhasil dihapus !');   
            }else if  ($access == 'deleteOthers'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Data Others berhasil dihapus !');   
            }else if  ($access == 'getZeroConsumables'){
                return view('cogs/formcogs')->with('data', $data)->with('err_message','Tidak ada data Consumables di Database / SAP !');
            }else if  ($access == 'getZeroSFComponent'){
                return view('cogs/formcogs')->with('data', $data)->with('err_message','Tidak ada data S/F & Component di Database / SAP !');
            }else if  ($access == 'getZeroRawMaterial'){
                return view('cogs/formcogs')->with('data', $data)->with('err_message','Tidak ada data Raw Material di Database / SAP !');
            }else if  ($access == 'getZeroRawMaterial'){
                return view('cogs/formcogs')->with('data', $data)->with('err_message','Tidak ada data Raw Material di Database / SAP !');
            }else if  ($access == 'getIncompleteRawMaterialCurrency'){
                return view('cogs/formcogs')->with('data', $data)->with('err_message','Ada data raw material yang tidak memiliki harga, cek kembali data Raw Material Price !');
            }else if  ($access == 'getZeroMasterProcess'){
                return view('cogs/formcogs')->with('data', $data)->with('err_message','Tidak ada data Master Process di Database / SAP !');
            }else if  ($access == 'updateCalculationSuccess'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Berhasil update kalkulasi !');
            }else if  ($access == 'updateCalculationFailed'){
                return view('cogs/formcogs')->with('data', $data)->with('err_message','Gagal update kalkulasi !');
            }else if  ($access == 'successSaveAs'){
                return view('cogs/formcogs')->with('data', $data)->with('suc_message','Sukses menyimpan COGS baru dengan data ini !');
            }else if  ($access == 'failedSaveAs'){
                return view('cogs/formcogs')->with('data', $data)->with('err_message','Gagal menyimpan COGS baru dengan data ini !');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

    public function ImportKursFormCOGS($COGSID,$access='')
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
                if ($access == 'refresh'){
                    return true;
                }else{
                    return redirect('cogs-form-cogs/'.$COGSID.'/importkurs');
                }
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        } 
    }

    public function UpdateHeaderCOGS(Request $request)
    {
        try{ 
            $COGSID = $request->COGSID;
            // $IDProductCategory = (ProductCategory::select('ID')->where('ID',$request->newIDProductCategory)->first())->ID;
            $BOMMaterialExist = Bom::select('Material', 'Description')->where('Material',$request->newPNReference)->first();
            if ($BOMMaterialExist){
                $PNReference = $BOMMaterialExist->Material;
                $PNReferenceDesc = $BOMMaterialExist->Description;
            }
            else{
                $PNReference = $request->newPNReference;
                $PNReferenceDesc = "";
            }        

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

            $UpdateCOGSHeader = COGSHeader::where('ID', $COGSID)->update([
                            'ProductCategory' => $request->newProductCategory, 
                            'CalculationType' => $request->newCalculationType,
                            'PNReference' => $request->newPNReference,
                            'PNReferenceDesc' => $PNReferenceDesc,
                            'CalculatedBy' => 'Gross Profit',
                            'GrossProfit' => 0,
                            'QuotationPRice' => 0,
                            'PDF'=> '',
                            'TotalRawMaterial' => 0, 
                            'TotalSFComponent' => 0, 
                            'TotalConsumables' => 0, 
                            'TotalProcess' => 0, 
                            'TotalOthers' => 0,
                            'PEDNumber' => $request->PEDNumber,
                            'UnitWeight' => $request->UnitWeight,
                            'UpdatedBy' => Auth::user()->name,
                        ]);

            if($UpdateCOGSHeader){
                return redirect('cogs-form-cogs/'.$COGSID.'/successupdateheader');
            }else{
                return redirect('cogs-form-cogs/'.$COGSID.'/failedupdateheader');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function FilterComponent($DataComponent){
        try{ 
            $tempPIRDate = [];
            $newDataComponent = [];
            $lenDataComponent = count($DataComponent);
            $DataComponent[$lenDataComponent]['ComponentNumber'] = "";
            $DataComponent[$lenDataComponent]['PIRDate'] = "";
            $DataComponent[$lenDataComponent]['NetPrice'] = 0;
            $DataComponent[$lenDataComponent]['Currency'] = "";
            $DataComponent[$lenDataComponent]['VendorName'] = "";
            // var_dump($PNReference);echo '<br><br>';
            // dd("1");
            foreach ($DataComponent as $i => $row){
                // var_dump($i);
                if(!isset($row['Material'])){
                    break;
                }
                // dd($row);
                $newItem  = [];
                $newItem['Material'] = $row['Material'];
                $newItem['Description'] = $row['Description'];
                $newItem['ComponentCategory'] = $row['ComponentCategory'];
                $newItem['ComponentNumber'] = $row['ComponentNumber'];
                $newItem['ObjectDescription'] = $row['ObjectDescription'];
                $newItem['QtyBOM'] = $row['QtyBOM'];
                $newItem['Un'] = $row['Un'];
                $newItem['PurchasingInfoRecord'] = $row['PurchasingInfoRecord'];
                $newItem['PIRDate'] = $row['PIRDate'];
                $newItem['Vendor'] = $row['Vendor'];
                $newItem['VendorName'] = $row['VendorName'];
                $newItem['NetPrice'] = $row['NetPrice'];
                $newItem['Currency'] = $row['Currency'];
                
                $currDataComponentComponentNumber = $newItem['ComponentNumber'];
                $nextDataComponentComponentNumber = $DataComponent[$i+1]['ComponentNumber'];
                $currDataComponentPIRDate = $newItem['PIRDate'];
                $nextDataComponentPIRDate = $DataComponent[$i+1]['PIRDate'];
                $currDataComponentNetPrice = $newItem['NetPrice'];
                $nextDataComponentNetPrice = $DataComponent[$i+1]['NetPrice'];
                $currDataComponentCurrency = $newItem['Currency'];
                $nextDataComponentCurrency = $DataComponent[$i+1]['Currency'];
                $currDataVendorName = $newItem['VendorName'];
                $nextDataVendorName = $DataComponent[$i+1]['VendorName'];

                // echo '<br>';var_dump($currDataComponentComponentNumber);var_dump($nextDataComponentComponentNumber);
                $highestPIRDate = "";
                $highestNetPrice = 0;
                $highestCurrency = "";
                $highestVendorName  = "";
                if ($nextDataComponentComponentNumber == $currDataComponentComponentNumber){//JIKA SAMA
                    // echo "SAMA ";
                    $a = ['PIRDate' => $currDataComponentPIRDate, 'NetPrice' => $currDataComponentNetPrice, 'Currency' => $currDataComponentCurrency, 'VendorName' => $currDataVendorName];
                    $b = ['PIRDate' => $nextDataComponentPIRDate, 'NetPrice' => $nextDataComponentNetPrice, 'Currency' => $nextDataComponentCurrency, 'VendorName' => $nextDataVendorName];
                    array_push($tempPIRDate, $a, $b);
                    // dd($tempPIRDate);
                }
                else{// JIKA BEDA
                    // var_dump($currDataComponentPIRDate);var_dump($currDataComponentNetPrice);echo "BEDA ";echo '<br>';
                    //PIRDATE TERUPDATE
                    
                    foreach($tempPIRDate as $i => $tempPIRDateData){
                        if ($i == 0){
                            $highestPIRDate = $tempPIRDate[$i]['PIRDate'];
                            $highestNetPrice = $tempPIRDate[$i]['NetPrice'];
                            $highestCurrency = $tempPIRDate[$i]['Currency'];
                            $highestVendorName = $tempPIRDate[$i]['VendorName'];
                        
                        }
                        else{
                            if ( $highestPIRDate < $tempPIRDate[$i]['PIRDate']){
                                $highestPIRDate = $tempPIRDate[$i]['PIRDate'];
                                $highestNetPrice = $tempPIRDate[$i]['NetPrice'];
                                $highestCurrency = $tempPIRDate[$i]['Currency'];
                                $highestVendorName = $tempPIRDate[$i]['VendorName'];
                            }
                        }
                        // var_dump($i);var_dump($tempPIRDateData);echo '<br>';
                    }
                    //JIKA NET PRICE 0
                    if ($highestNetPrice == 0){
                        // var_dump('XXX');echo '<br>';
                        foreach($tempPIRDate as $i => $tempPIRDateData){
                            if ($i == 0){
                            $highestPIRDate = $tempPIRDate[$i]['PIRDate'];
                            $highestNetPrice = $tempPIRDate[$i]['NetPrice'];
                            $highestCurrency = $tempPIRDate[$i]['Currency'];
                            $highestVendorName = $tempPIRDate[$i]['VendorName'];
                            }
                            else{
                                if ( $highestNetPrice < $tempPIRDate[$i]['NetPrice']){
                                    $highestPIRDate = $tempPIRDate[$i]['PIRDate'];
                                    $highestNetPrice = $tempPIRDate[$i]['NetPrice'];
                                    $highestCurrency = $tempPIRDate[$i]['Currency'];
                                    $highestVendorName = $tempPIRDate[$i]['VendorName'];
                                }
                            }
                        }
                    }
                    $newItem['PIRDate'] = $highestPIRDate; 
                    $newItem['NetPrice'] = $highestNetPrice;
                    $newItem['Currency'] = $highestCurrency;
                    $newItem['VendorName'] = $highestVendorName;
                    $tempPIRDate = array();
                    // var_dump( $newItem['PurchasingInfoRecord']); var_dump($newItem['ComponentNumber']);var_dump($newItem['PIRDate']);var_dump( $newItem['NetPrice']);var_dump( $newItem['Currency']);var_dump( $newItem['VendorName']);echo '<br>';
                    if ($newItem['PIRDate'] == ""){
                        $newItem['PIRDate'] = (vwSFComponent::select('PIRDate')->where('Material',$newItem['Material'])->where('ComponentNumber',$newItem['ComponentNumber'])->first())->PIRDate;
                        $newItem['NetPrice'] = (vwSFComponent::select('NetPrice')->where('Material',$newItem['Material'])->where('ComponentNumber',$newItem['ComponentNumber'])->first())->NetPrice;
                        $newItem['Currency'] = (vwSFComponent::select('Currency')->where('PurchasingInfoRecord',$newItem['PurchasingInfoRecord'])->first())->Currency;
                        $newItem['VendorName'] = (vwSFComponent::select('VendorName')->where('Material',$newItem['Material'])->where('ComponentNumber',$newItem['ComponentNumber'])->first())->VendorName;
                    }
                    array_push($newDataComponent, $newItem);
                    // dd($newItem);
                    continue;
                }
                // dd("12");
                // dd($newDataComponent);
            }

            // dd($newDataComponent);
            
            return $newDataComponent;
        } catch (Exception $e) {  
            
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function SearchKursFormCOGS(){
        return response()->json([
                'Kurs' => Kurs::select('*')->orderBy('MataUang','ASC')->get(),
        ], 200);
    }

    public function GetRawMaterial($COGSID){
        try{ 
            $COGSHeader = COGSHeader::select('*')->where('ID',$COGSID)->first();
            $PNReference = $COGSHeader->PNReference;
            $DataRawMaterial = (vwRawMaterialGroup::select('*')->where('Material',$PNReference)->orderBy('Category','ASC')->get())->toArray();
            if (empty($DataRawMaterial)){
                return redirect('cogs-form-cogs/'.$COGSID.'/getZeroRawMaterial');
            }else{       
                COGSRawMaterial::where('COGSID',$COGSID)->delete();
                foreach($DataRawMaterial as $itemDataRawMaterial){
                    $Weight = round($itemDataRawMaterial['TotalWeight'],2);
                    $Price = $itemDataRawMaterial['PriceExmill'];
                    $Currency = $itemDataRawMaterial['CurrencyExmill'];
                    if (is_null($Currency)){
                        return redirect('cogs-form-cogs/'.$COGSID.'/getIncompleteRawMaterialCurrency');
                    }
                    $Kurs = (Kurs::select('KursTengah')->where('MataUang',$Currency)->first())->KursTengah;
                    $FinalCost =  $Weight * $Price * $Kurs ;
                    COGSRawMaterial::Create([
                        'COGSID' => $COGSID,
                        'Spesification' => $itemDataRawMaterial['Category'],
                        'Weight' => $Weight,
                        'Price' => $Price,
                        'Currency' => $Currency,
                        'Status'=> 'Exmill',
                        'Un' => $itemDataRawMaterial['Un'],
                        'FinalCost' => $FinalCost,
                        'CreatedBy' => Auth::user()->name, 
                        'UpdatedBy' => Auth::user()->name,
                    ]);
                }
                COGSHeader::where('ID',$COGSID)->Update([
                        'UpdatedBy' => Auth::user()->name,
                ]);
            }
            return redirect('cogs-form-cogs/'.$COGSID.'/getRawMaterial');
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function AddRawMaterial(Request $request){
        try{ 
            $COGSID = $request->addCOGSIDRaw;
            $addRawMaterial = COGSRawMaterial::Create([
                'COGSID' => $COGSID,
                'Spesification' => $request->addSpesificationRaw,
                'Weight' => $request->addWeightRaw, 
                'Price' => $request->addPriceRaw,
                'Currency' => $request->addCurrencyRaw,
                'Un' =>$request->addUnRaw,
                'Status'=> $request->addStatusPriceRaw,
                'FinalCost' => $request->addFinalCostRaw,
                'CreatedBy' => Auth::user()->name, 
                'UpdatedBy' => Auth::user()->name,
            ]);
            if ($addRawMaterial){
                COGSHeader::where('ID',$COGSID)->Update([
                    'UpdatedBy' => Auth::user()->name,
                ]);
                return redirect('cogs-form-cogs/'.$COGSID.'/addRawMaterial');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function SearchRawMaterial(Request $request){
        return response()->json([
                'RawMaterial' => COGSRawMaterial::select('*')->where('ID',$request->id)->first(),
            ], 200);
    }

     public function SearchStatusPriceRawMaterial(Request $request){
         return response()->json([
                'RawMaterialGroup' => vwRawMaterialGroup::select('*')->where('Material',$request->material)->where('Category',$request->category)->first(),
            ], 200);
     }

    public function EditStatusRawMaterial(Request $request){
        try{ 
            $COGSID = $request->editCOGSIDStatusRaw;
            $ID = $request->editIDStatusRaw;
            $editRawMaterial = COGSRawMaterial::where('ID',$ID)->update([  
                'COGSID' => $COGSID,
                'Spesification' => $request->editStatusSpesification,
                'Weight' => $request->editStatusWeight,
                'Price' =>$request->editStatusPrice,
                'Currency' => $request->editStatusCurrency,
                'Un' =>$request->editStatusUn,
                'Status' => $request->editStatusStatusPrice, 
                'FinalCost' => $request->editStatusFinalCost, 
                'UpdatedBy' => Auth::user()->name,
            ]);
            if ($editRawMaterial){
                COGSHeader::where('ID',$COGSID)->Update([
                    'UpdatedBy' => Auth::user()->name,
                ]);
                return redirect('cogs-form-cogs/'.$COGSID.'/editRawMaterial');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function EditRawMaterial(Request $request){
        try{ 
            $COGSID = $request->editCOGSIDRaw;
            $ID = $request->editIDRaw;
            $editRawMaterial = COGSRawMaterial::where('ID',$ID)->update([
                'COGSID' => $COGSID,
                'Spesification' => $request->editSpesificationRaw,
                'Weight' => $request->editWeightRaw,
                'Price' =>$request->editPriceRaw,
                'Currency' => $request->editCurrencyRaw,
                'Un' =>$request->editUnRaw,
                'Status' => $request->editStatusPriceRaw, 
                'FinalCost' => $request->editFinalCostRaw, 
                'UpdatedBy' => Auth::user()->name,
            ]);
            if ($editRawMaterial){
                COGSHeader::where('ID',$COGSID)->Update([
                    'UpdatedBy' => Auth::user()->name,
                ]);
                return redirect('cogs-form-cogs/'.$COGSID.'/editRawMaterial');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function DeleteRawMaterial(Request $request){
        try{ 
            $ID = $request->deleteIDRaw;
            $COGSID = $request->deleteCOGSIDRaw;
            $deleteRawMaterial = COGSRawMaterial::where('ID',$ID)->delete();
            if($deleteRawMaterial){
                COGSHeader::where('ID',$COGSID)->Update([
                    'UpdatedBy' => Auth::user()->name,
                ]);
                return redirect('cogs-form-cogs/'.$COGSID.'/deleteRawMaterial');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    
    public function SearchComponentPir(Request $request){
        $ListComponentPIR = [];
        $ComponentPir = Pir::select('MaterialDescription')->groupBy('MaterialDescription')->get();
        foreach ($ComponentPir as $item){
            array_push($ListComponentPIR,$item->MaterialDescription);
        }
        return response()->json([
                'ListComponentPIR' => $ListComponentPIR,
            ], 200);
    }

    public function GetDataComponentPir(Request $request){
        $DataComponent = Pir::select('*')->where('MaterialDescription',$request->materialdescription)->orderBy('PIRDate','DESC')->first();
        return response()->json([
                'DataComponent' => $DataComponent,
            ], 200);
    }


    // public function GetSFComponent($COGSID){
    //     try{ 
    //         $COGSHeader = COGSHeader::select('*')->where('ID',$COGSID)->first();
    //         $PNReference = $COGSHeader->PNReference;
    //         $DataSFComponent = (vwSFComponent::select('*')->where('Material',$PNReference)->orderBy('ComponentNumber','ASC')->get())->toArray();
    //         if (empty($DataSFComponent)){
    //             return redirect('cogs-form-cogs/'.$COGSID.'/getZeroSFComponent');
    //         }else{
    //             COGSSFComponent::where('COGSID',$COGSID)->delete();
    //             $DataSFComponentFiltered = $this->FilterComponent($DataSFComponent);
    //             $Kurs = Kurs::all()->toArray();
    //             foreach($DataSFComponentFiltered as $item){
    //                 $TotalPrice = 0;
    //                 foreach($Kurs as $KursItem){
    //                     if ($KursItem['MataUang'] == $item['Currency']){
    //                         $TotalPrice = $item['NetPrice'] * $KursItem['KursTengah'];
    //                         break;
    //                 }else{
    //                         continue;
    //                 }
    //                 }
    //                 $FinalPrice = $TotalPrice * (float)$item['QtyBOM'];
    //                 COGSSFComponent::Create([
    //                     'COGSID' =>$COGSID,
    //                     'Component' => $item['ComponentNumber'],
    //                     'Description' => $item['ObjectDescription'],
    //                     'Category' => $item['ComponentCategory'],
    //                     'Price' => $item['NetPrice'], 
    //                     'Currency' => $item['Currency'], 
    //                     'Qty' => $item['QtyBOM'], 
    //                     'Un' => $item['Un'], 
    //                     'Tax' => 0,
    //                     'TotalPrice' =>$FinalPrice,
    //                     'LastTransaction' => $item['PIRDate'], 
    //                     'ManualAdjustment' => 1,
    //                     'FinalPrice' => $FinalPrice,
    //                     'CreatedBy' => Auth::user()->name, 
    //                     'UpdatedBy' => Auth::user()->name,
    //                 ]);
    //             }
    //             COGSHeader::where('ID',$COGSID)->Update([
    //                     'UpdatedBy' => Auth::user()->name,
    //             ]);
    //         }
    //         return redirect('cogs-form-cogs/'.$COGSID.'/getSFComponent');
    //     } catch (Exception $e) {    
    //         $this->ErrorLog($e);
    //         return response()->json(['message' => 'Error Request, Exception Error '], 401);     
    //     }
    // }

    public function GetSFComponent($COGSID){
        try{ 
            $COGSHeader = COGSHeader::select('*')->where('ID',$COGSID)->first();
            $PNReference = $COGSHeader->PNReference;
            // dd($PNReference);
            $DataSFComponent = (vwSFComponent::select('*')->where('Material',$PNReference)->orderBy('ComponentNumber','ASC')->get())->toArray();
            // $DataSFComponent = vwSFComponent::where('Material',$PNReference)->get()->toArray();
            // $DataSFComponent = Bom::where('Material',$PNReference)
            //                     ->where('ComponentCategory', '!=', 'RAW MATERIAL')
            //                     ->where('ComponentCategory', '!=', 'CONSUMABLE')
            //                     ->where('ComponentCategory', '!=', 'CONSUMBLE PARTS')
            //                     ->where('ComponentCategory', '!=', 'CONSUMABLE PARTS')
            //                     ->get()->toArray();
            // $DataPO = VwDataPOCogs::where('MaterialNumber',$PNReference)->get()->toArray();
            // $merged_array = array_merge($DataSFComponent, $DataPO);
            // dd($DataSFComponent);
            if (empty($DataSFComponent)){
                return redirect('cogs-form-cogs/'.$COGSID.'/getZeroSFComponent');
            }else{
                COGSSFComponent::where('COGSID',$COGSID)->delete();
                $DataSFComponentFiltered = $this->FilterComponent($DataSFComponent);
                $Kurs = Kurs::all()->toArray();
                // dd($DataSFComponentFiltered);
                // $DataPO = VwDataPOCogs::whereIn('MaterialNumber', $DataSFComponentFiltered)->get()->toArray();
                // dd($DataPO);
                // $price = [];
                foreach($DataSFComponentFiltered as $item){
                    // dd($item['ComponentNumber']);
                    $DataPO = VwDataPOCogs::where('MaterialNumber',trim($item['ComponentNumber']))->first();
                    // dd($DataPO['Price']);
                    // $DataPO = VwDataPOCogs::get()->toArray();
                    if(empty($DataPO)){
    
                        $price = $item['NetPrice'];
                        $curr = $item['Currency'];
                        $datepo = $item['PIRDate'];
                    }else{
    
                            $price = $DataPO['Price'];
                            $curr = $DataPO['Currency'];
                            $datepo = $DataPO['PODate'];
                    }
                    // dd($price);
                    $TotalPrice = 0;
                    foreach($Kurs as $KursItem){
                        if ($KursItem['MataUang'] == $curr){
                            $TotalPrice =$price * $KursItem['KursTengah'];
                            break;
                    }else{
                            continue;
                    }
                    }
                    $FinalPrice = $TotalPrice * (float)$item['QtyBOM'];
                    COGSSFComponent::Create([
                        'COGSID' =>$COGSID,
                        'Component' => $item['ComponentNumber'],
                        'Description' => $item['ObjectDescription'],
                        'Category' => $item['ComponentCategory'],
                        'Price' =>$price, 
                        'Currency' => $curr, 
                        'Qty' => $item['QtyBOM'], 
                        'Un' => $item['Un'], 
                        'Tax' => 0,
                        'TotalPrice' =>$FinalPrice,
                        'LastTransaction' => $datepo, 
                        'ManualAdjustment' => 1,
                        'FinalPrice' => $FinalPrice,
                        'CreatedBy' => Auth::user()->name, 
                        'UpdatedBy' => Auth::user()->name,
                    ]);
                }
                COGSHeader::where('ID',$COGSID)->Update([
                        'UpdatedBy' => Auth::user()->name,
                ]);
            }
            return redirect('cogs-form-cogs/'.$COGSID.'/getSFComponent');
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function SearchSFComponent(Request $request){
        return response()->json([
                'SFComponent' => COGSSFComponent::select('*')->where('ID',$request->id)->first(),
            ], 200);
    }

    public function EditSFComponent(Request $request){
        try{ 
            $COGSID = $request->editCOGSID;
            $ID = $request->editID;
            $editSFComponent = COGSSFComponent::where('ID',$ID)->update([
                'Component' => $request->editComponent,
                'Description' => $request->editDescription,
                'Category' =>  $request->editCategory,
                'Price' =>  $request->editPrice, 
                'Currency' =>  $request->editCurrency, 
                'Qty' =>  $request->editQty, 
                'Un' =>  $request->editUn, 
                'Tax' => $request->editTax,
                'TotalPrice' => $request->editTotalPrice,
                'LastTransaction' =>  $request->editLastTransaction, 
                'ManualAdjustment' => $request->editManualAdjustment,
                'FinalPrice' =>  $request->editFinalPrice,
                'UpdatedBy' => Auth::user()->name,
            ]);
            if ($editSFComponent){
                COGSHeader::where('ID',$COGSID)->Update([
                    'UpdatedBy' => Auth::user()->name,
                ]);
                return redirect('cogs-form-cogs/'.$COGSID.'/editSFComponent');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function AddSFComponent(Request $request){
        try{ 
            $COGSID = $request->addCOGSID;
            $addSFComponent = COGSSFComponent::Create([
                'COGSID' =>$COGSID,
                'Component' => $request->addComponent,
                'Description' => $request->addDescription,
                'Category' => $request->addCategory,
                'Price' => $request->addPrice, 
                'Currency' => $request->addOptionCurrency, 
                'Qty' => $request->addQty, 
                'Un' => $request->addUn, 
                'Tax' => $request->addTax,
                'TotalPrice' =>$request->addTotalPrice,
                'LastTransaction' => $request->addLastTransaction, 
                'ManualAdjustment' => $request->addManualAdjustment,
                'FinalPrice' => $request->addFinalPrice,
                'CreatedBy' => Auth::user()->name, 
                'UpdatedBy' => Auth::user()->name,
            ]);
            if ($addSFComponent){
                COGSHeader::where('ID',$COGSID)->Update([
                    'UpdatedBy' => Auth::user()->name,
                ]);
                return redirect('cogs-form-cogs/'.$COGSID.'/addSFComponent');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function DeleteSFComponent(Request $request){
        try{ 
            $ID = $request->deleteID;
            $COGSID = $request->deleteCOGSID;
            $deleteSFComponent = COGSSFComponent::where('ID',$ID)->delete();
            if($deleteSFComponent){
                COGSHeader::where('ID',$COGSID)->Update([
                    'UpdatedBy' => Auth::user()->name,
                ]);
                return redirect('cogs-form-cogs/'.$COGSID.'/deleteSFComponent');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    // public function GetConsumables($COGSID){
    //     try{ 
    //         $COGSHeader = COGSHeader::select('*')->where('ID',$COGSID)->first();
    //         $PNReference = $COGSHeader->PNReference;
    //         $DataConsumables = (vwConsumables::select('*')->where('Material',$PNReference)->orderBy('ComponentNumber','ASC')->get())->toArray();
    //         if (empty($DataConsumables)){
    //             return redirect('cogs-form-cogs/'.$COGSID.'/getZeroConsumables');
    //         }else{       
    //             COGSConsumables::where('COGSID',$COGSID)->delete();
    //             $DataConsumablesFiltered = $this->FilterComponent($DataConsumables);
    //             $Kurs = Kurs::all()->toArray();
    //             foreach($DataConsumablesFiltered as $item){
    //                 $TotalPrice = 0;
    //                 foreach($Kurs as $KursItem){
    //                     if ($KursItem['MataUang'] == $item['Currency']){
    //                         $TotalPrice = $item['NetPrice'] * $KursItem['KursTengah'];
    //                         break;
    //                 }else{
    //                         continue;
    //                 }
    //                 }
    //                 $FinalPrice = $TotalPrice * (float)$item['QtyBOM'];
    //                 COGSConsumables::Create([
    //                     'COGSID' =>$COGSID,
    //                     'Component' => $item['ComponentNumber'],
    //                     'Description' => $item['ObjectDescription'],
    //                     'Category' => $item['ComponentCategory'],
    //                     'Price' => $item['NetPrice'], 
    //                     'Currency' => $item['Currency'], 
    //                     'Qty' => $item['QtyBOM'], 
    //                     'Un' => $item['Un'], 
    //                     'Tax' => 0,
    //                     'TotalPrice' =>$FinalPrice,
    //                     'LastTransaction' => $item['PIRDate'], 
    //                     'ManualAdjustment' => 1,
    //                     'FinalPrice' => $FinalPrice,
    //                     'CreatedBy' => Auth::user()->name, 
    //                     'UpdatedBy' => Auth::user()->name,
    //                 ]);
    //             }
    //             COGSHeader::where('ID',$COGSID)->Update([
    //                     'UpdatedBy' => Auth::user()->name,
    //             ]);
    //         }
    //         return redirect('cogs-form-cogs/'.$COGSID.'/getConsumables');
    //     } catch (Exception $e) {    
    //         $this->ErrorLog($e);
    //         return response()->json(['message' => 'Error Request, Exception Error '], 401);     
    //     }
    // }

    public function GetConsumables($COGSID){
        try{ 
            $COGSHeader = COGSHeader::select('*')->where('ID',$COGSID)->first();
            $PNReference = $COGSHeader->PNReference;
            $DataConsumables = (vwConsumables::select('*')->where('Material',$PNReference)->orderBy('ComponentNumber','ASC')->get())->toArray();
            // $DataConsumables = Bom::where('Material',$PNReference)
            //                     ->where('ComponentCategory','CONSUMABLE PARTS')
            //                     ->where('ComponentCategory','CONSUMABLE')
            //                     ->get()->toArray();
            if (empty($DataConsumables)){
                return redirect('cogs-form-cogs/'.$COGSID.'/getZeroConsumables');
            }else{       
                COGSConsumables::where('COGSID',$COGSID)->delete();
                $DataConsumablesFiltered = $this->FilterComponentConsumable($DataConsumables);
                // dd( $DataConsumablesFiltered);
                if (empty($DataConsumablesFiltered)){
                    return redirect('cogs-form-cogs/'.$COGSID.'/getZeroConsumables');
                }
                // dd( $DataConsumablesFiltered );
                $Kurs = Kurs::all()->toArray();
                foreach($DataConsumablesFiltered as $item){
                    // dd($item['ComponentNumber']);
                    $DataPO2 = VwDataPOCogs::where('MaterialNumber',trim($item['ComponentNumber']))->first();
                    // dd($DataPO['Price']);
                    // $DataPO = VwDataPOCogs::get()->toArray();
                    $datepo = $DataPO2['PODate'];
                    // dd( $datepo);
                    if(empty($DataPO2)){
    
                        $price = $item['NetPrice'];
                        $curr = $item['Currency'];
                    }else{
    
                        $price = $DataPO2['Price'];
                        $curr = $DataPO2['Currency'];
                    }
                    $TotalPrice = 0;
                    foreach($Kurs as $KursItem){
                        if ($KursItem['MataUang'] == $curr){
                            $TotalPrice = $price * $KursItem['KursTengah'];
                            break;
                    }else{
                            continue;
                    }
                    }
                    $FinalPrice = $TotalPrice * (float)$item['QtyBOM'];
                    COGSConsumables::Create([
                        'COGSID' =>$COGSID,
                        'Component' => $item['ComponentNumber'],
                        'Description' => $item['ObjectDescription'],
                        'Category' => $item['ComponentCategory'],
                        'Price' => $price, 
                        'Currency' => $curr, 
                        'Qty' => $item['QtyBOM'], 
                        'Un' => $item['Un'], 
                        'Tax' => 0,
                        'TotalPrice' =>$FinalPrice,
                        'LastTransaction' => $datepo, 
                        'ManualAdjustment' => 1,
                        'FinalPrice' => $FinalPrice,
                        'CreatedBy' => Auth::user()->name, 
                        'UpdatedBy' => Auth::user()->name,
                    ]);
                    
                    
                }
                COGSHeader::where('ID',$COGSID)->Update([
                        'UpdatedBy' => Auth::user()->name,
                ]);
            }
            return redirect('cogs-form-cogs/'.$COGSID.'/getConsumables');
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function FilterComponentConsumable($DataComponent){
        try{ 
            $tempPIRDate = [];
            $newDataComponent = [];
            $lenDataComponent = count($DataComponent);
            $DataComponent[$lenDataComponent]['ComponentNumber'] = "";
            // $DataComponent[$lenDataComponent]['PIRDate'] = "";
            $DataComponent[$lenDataComponent]['NetPrice'] = 0;
            $DataComponent[$lenDataComponent]['Currency'] = "";
            $DataComponent[$lenDataComponent]['VendorName'] = "";
            // var_dump($PNReference);echo '<br><br>';
            // dd("1");
            foreach ($DataComponent as $i => $row){
                // var_dump($i);
                if(!isset($row['Material'])){
                    break;
                }
                // dd($row);
                $newItem  = [];
                $newItem['Material'] = $row['Material'];
                $newItem['Description'] = $row['Description'];
                $newItem['ComponentCategory'] = $row['ComponentCategory'];
                $newItem['ComponentNumber'] = $row['ComponentNumber'];
                $newItem['ObjectDescription'] = $row['ObjectDescription'];
                $newItem['QtyBOM'] = $row['QtyBOM'];
                $newItem['Un'] = $row['Un'];
                $newItem['PurchasingInfoRecord'] = $row['PurchasingInfoRecord'];
                // $newItem['PIRDate'] = $row['PIRDate'];
                $newItem['Vendor'] = $row['Vendor'];
                $newItem['VendorName'] = $row['VendorName'];
                $newItem['NetPrice'] = $row['NetPrice'];
                $newItem['Currency'] = $row['Currency'];
                
                $currDataComponentComponentNumber = $newItem['ComponentNumber'];
                $nextDataComponentComponentNumber = $DataComponent[$i+1]['ComponentNumber'];
                // $currDataComponentPIRDate = $newItem['PIRDate'];
                // $nextDataComponentPIRDate = $DataComponent[$i+1]['PIRDate'];
                $currDataComponentNetPrice = $newItem['NetPrice'];
                $nextDataComponentNetPrice = $DataComponent[$i+1]['NetPrice'];
                $currDataComponentCurrency = $newItem['Currency'];
                $nextDataComponentCurrency = $DataComponent[$i+1]['Currency'];
                $currDataVendorName = $newItem['VendorName'];
                $nextDataVendorName = $DataComponent[$i+1]['VendorName'];

                // echo '<br>';var_dump($currDataComponentComponentNumber);var_dump($nextDataComponentComponentNumber);
                $highestPIRDate = "";
                $highestNetPrice = 0;
                $highestCurrency = "";
                $highestVendorName  = "";
                if ($nextDataComponentComponentNumber == $currDataComponentComponentNumber){//JIKA SAMA
                    // echo "SAMA ";
                    $a = [ 'NetPrice' => $currDataComponentNetPrice, 'Currency' => $currDataComponentCurrency, 'VendorName' => $currDataVendorName];
                    $b = [ 'NetPrice' => $nextDataComponentNetPrice, 'Currency' => $nextDataComponentCurrency, 'VendorName' => $nextDataVendorName];
                    array_push($tempPIRDate, $a, $b);
                    // dd($tempPIRDate);
                }
                else{// JIKA BEDA
                    // var_dump($currDataComponentPIRDate);var_dump($currDataComponentNetPrice);echo "BEDA ";echo '<br>';
                    //PIRDATE TERUPDATE
                    
                    foreach($tempPIRDate as $i => $tempPIRDateData){
                        if ($i == 0){
                            // $highestPIRDate = $tempPIRDate[$i]['PIRDate'];
                            $highestNetPrice = $tempPIRDate[$i]['NetPrice'];
                            $highestCurrency = $tempPIRDate[$i]['Currency'];
                            $highestVendorName = $tempPIRDate[$i]['VendorName'];
                        
                        }
                        // else{
                        //     if ( $highestPIRDate < $tempPIRDate[$i]['PIRDate']){
                        //         $highestPIRDate = $tempPIRDate[$i]['PIRDate'];
                        //         $highestNetPrice = $tempPIRDate[$i]['NetPrice'];
                        //         $highestCurrency = $tempPIRDate[$i]['Currency'];
                        //         $highestVendorName = $tempPIRDate[$i]['VendorName'];
                        //     }
                        // }
                        // var_dump($i);var_dump($tempPIRDateData);echo '<br>';
                    }
                    //JIKA NET PRICE 0
                    if ($highestNetPrice == 0){
                        // var_dump('XXX');echo '<br>';
                        foreach($tempPIRDate as $i => $tempPIRDateData){
                            if ($i == 0){
                            // $highestPIRDate = $tempPIRDate[$i]['PIRDate'];
                            $highestNetPrice = $tempPIRDate[$i]['NetPrice'];
                            $highestCurrency = $tempPIRDate[$i]['Currency'];
                            $highestVendorName = $tempPIRDate[$i]['VendorName'];
                            }
                            // else{
                            //     if ( $highestNetPrice < $tempPIRDate[$i]['NetPrice']){
                            //         $highestPIRDate = $tempPIRDate[$i]['PIRDate'];
                            //         $highestNetPrice = $tempPIRDate[$i]['NetPrice'];
                            //         $highestCurrency = $tempPIRDate[$i]['Currency'];
                            //         $highestVendorName = $tempPIRDate[$i]['VendorName'];
                            //     }
                            // }
                        }
                    }
                    // $newItem['PIRDate'] = $highestPIRDate; 
                    $newItem['NetPrice'] = $highestNetPrice;
                    $newItem['Currency'] = $highestCurrency;
                    $newItem['VendorName'] = $highestVendorName;
                    $tempPIRDate = array();
                    // var_dump( $newItem['PurchasingInfoRecord']); var_dump($newItem['ComponentNumber']);var_dump($newItem['PIRDate']);var_dump( $newItem['NetPrice']);var_dump( $newItem['Currency']);var_dump( $newItem['VendorName']);echo '<br>';
                    // if ($newItem['PIRDate'] == ""){
                        // $newItem['PIRDate'] = (vwSFComponent::select('PIRDate')->where('Material',$newItem['Material'])->where('ComponentNumber',$newItem['ComponentNumber'])->first())->PIRDate;
                        // $newItem['NetPrice'] = (vwSFComponent::select('NetPrice')->where('Material',$newItem['Material'])->where('ComponentNumber',$newItem['ComponentNumber'])->first())->NetPrice;
                        // $newItem['Currency'] = (vwSFComponent::select('Currency')->where('PurchasingInfoRecord',$newItem['PurchasingInfoRecord'])->first())->Currency;
                        // $newItem['VendorName'] = (vwSFComponent::select('VendorName')->where('Material',$newItem['Material'])->where('ComponentNumber',$newItem['ComponentNumber'])->first())->VendorName;
                    // }
                    array_push($newDataComponent, $newItem);
                    // dd($newItem);
                    continue;
                }
                // dd("12");
                // dd($newDataComponent);
            }

            // dd($newDataComponent);
            
            return $newDataComponent;
        } catch (Exception $e) {  
            
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function SearchConsumables(Request $request){
        return response()->json([
                'Consumables' => COGSConsumables::select('*')->where('ID',$request->id)->first(),
            ], 200);
    }

    public function EditConsumables(Request $request){
        try{ 
            $COGSID = $request->editCOGSIDConsumables;
            $ID = $request->editIDConsumables;
            $editConsumables = COGSConsumables::where('ID',$ID)->update([
                'Component' => $request->editComponentConsumables,
                'Description' => $request->editDescriptionConsumables,
                'Category' =>  $request->editCategoryConsumables,
                'Price' =>  $request->editPriceConsumables, 
                'Currency' =>  $request->editCurrencyConsumables, 
                'Qty' =>  $request->editQtyConsumables, 
                'Un' =>  $request->editUnConsumables, 
                'Tax' => $request->editTaxConsumables,
                'TotalPrice' => $request->editTotalPriceConsumables,
                'LastTransaction' =>  $request->editLastTransactionConsumables, 
                'ManualAdjustment' => $request->editManualAdjustmentConsumables,
                'FinalPrice' =>  $request->editFinalPriceConsumables,
                'UpdatedBy' => Auth::user()->name,
            ]);
            if ($editConsumables){
                COGSHeader::where('ID',$COGSID)->Update([
                    'UpdatedBy' => Auth::user()->name,
                ]);
                return redirect('cogs-form-cogs/'.$COGSID.'/editConsumables');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function AddConsumables (Request $request){
        try{ 
            $COGSID = $request->addCOGSIDConsumables;
            $addConsumables = COGSConsumables::Create([
                'COGSID' =>$COGSID,
                'Component' => $request->addComponentConsumables,
                'Description' => $request->addDescriptionConsumables,
                'Category' => $request->addCategoryConsumables,
                'Price' => $request->addPriceConsumables, 
                'Currency' => $request->addOptionCurrencyConsumables, 
                'Qty' => $request->addQtyConsumables, 
                'Un' => $request->addUnConsumables, 
                'Tax' => $request->addTaxConsumables,
                'TotalPrice' =>$request->addTotalPriceConsumables,
                'LastTransaction' => $request->addLastTransactionConsumables, 
                'ManualAdjustment' => $request->addManualAdjustmentConsumables,
                'FinalPrice' => $request->addFinalPriceConsumables,
                'CreatedBy' => Auth::user()->name, 
                'UpdatedBy' => Auth::user()->name,
            ]);
            if ($addConsumables){
                COGSHeader::where('ID',$COGSID)->Update([
                    'UpdatedBy' => Auth::user()->name,
                ]);
                return redirect('cogs-form-cogs/'.$COGSID.'/addConsumables');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function DeleteConsumables(Request $request){
        try{ 
            $ID = $request->deleteIDConsumables;
            $COGSID = $request->deleteCOGSIDConsumables;
            $deleteConsumables = COGSConsumables::where('ID',$ID)->delete();
            if($deleteConsumables){
                COGSHeader::where('ID',$COGSID)->Update([
                    'UpdatedBy' => Auth::user()->name,
                ]);
                return redirect('cogs-form-cogs/'.$COGSID.'/deleteConsumables');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function SearchMasterProcess(Request $request){
        $PNReference = $request->pnreference;
        $MPTName = vwProcess::select('MPTName')->where('PartNumber',$PNReference)->distinct()->orderBy('MPTName','ASC')->get();
        if (isset($request->mptnameselected)){
            $MPTNameSelected = $request->mptnameselected;
            $MasterProcess = vwProcess::select('*')->where('MPTName',$MPTNameSelected)->where('PartNumber',$PNReference)->orderBy('ProcessOrder','ASC')->get();
        }
        else{
            $MPTNameSelected = "";
            $MasterProcess = array();
        }
        return response()->json([
                'MPTName' => $MPTName,
                'MPTNameSelected' => $MPTNameSelected,
                'MasterProcess' => $MasterProcess,
        ], 200);
    }

    public function GetMasterProcess(Request $request){
        try{ 
            $COGSID = $request->COGSIDProcess;
            $PNReference = $request->PNReferenceProcess;
            $MPTName = $request->MPTName;
            $RateMH = $request->RateMH;
            $DataMasterprocess = (vwProcessGroup::select('*')->where('MPTName',$MPTName)->where('PartNumber',$PNReference)->orderBy('ProcessGroup','ASC')->get())->toArray();
            if (empty($DataMasterprocess)){
                return redirect('cogs-form-cogs/'.$COGSID.'/getZeroMasterProcess');
            }else{       
                COGSProcess::where('COGSID',$COGSID)->delete();
                foreach($DataMasterprocess as $itemDataMasterprocess){
                    $TotalMH = $itemDataMasterprocess['ManHour'];// * $itemDataMasterprocess['ManPower'];
                    COGSProcess::Create([
                        'COGSID' =>$COGSID,
                        'Process' => $itemDataMasterprocess['ProcessGroup'],
                        'Um' => 'Hours',
                        'Hours' => $TotalMH,
                        'Cost' => $TotalMH * $RateMH, 
                        'RateManhour' => $RateMH,
                        'CreatedBy' => Auth::user()->name, 
                        'UpdatedBy' => Auth::user()->name,
                    ]);
                }
                COGSHeader::where('ID',$COGSID)->Update([
                        'UpdatedBy' => Auth::user()->name,
                ]);
            }
            return redirect('cogs-form-cogs/'.$COGSID.'/getMasterProcess');
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function AddProcess(Request $request){
        try{ 
            $COGSID = $request->addCOGSIDProcess;
            $addProcess = COGSProcess::Create([
                'COGSID' =>$COGSID,
                'Process' => $request->addProcessProcess,
                'Um' => $request->addUmProcess,
                'Hours' => $request->addHoursProcess,
                'Cost' => $request->addCostProcess, 
                'RateManhour' => $request->addRateMHProcess,
                'CreatedBy' => Auth::user()->name, 
                'UpdatedBy' => Auth::user()->name,
            ]);
            if ($addProcess){
                COGSHeader::where('ID',$COGSID)->Update([
                    'UpdatedBy' => Auth::user()->name,
                ]);
                return redirect('cogs-form-cogs/'.$COGSID.'/addProcess');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function SearchProcess(Request $request){
        return response()->json([
                'Process' => COGSProcess::select('*')->where('ID',$request->id)->first(),
            ], 200);
    }

    public function EditProcess(Request $request){
        try{ 
            $COGSID = $request->editCOGSIDProcess;
            $ID = $request->editIDProcess;
            $editProcess = COGSProcess::where('ID',$ID)->update([
                'Process' => $request->editProcessProcess,
                'Um' => $request->editUmProcess,
                'Hours' => $request->editHoursProcess,
                'Cost' => $request->editCostProcess, 
                'RateManhour' => $request->editRateMHProcess,
                'UpdatedBy' => Auth::user()->name,
            ]);
            if ($editProcess){
                COGSHeader::where('ID',$COGSID)->Update([
                    'UpdatedBy' => Auth::user()->name,
                ]);
                return redirect('cogs-form-cogs/'.$COGSID.'/editProcess');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function DeleteProcess(Request $request){
        try{ 
            $ID = $request->deleteIDProcess;
            $COGSID = $request->deleteCOGSIDProcess;
            $deleteProcess = COGSProcess::where('ID',$ID)->delete();
            if($deleteProcess){
                COGSHeader::where('ID',$COGSID)->Update([
                    'UpdatedBy' => Auth::user()->name,
                ]);
                return redirect('cogs-form-cogs/'.$COGSID.'/deleteProcess');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function AddOthers(Request $request){
        try{ 
            $COGSID = $request->addCOGSIDOthers;
            $addOthers = COGSOthers::Create([
                'COGSID' =>$COGSID,
                'PartNumber' => $request->addPartNumberOthers,
                'Description' => $request->addDescriptionOthers,
                'Price' => $request->addPriceOthers, 
                'Currency' => $request->addOptionCurrencyOthers, 
                'Tax' => $request->addTaxOthers,
                'Qty' => $request->addQtyOthers, 
                'Un' => $request->addUnOthers, 
                'TotalPrice' =>$request->addTotalPriceOthers,
                'CreatedBy' => Auth::user()->name, 
                'UpdatedBy' => Auth::user()->name,
            ]);
            if ($addOthers){
                COGSHeader::where('ID',$COGSID)->Update([
                    'UpdatedBy' => Auth::user()->name,
                ]);
                return redirect('cogs-form-cogs/'.$COGSID.'/addOthers');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function SearchOthers(Request $request){
        return response()->json([
                'Others' => COGSOthers::select('*')->where('ID',$request->id)->first(),
            ], 200);
    }

    public function EditOthers(Request $request){
        try{ 
            $COGSID = $request->editCOGSIDOthers;
            $ID = $request->editIDOthers;
            $editOthers = COGSOthers::where('ID',$ID)->update([
                'COGSID' =>$COGSID,
                'PartNumber' => $request->editPartNumberOthers,
                'Description' => $request->editDescriptionOthers,
                'Price' => $request->editPriceOthers, 
                'Currency' => $request->editOptionCurrencyOthers, 
                'Tax' => $request->editTaxOthers,
                'Qty' => $request->editQtyOthers, 
                'Un' => $request->editUnOthers, 
                'TotalPrice' =>$request->editTotalPriceOthers,
                'UpdatedBy' => Auth::user()->name,
            ]);
            if ($editOthers){
                COGSHeader::where('ID',$COGSID)->Update([
                    'UpdatedBy' => Auth::user()->name,
                ]);
                return redirect('cogs-form-cogs/'.$COGSID.'/editOthers');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function DeleteOthers(Request $request){
        try{ 
            $ID = $request->deleteIDOthers;
            $COGSID = $request->deleteCOGSIDOthers;
            $deleteOthers = COGSOthers::where('ID',$ID)->delete();
            if($deleteOthers){
                COGSHeader::where('ID',$COGSID)->Update([
                    'UpdatedBy' => Auth::user()->name,
                ]);
                return redirect('cogs-form-cogs/'.$COGSID.'/deleteOthers');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function UpdateCalculateHeader(Request $request){
        try{ 
            $COGSID = $request->COGSID;
            $GrossProfit = ($request->GrossProfit);
            $QuotationPrice = ($request->QuotationPrice);
            if ($GrossProfit == "Infinity" OR $QuotationPrice == "Infinity" OR str_contains($GrossProfit, '-') OR  str_contains($QuotationPrice, '-') OR $request->TotalCOGS < 0){
                return redirect('cogs-form-cogs/'.$COGSID.'/updateCalculationFailed');
            }
            $update = COGSHeader::where('ID',$request->COGSID)->update([
                'CalculatedBy' => $request->CalculatedBy,
                'GrossProfit' => empty($GrossProfit) ? 0 :  $GrossProfit,
                'QuotationPrice' => $QuotationPrice,
                'UpdatedBy' => Auth::user()->name,
            ]);
            if ($update){
                return redirect('cogs-form-cogs/'.$COGSID.'/updateCalculationSuccess');
            }else{
                return redirect('cogs-form-cogs/'.$COGSID.'/updateCalculationFailed');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function SearchCalculateHeader(Request $request){
         return response()->json([
                'Header' => COGSHeader::select('*')->where('ID',$request->cogsid)->first(),
            ], 200);
    }

    public function COGSPDF(Request $request){
        try{ 
            $ArraySFComponent = str_replace(array("[","]","\""), "", $request->ArraySFComponent);
            $ArraySFComponent = explode(",",$ArraySFComponent);
            $intArraySFComponent = array_map('intval', $ArraySFComponent);

            $ArrayConsumables = str_replace(array("[","]","\""), "", $request->ArrayConsumables);
            $ArrayConsumables = explode(",",$ArrayConsumables);
            $intArrayConsumables = array_map('intval', $ArrayConsumables);

            $COGSID = (int)$request->COGSIDPDF;
            $Take = 10;

            //RawMaterial
            $DataRawMaterial = COGSRawMaterial::select('*')->where('COGSID',$COGSID)->get();
            $TotalWeightMaterial = 0;
            if ($DataRawMaterial){
                foreach($DataRawMaterial as $item){
                    $TotalWeightMaterial += $item->Weight;
                }
            }else{
                $TotalWeightMaterial = "";
            }
            
            //SF Component
            $CountDataSFComponent = count(COGSSFComponent::select('*')->where('COGSID',$COGSID)->whereIn('ID', $intArraySFComponent)->get());
            if ($CountDataSFComponent >= 10){
                $LastDataSFComponent =  COGSSFComponent::select('*')->where('COGSID',$COGSID)->whereIn('ID', $intArraySFComponent)->orderBy('FinalPrice','DESC')->skip($Take)->take($CountDataSFComponent-$Take)->get();
                $SumLastDataSFComponent = 0;
                foreach($LastDataSFComponent as $item){
                    $SumLastDataSFComponent += $item->FinalPrice;
                }
            }else{
                $SumLastDataSFComponent = 0;
            }
            
            //Consumables
            $CountDataConsumables = count(COGSConsumables::select('*')->where('COGSID',$COGSID)->whereIn('ID', $intArrayConsumables)->get());
            if ($CountDataConsumables >= 10){
                $LastDataConsumables =  COGSConsumables::select('*')->where('COGSID',$COGSID)->whereIn('ID', $intArrayConsumables)->orderBy('FinalPrice','DESC')->skip($Take)->take($CountDataConsumables-$Take)->get();
                $SumLastDataConsumables = 0;
                foreach($LastDataConsumables as $item){
                    $SumLastDataConsumables += $item->FinalPrice;
                }
            }else{
                $SumLastDataConsumables = 0;
            }
            
            //DirectMaterial
            $TotalRawMaterial = (COGSHeader::select('TotalRawMaterial')->where('ID',$COGSID)->first())->TotalRawMaterial;
            $TotalSFComponent = (COGSHeader::select('TotalSFComponent')->where('ID',$COGSID)->first())->TotalSFComponent;
            $TotalConsumables = (COGSHeader::select('TotalConsumables')->where('ID',$COGSID)->first())->TotalConsumables;
            $TotalOthers = (COGSHeader::select('TotalOthers')->where('ID',$COGSID)->first())->TotalOthers;
            $TotalDirectMaterial = $TotalRawMaterial + $TotalSFComponent + $TotalConsumables + $TotalOthers;
        
            //Process
            $DataCOGSMasterProcess = COGSProcess::select('*')->where('COGSID',$COGSID)->orderBy('Process','ASC')->get();
            $TotalHours = 0;
            $TotalCostProcess = 0;
            if($DataCOGSMasterProcess){
                foreach($DataCOGSMasterProcess as $item){
                    $TotalHours += $item->Hours;
                    $TotalCostProcess += $item->Cost;
                }
            }   
            
            $data = array(
                'DataHeader' => COGSHeader::select('*','cogs_header.ID AS COGSID')->where('cogs_header.ID',$COGSID)->first(),
                'DataRawMaterialPrice' => RawMaterialPrice::all(),
                'DataRawMaterial' => $DataRawMaterial,
                'TotalWeightMaterial' => $TotalWeightMaterial,
                'TopDataSFComponent' => COGSSFComponent::select('*')->where('COGSID',$COGSID)->whereIn('ID', $intArraySFComponent)->orderBy('FinalPrice','DESC')->take($Take)->get(),
                'SumLastDataSFComponent' => $SumLastDataSFComponent,
                'TopDataConsumables' => COGSConsumables::select('*')->where('COGSID',$COGSID)->whereIn('ID', $intArrayConsumables)->orderBy('FinalPrice','DESC')->take($Take)->get(),
                'SumLastDataConsumables' => $SumLastDataConsumables,
                'DataOthers' => COGSOthers::select('*')->where('COGSID',$COGSID)->get(),
                'TotalDirectMaterial' => $TotalDirectMaterial,
                'DataProcess' => COGSProcess::select('*')->where('COGSID',$COGSID)->get(),
                'TotalHours' => $TotalHours,
                'TotalCostProcess' => $TotalCostProcess,
                'TotalCOGS' => $TotalDirectMaterial + $TotalCostProcess,
                'DataKurs' => Kurs::select('*')->whereIn('MataUang', ["USD", "AUD", "SGD", "JPY", "EUR", "IDR"])->get(),
            );
            return $pdf =  PDF::loadView('cogs/cogspdf', $data)->stream();
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function SaveNewCOGS($COGSID){
        try{ 
            $COGSHeader = COGSHeader::select('*')->where('ID',$COGSID)->first();
            $COGSRawMaterial = COGSRawMaterial::select('*')->where('COGSID',$COGSID)->get();
            $COGSSFComponent = COGSSFComponent::select('*')->where('COGSID',$COGSID)->get();
            $COGSConsumables = COGSConsumables::select('*')->where('COGSID',$COGSID)->get();
            $COGSProcess = COGSProcess::select('*')->where('COGSID',$COGSID)->get();
            $COGSOthers = COGSOthers::select('*')->where('COGSID',$COGSID)->get();

            $SaveNewHeaderCOGS = COGSHeader::create([
                    'ProductCategory'=> $COGSHeader->ProductCategory,
                    'PCRID' =>$COGSHeader->PCRID,
                    'CPOID' =>$COGSHeader->CPOID,
                    'CalculationType' => $COGSHeader->CalculationType,
                    'Opportunity' => $COGSHeader->Opportunity,
                    'PNReference' => $COGSHeader->PNReference, 
                    'PNReferenceDesc' => $COGSHeader->PNReferenceDesc, 
                    'PICTriatra' => $COGSHeader->PICTriatra,
                    'CostEstimator' => $COGSHeader->CostEstimator,
                    'MarketingDeptHead' => $COGSHeader->MarketingDeptHead,
                    'SCMDivisionHead' => $COGSHeader->SCMDivisionHead,
                    'CalculatedBy' => $COGSHeader->CalculatedBy,
                    'GrossProfit' => $COGSHeader->GrossProfit,
                    'QuotationPrice' => $COGSHeader->QuotationPrice,
                    'PDF' => $COGSHeader->PDF,
                    'TotalRawMaterial' => $COGSHeader->TotalRawMaterial, 
                    'TotalSFComponent' => $COGSHeader->TotalSFComponent, 
                    'TotalConsumables' => $COGSHeader->TotalConsumables, 
                    'TotalProcess' => $COGSHeader->TotalProcess, 
                    'TotalOthers' => $COGSHeader->TotalOthers,
                    'PEDNumber' => $COGSHeader->PEDNumber,
                    'UnitWeight' => $COGSHeader->UnitWeight,
                    'CreatedBy' => Auth::user()->name, 
                    'UpdatedBy' => Auth::user()->name,
                ]);
                
            $newCOGSID = (COGSHeader::select('*')->orderBy('ID','DESC')->first())->ID;
            foreach ($COGSRawMaterial as $item){
                COGSRawMaterial::Create([
                        'COGSID' => $newCOGSID,
                        'Spesification' => $item->Spesification,
                        'Weight' => $item->Weight,
                        'Price' => $item->Price,
                        'Currency' => $item->Currency,
                        'Status'=> $item->Status,
                        'Un' => $item->Un,
                        'FinalCost' => $item->FinalCost,
                        'CreatedBy' => Auth::user()->name, 
                        'UpdatedBy' => Auth::user()->name,
                    ]);
            }
            foreach ($COGSSFComponent as $item){
                COGSSFComponent::Create([
                            'COGSID' =>$newCOGSID,
                            'Component' => $item->Component,
                            'Description' => $item->Description,
                            'Category' => $item->Category,
                            'Price' => $item->Price, 
                            'Currency' => $item->Currency, 
                            'Qty' => $item->Qty, 
                            'Un' => $item->Un, 
                            'Tax' => $item->Tax,
                            'TotalPrice' =>$item->TotalPrice,
                            'LastTransaction' => $item->LastTransaction, 
                            'ManualAdjustment' => $item->ManualAdjustment,
                            'FinalPrice' => $item->FinalPrice,
                            'CreatedBy' => Auth::user()->name, 
                            'UpdatedBy' => Auth::user()->name,
                        ]);
            }
            foreach ($COGSConsumables as $item){
                COGSConsumables::Create([
                            'COGSID' =>$newCOGSID,
                            'Component' => $item->Component,
                            'Description' => $item->Description,
                            'Category' => $item->Category,
                            'Price' => $item->Price, 
                            'Currency' => $item->Currency, 
                            'Qty' => $item->Qty, 
                            'Un' => $item->Un, 
                            'Tax' => $item->Tax,
                            'TotalPrice' =>$item->TotalPrice,
                            'LastTransaction' => $item->LastTransaction, 
                            'ManualAdjustment' => $item->ManualAdjustment,
                            'FinalPrice' => $item->FinalPrice,
                            'CreatedBy' => Auth::user()->name, 
                            'UpdatedBy' => Auth::user()->name,
                        ]);
            }
            foreach ($COGSProcess as $item){
                COGSProcess::Create([
                        'COGSID' =>$newCOGSID,
                        'Process' => $item->Process,
                        'Um' => $item->Um,
                        'Hours' => $item->Hours,
                        'Cost' => $item->Cost, 
                        'RateManhour' => $item->RateManhour,
                        'CreatedBy' => Auth::user()->name, 
                        'UpdatedBy' => Auth::user()->name,
                    ]);
            }
            foreach ($COGSOthers as $item){
                COGSOthers::Create([
                    'COGSID' =>$newCOGSID,
                    'PartNumber' => $item->PartNumber,
                    'Description' => $item->Description,
                    'Price' => $item->Price, 
                    'Currency' => $item->Currency, 
                    'Tax' => $item->Tax,
                    'Qty' => $item->Qty, 
                    'Un' => $item->Qty, 
                    'TotalPrice' =>$item->TotalPrice,
                    'CreatedBy' => Auth::user()->name, 
                    'UpdatedBy' => Auth::user()->name,
                ]);
            }
            if ($SaveNewHeaderCOGS){
                return redirect('cogs-form-cogs/'.$COGSID.'/successSaveAs');
            }else{
                return redirect('cogs-form-cogs/'.$COGSID.'/failedSaveAs');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }

    }


    function downloadBase64(Request $request) {
       
    $filename = $request->name;
    $filepath = public_path('cogs/attachment'.$filename);
    $base64_encoded_file_data = $request->file;
    
    //"RXhjZXB0aW9uIE1lc3NhZ2U6IDAgQW4gZXJyb3Igb2NjdXJlZCBUaGUgZ2l2ZW4ga2V5IHdhcyBub3QgcHJlc2VudCBpbiB0aGUgZGljdGlvbmFyeS4KCkVycm9yQ29kZTogLTIxNDcyMjA4OTEKSGV4RXJyb3JDb2RlOiAweDgwMDQwMjY1CgpFcnJvckRldGFpbHM6IAoJT3BlcmF0aW9uU3RhdHVzOiAwCglTdWJFcnJvckNvZGU6IC0yMTQ2MjMzMDg4CglQbHVnaW46IAoJCUV4Y2VwdGlvbkZyb21QbHVnaW5FeGVjdXRlOiBjdXN0b21pemF0aW9uXzEuTWFwcGluZ1Byb2R1Y3RTdWJncm91cE9wcHR5CgkJRXhjZXB0aW9uUmV0cmlhYmxlOiBGYWxzZQoJCUV4Y2VwdGlvblNvdXJjZTogUGx1Z2luRXhlY3V0aW9uCgkJT3JpZ2luYWxFeGNlcHRpb246IFBsdWdpbkV4ZWN1dGlvbgoJCVBsdWdpblRyYWNlOiAKCkhlbHBMaW5rOiBodHRwOi8vZ28ubWljcm9zb2Z0LmNvbS9md2xpbmsvP0xpbmtJRD0zOTg1NjMmZXJyb3I9TWljcm9zb2Z0LkNybS5Dcm1FeGNlcHRpb24lM2E4MDA0MDI2NSZjbGllbnQ9cGxhdGZvcm0KClRyYWNlVGV4dDogCglbbWFwcGluZ3N1Ymdyb3Vwb3BwdHk6IGN1c3RvbWl6YXRpb25fMS5NYXBwaW5nUHJvZHVjdFN1Ymdyb3VwT3BwdHldDQoJWzYwM2Y0ZTc4LTU3MTktZWQxMS1iODNmLTAwMGQzYTg1ZjdhMDogY3VzdG9taXphdGlvbl8xLk1hcHBpbmdQcm9kdWN0U3ViZ3JvdXBPcHB0eTogVXBkYXRlIG9mIGtyZV9wcm9kdWN0bGluZWl0ZW1dCgpBY3Rpdml0eSBJZDogOWZjZmY4ZWEtMzcxMC00NTk4LWI0MzYtYTVhMTI5ZGY0OWVi";
    // Prevents run-out-of-memory issue
    if (ob_get_level()) {
        ob_end_clean();
    }
    
	// Decodes encoded data
	$decoded_file_data = base64_decode($base64_encoded_file_data);



	// Writes data to the specified file
	$fpc = file_put_contents($filepath, $decoded_file_data);
    

	header('Expires: 0');
	header('Pragma: public');
	header('Cache-Control: must-revalidate');
	header('Content-Length: ' . filesize($filepath));
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	readfile($filepath);
    
    // Deletes the temp file
        if (file_exists($filepath)) {
            unlink($filepath);	
        }
        return response()->json([
                'Data' => $fpc,
        ], 200);
    }
   
}
