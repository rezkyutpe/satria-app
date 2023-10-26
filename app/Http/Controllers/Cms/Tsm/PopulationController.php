<?php

namespace App\Http\Controllers\Cms\Tsm;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Exception;

use App\Models\Table\Tsm\Population;
use App\Models\Table\Tsm\Area;
use App\Models\Table\Tsm\PopulationKalsel;
use App\Models\Table\Tsm\SN;
use App\Models\View\Tsm\vw_sn;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PopulationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('tsm-population') == 0) {
                Auth::logout();
                return redirect('login')->with('err_message', 'Access TSM denied!');
            }
            return $next($request);
        });
    }
 
    public function get_last_datetime($data) 
    {
        if (count($data) > 0){
            return date_format(date_create(substr($data[array_key_last(json_decode(json_encode($data),true))]["updated_at"],0,-3)),"Y-m-d H:i:s");
        }else{
            return date('m/d/Y h:i:s a', time()); 
        }
    }

    
    public function Population()
    {
        try{ 
            if(isset($this->PermissionActionMenu('tsm-population')->v) AND $this->PermissionActionMenu('tsm-population')->v==1){
                $data = Population::all();
                $updated_at = $this->get_last_datetime($data);
                $actionmenu = $this->PermissionActionMenu('tsm-population');
                return view('tsm/population', compact('data','updated_at','actionmenu'));
            }
            else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } 
        catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function SearchSerialNumber(Request $request){
        $ListSerialNumber = [];
        $SerialNumber = Population::select('serial_number')->groupBy('serial_number')->get();
        foreach ($SerialNumber as $item){
            array_push($ListSerialNumber,$item->serial_number);
        }
        return response()->json([
                'ListSerialNumber' => $ListSerialNumber,
            ], 200);
    }

    public function GetDataSerialNumber(Request $request){
        // dd($request);
        $DataSerialNumber= Population::select('*')->where('serial_number',$request->serialnumber)->orderBy('created_at','DESC')->first();
        // dd($DataSerialNumber1);
        // foreach($DataSerialNumber1 as $val){
        //     $DataSerialNumber = $val;
        // }
        // dd($DataSerialNumber);

        // $DataArea = Area::select('*')->where('area',$DataSerialNumber->area)->first();
        // $DataPopulationKalsel = PopulationKalsel::select('*')->where('sn_patria',$request->serialnumber)->orderBy('sn_patria','ASC')->first();
        return response()->json([
            'DataSerialNumber' => $DataSerialNumber,
            // 'DataArea' => $DataArea,
        ], 200);
    }

    public function SearchArea(Request $request){
        $ListArea = [];
        $Area = Area::select('area')->groupBy('area')->get();
        foreach ($Area as $item){
            array_push($ListArea,$item->area);
        }
        return response()->json([
                'ListArea' => $ListArea,
            ], 200);
    }

    public function GetDataArea(Request $request){
        $DataArea= Area::select('*')->where('area',$request->area)->orderBy('area','ASC')->first();
        return response()->json([
                'DataArea' => $DataArea,
            ], 200);
    }

    public function AddPopulation(Request $request){
        // dd($request);
        try{ 
            if(isset($this->PermissionActionMenu('tsm-population')->c) ){
                $data = [
                    'status' => 'x',
                ];

                $result = Population::where('serial_number', $request->addSerialNumber)->update($data);
                
                $addPopulation = Population::Create([
                    'serial_number' => $request->addSerialNumber,
                    'description' => $request->addDescription, 
                    'general_category' => $request->addGeneralCatgeory, 
                    'part_number' => $request->addPartNumber,
                    'plant' => $request->addPlant,
                    'area' =>$request->addArea,
                    'end_customer'=> $request->addEndCustomer,
                    'customer' => $request->addCustomer,
                    'no_lambung' => $request->addNoLambung,
                    'hm_km' => $request->hm_km,
                    'satuan' => $request->satuan,
                    'type_of_service' => $request->TypeOfService,
                    'tgl_service' => $request->addTglService,
                    'deliv_date' => $request->addDelivDate,
                    'commisioning_date' => $request->addCommisioningDate,
                    'brand' => $request->brand,
                    'notbrand' => $request->notbrand,
                    'variant' => $request->variant,
                    'created_by' => Auth::user()->name,
                    'updated_by' => Auth::user()->name,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    
                ]);
                $response = Http::withHeaders([
                    'Authorization' => '239|1Uvr9w1NsdDscnqa9aCF6aLsm57F7PrLWRSXsqyY',
                ])->post('http://webportal.patria.co.id/satria-api-man/public/api/insert-data-population', [
                    'serial_number' => $request->addSerialNumber,
                    'description' => $request->addDescription, 
                    'part_number' => $request->addPartNumber,
                    'area' =>$request->addArea,
                    'plant' => $request->addPlant,
                    'end_customer'=> $request->addEndCustomer,
                    'customer' => $request->addCustomer,
                    'deliv_date' => $request->addDelivDate,
                    'commisioning_date' => $request->addCommisioningDate,
                    'general_category' => $request->addGeneralCatgeory, 
                    'LastServiceType' => "",
                    'no_lambung' => $request->addNoLambung,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'created_by' => Auth::user()->name,
                    'updated_by' => Auth::user()->name,
                    'type_of_service' =>"aaa",
                    'hm_km' => $request->hm_km,
                    'satuan' => $request->satuan,
                    'tgl_service' => $request->addTglService,
                    'brand' => $request->brand,
                    'notbrand' => $request->notbrand,
                    'variant' => $request->variant,
                    'status' => "",
                ]);

                return redirect('tsm-population')->with('suc_message',"Data Berhasil Disimpan !");
            }
            else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
           
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function SearchPopulation(Request $request){
        return response()->json([
                'Population' => Population::select('*')->where('id_population_monitoring',$request->id)->first(),
            ], 200);
    }

    public function DeletePopulation(Request $request){
        try{ 
            $id = $request->deleteIDPopulation;
            $deleteIDPopulation = Population::where('id_population_monitoring',$id)->delete();
           
            $response = Http::get('http://10.48.10.61/tsm/public/api/delete-population-monitoring-app/'.$id);

            return redirect('tsm-population')->with('suc_message','Data berhasil dihapus !');
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }
    }

    public function SearchTypeOfService()
    {
        $TypeOfService = (object) ['PS1 GS1', 'PS2 GS2'];
        return response()->json([
            'TypeOfService' => $TypeOfService,
        ], 200);
    }

    public function ImportPopulation()
    {
        try{ 
            $response = Http::withHeaders([
                'Authorization' => '239|1Uvr9w1NsdDscnqa9aCF6aLsm57F7PrLWRSXsqyY',
            ])->get('http://webportal.patria.co.id/satria-api-man/public/api/get-population-monitoring-app');
            // dd($response);
            $message = $response["message"];
            if ($response->successful()){
                Population::truncate();
                $data = $response["data"]["data"];
                // dd($data);
                $new_data = [];
                foreach ($data as $data){
                    $new_col_data = [];
                    foreach ($data as $key => $val){
                        // dd($val);
                        if ($key == "created_at"){
                            $new_col_data[$key] = date('Y-m-d H:i:s', strtotime($val));
                            // dd($new_col_data[$key]);
                        }elseif($key == "updated_at"){
                            $new_col_data[$key] = date('Y-m-d H:i:s', strtotime($val));
                        }
                        else{
                            $new_col_data[$key] = $val;
                        }
                    }
                    array_push($new_data, $new_col_data);
                }

                // dd($new_data);
                $sn = array_chunk($new_data, 500, true);
                foreach ($sn as $sn) {
                    Population::insert($sn);
                }
                return redirect('tsm-population')->with('suc_message'," Data Berhasil diupdate !");
            }
            else{
                return redirect('tsm-population')->with('err_message',$message);
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return response()->json(['message' => 'Error Request, Exception Error '], 401);     
        }   
    } 

    public function import(Request $request){
        
        try{
            $the_file = $request->file('file');
    
            $spreadsheet = IOFactory::load($the_file->getRealPath());
            $sheet        = $spreadsheet->getSheet(0);;
            $row_limit    = $sheet->getHighestDataRow();
            // dd($sheet);
            $column_limit = $sheet->getHighestDataColumn();
            $cek_range = range( 1, 1 );
            // dd($sheet->getCell( 'A' . $cek_range[0])->getValue());
            if($sheet->getCell( 'A' . $cek_range[0])->getValue() == 'serial_number'){
                $row_range    = range( 2, $row_limit );
                $startcount = 2;
                // $data = array();
                // dd($row_range);
                foreach ( $row_range as $row ) {
                    if($sheet->getCell( 'A' . $row )->getValue() != "" || $sheet->getCell( 'A' . $row )->getValue() != NULL ||  $sheet->getCell( 'A' . $row )->getValue() != null){
                            // dd($sheet->getCell( 'A' . $row )->getValue());
    
                            $sn= Population::select('*')->where('serial_number',$sheet->getCell( 'A' . $row )->getValue())->orderBy('created_at','DESC')->first();
                            
                            if($sheet->getCell( 'C' . $row )->getValue() != "" || $sheet->getCell( 'C' . $row )->getValue() != NULL){
                                $excel_date = $sheet->getCell( 'C' . $row )->getValue(); //here is that value 41621 or 41631
                                $unix_date = ($excel_date - 25569) * 86400;
                                $excel_date = 25569 + ($unix_date / 86400);
                                $unix_date = ($excel_date - 25569) * 86400;
                                $commisioning_date = date("Y-m-d", $unix_date);
                            }else{
                                $commisioning_date = NULL;
                            }
        
                            if($sheet->getCell( 'H' . $row )->getValue() != "" || $sheet->getCell( 'H' . $row )->getValue() != NULL){
                                $excel_date2 = $sheet->getCell( 'H' . $row )->getValue(); //here is that value 41621 or 41631
                                $unix_date2 = ($excel_date2 - 25569) * 86400;
                                $excel_date2 = 25569 + ($unix_date2 / 86400);
                                $unix_date2 = ($excel_date2 - 25569) * 86400;
                                $tgl_service = date("Y-m-d", $unix_date2);
                            }else{
                                $tgl_service = NULL;
                            }
                            
                            $data = [
                                'serial_number' => $sheet->getCell( 'A' . $row )->getValue(),
                                'description' => $sn->description,
                                'general_category' => $sn->general_category,
                                'part_number' => $sn->part_number,
                                'plant' => $sn->plant,
                                'area' => $sn->area,
                                'end_customer'=> $sheet->getCell( 'B' . $row )->getValue(),
                                'customer' => $sn->customer,
                                'no_lambung' => $sheet->getCell( 'D' . $row )->getValue(),
                                'hm_km' => $sheet->getCell( 'F' . $row )->getValue(),
                                'satuan' => $sheet->getCell( 'G' . $row )->getValue(),
                                'type_of_service' => $sheet->getCell( 'E' . $row )->getValue(),
                                'tgl_service' => $tgl_service,
                                'deliv_date' => $sn->deliv_date,
                                'commisioning_date' => $commisioning_date,
                                'brand' => $sheet->getCell( 'I' . $row )->getValue(),
                                'notbrand' => $sheet->getCell( 'J' . $row )->getValue(),
                                'variant' => $sheet->getCell( 'K' . $row )->getValue(),
                                'created_by' => Auth::user()->name,
                                'updated_by' => Auth::user()->name,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                            // dd($data);
                            if($sheet->getCell( 'A' . $row )->getValue() != "" || $sheet->getCell( 'A' . $row )->getValue() != NULL){
            
                                // dd("masuk");
                                Population::insert($data);
                                $response = Http::withHeaders([
                                    'Authorization' => '239|1Uvr9w1NsdDscnqa9aCF6aLsm57F7PrLWRSXsqyY',
                                ])->post('http://webportal.patria.co.id/satria-api-man/public/api/insert-data-population', [
                                    'serial_number' => $sheet->getCell( 'A' . $row )->getValue(),
                                    'description' => $sn->description,
                                    'part_number' => $sn->part_number,
                                    'area' => $sn->area,
                                    'plant' => $sn->plant,
                                    'end_customer'=> $sheet->getCell( 'B' . $row )->getValue(),
                                    'customer' => $sn->customer,
                                    'deliv_date' => $sn->deliv_date,
                                    'commisioning_date' => $commisioning_date,
                                    'general_category' => $sn->general_category,
                                    'LastServiceType' => "",
                                    'no_lambung' => $sheet->getCell( 'D' . $row )->getValue(),
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                    'created_by' => Auth::user()->name,
                                    'updated_by' => Auth::user()->name,
                                    'type_of_service' => $sheet->getCell( 'E' . $row )->getValue(),
                                    'hm_km' => $sheet->getCell( 'F' . $row )->getValue(),
                                    'satuan' => $sheet->getCell( 'G' . $row )->getValue(),
                                    'tgl_service' => $tgl_service,
                                    'brand' =>  $sheet->getCell( 'I' . $row )->getValue(),
                                    'notbrand' => $sheet->getCell( 'J' . $row )->getValue(),
                                    'variant' => $sheet->getCell( 'K' . $row )->getValue(),
                                    'status' => "",
                                ]);
                                // dd($response);
                            }
                        } else {
                            // dd("null");
                            $sn= Population::select('*')->where('no_lambung',$sheet->getCell( 'D' . $row )->getValue())->orderBy('created_at','DESC')->first();
                            if(!empty($sn)){
    
                                if($sheet->getCell( 'C' . $row )->getValue() != "" || $sheet->getCell( 'C' . $row )->getValue() != NULL){
                                    $excel_date = $sheet->getCell( 'C' . $row )->getValue(); //here is that value 41621 or 41631
                                    $unix_date = ($excel_date - 25569) * 86400;
                                    $excel_date = 25569 + ($unix_date / 86400);
                                    $unix_date = ($excel_date - 25569) * 86400;
                                    $commisioning_date = date("Y-m-d", $unix_date);
                                }else{
                                    $commisioning_date = NULL;
                                }
            
                                if($sheet->getCell( 'H' . $row )->getValue() != "" || $sheet->getCell( 'H' . $row )->getValue() != NULL){
                                    $excel_date2 = $sheet->getCell( 'H' . $row )->getValue(); //here is that value 41621 or 41631
                                    $unix_date2 = ($excel_date2 - 25569) * 86400;
                                    $excel_date2 = 25569 + ($unix_date2 / 86400);
                                    $unix_date2 = ($excel_date2 - 25569) * 86400;
                                    $tgl_service = date("Y-m-d", $unix_date2);
                                }else{
                                    $tgl_service = NULL;
                                }
                                $data = [
                                    'serial_number' => $sn->serial_number,
                                    'description' => $sn->description,
                                    'general_category' => $sn->general_category,
                                    'part_number' => $sn->part_number,
                                    'plant' => $sn->plant,
                                    'area' => $sn->area,
                                    'end_customer'=> $sheet->getCell( 'B' . $row )->getValue(),
                                    'customer' => $sn->customer,
                                    'no_lambung' => $sheet->getCell( 'D' . $row )->getValue(),
                                    'hm_km' => $sheet->getCell( 'F' . $row )->getValue(),
                                    'satuan' => $sheet->getCell( 'G' . $row )->getValue(),
                                    'type_of_service' => $sheet->getCell( 'E' . $row )->getValue(),
                                    'tgl_service' => $tgl_service,
                                    'deliv_date' => $sn->deliv_date,
                                    'commisioning_date' => $commisioning_date,
                                    'brand' => $sheet->getCell( 'I' . $row )->getValue(),
                                    'notbrand' => $sheet->getCell( 'J' . $row )->getValue(),
                                    'variant' => $sheet->getCell( 'K' . $row )->getValue(),
                                    'created_by' => Auth::user()->name,
                                    'updated_by' => Auth::user()->name,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ];
                                // dd($data);
                                // dd("masuk");
                                Population::insert($data);
                                $response = Http::withHeaders([
                                    'Authorization' => '239|1Uvr9w1NsdDscnqa9aCF6aLsm57F7PrLWRSXsqyY',
                                ])->post('http://webportal.patria.co.id/satria-api-man/public/api/insert-data-population', [
                                    'serial_number' => $sn->serial_number,
                                    'description' => $sn->description,
                                    'part_number' => $sn->part_number,
                                    'area' => $sn->area,
                                    'plant' => $sn->plant,
                                    'end_customer'=> $sheet->getCell( 'B' . $row )->getValue(),
                                    'customer' => $sn->customer,
                                    'deliv_date' => $sn->deliv_date,
                                    'commisioning_date' => $commisioning_date,
                                    'general_category' => $sn->general_category,
                                    'LastServiceType' => "",
                                    'no_lambung' => $sheet->getCell( 'D' . $row )->getValue(),
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                    'created_by' => Auth::user()->name,
                                    'updated_by' => Auth::user()->name,
                                    'type_of_service' => $sheet->getCell( 'E' . $row )->getValue(),
                                    'hm_km' => $sheet->getCell( 'F' . $row )->getValue(),
                                    'satuan' => $sheet->getCell( 'G' . $row )->getValue(),
                                    'tgl_service' => $tgl_service,
                                    'brand' =>  $sheet->getCell( 'I' . $row )->getValue(),
                                    'notbrand' => $sheet->getCell( 'J' . $row )->getValue(),
                                    'variant' => $sheet->getCell( 'K' . $row )->getValue(),
                                    'status' => "",
                                ]);
                                // dd($response);
                            } else{
                                if($sheet->getCell( 'C' . $row )->getValue() != "" || $sheet->getCell( 'C' . $row )->getValue() != NULL){
                                    $excel_date = $sheet->getCell( 'C' . $row )->getValue(); //here is that value 41621 or 41631
                                    $unix_date = ($excel_date - 25569) * 86400;
                                    $excel_date = 25569 + ($unix_date / 86400);
                                    $unix_date = ($excel_date - 25569) * 86400;
                                    $commisioning_date = date("Y-m-d", $unix_date);
                                }else{
                                    $commisioning_date = NULL;
                                }
            
                                if($sheet->getCell( 'H' . $row )->getValue() != "" || $sheet->getCell( 'H' . $row )->getValue() != NULL){
                                    $excel_date2 = $sheet->getCell( 'H' . $row )->getValue(); //here is that value 41621 or 41631
                                    $unix_date2 = ($excel_date2 - 25569) * 86400;
                                    $excel_date2 = 25569 + ($unix_date2 / 86400);
                                    $unix_date2 = ($excel_date2 - 25569) * 86400;
                                    $tgl_service = date("Y-m-d", $unix_date2);
                                }else{
                                    $tgl_service = NULL;
                                }
                                $data = [
                                    'serial_number' => "",
                                    'description' => "",
                                    'general_category' => "",
                                    'part_number' => "",
                                    'plant' => "",
                                    'area' => "",
                                    'end_customer'=> $sheet->getCell( 'B' . $row )->getValue(),
                                    'customer' => "",
                                    'no_lambung' => $sheet->getCell( 'D' . $row )->getValue(),
                                    'hm_km' => $sheet->getCell( 'F' . $row )->getValue(),
                                    'satuan' => $sheet->getCell( 'G' . $row )->getValue(),
                                    'type_of_service' => $sheet->getCell( 'E' . $row )->getValue(),
                                    'tgl_service' => $tgl_service,
                                    'deliv_date' => "",
                                    'commisioning_date' => $commisioning_date,
                                    'brand' => $sheet->getCell( 'I' . $row )->getValue(),
                                    'notbrand' => $sheet->getCell( 'J' . $row )->getValue(),
                                    'variant' => $sheet->getCell( 'K' . $row )->getValue(),
                                    'created_by' => Auth::user()->name,
                                    'updated_by' => Auth::user()->name,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ];
                                // dd($data);
                
                                // dd("masuk");
                                Population::insert($data);
                                $response = Http::withHeaders([
                                    'Authorization' => '239|1Uvr9w1NsdDscnqa9aCF6aLsm57F7PrLWRSXsqyY',
                                ])->post('http://webportal.patria.co.id/satria-api-man/public/api/insert-data-population', [
                                    'serial_number' => "",
                                    'description' => "",
                                    'part_number' => "",
                                    'area' => "",
                                    'plant' => "",
                                    'end_customer'=> $sheet->getCell( 'B' . $row )->getValue(),
                                    'customer' => "",
                                    'deliv_date' => "",
                                    'commisioning_date' => $commisioning_date,
                                    'general_category' => "",
                                    'LastServiceType' => "",
                                    'no_lambung' => $sheet->getCell( 'D' . $row )->getValue(),
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                    'created_by' => Auth::user()->name,
                                    'updated_by' => Auth::user()->name,
                                    'type_of_service' => $sheet->getCell( 'E' . $row )->getValue(),
                                    'hm_km' => $sheet->getCell( 'F' . $row )->getValue(),
                                    'satuan' => $sheet->getCell( 'G' . $row )->getValue(),
                                    'tgl_service' => $tgl_service,
                                    'brand' =>  $sheet->getCell( 'I' . $row )->getValue(),
                                    'notbrand' => $sheet->getCell( 'J' . $row )->getValue(),
                                    'variant' => $sheet->getCell( 'K' . $row )->getValue(),
                                    'status' => "",
                                ]);
                                // dd($response);
                            }
                        }
        
                    $startcount++;
                }
        
            
                return redirect('tsm-population')->with('suc_message',"Data Berhasil Disimpan !");
            }else{
                return redirect('tsm-population')->with('err_message',"Data Gagal Disimpan !");
            }
          
        } catch (Exception $e) {
            // $this->ErrorLog($e);
            // return back()->withErrors('There was a problem uploading the data!');
        }
    }




}
