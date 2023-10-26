<?php

namespace App\Http\Controllers\Cms\Incentive;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Http;
use PDF;
use App\Models\Table\Incentive\Incentive;
use App\Models\Table\Incentive\AdjustmentHistory;
use App\Models\Table\Incentive\Customer;
use App\Models\Table\Incentive\CustType;
use App\Models\View\VwPermissionAppsMenu;
use App\Imports\IncentiveImport;

class IncentiveManController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('incentive-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function IncentiveMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('incentive-management')->r==1){
            $paginate = 1500;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $incentive = Incentive::where('inv', 'like', "%" . $search. "%")->simplePaginate($paginate);
                $incentive->appends(['search' => $search]);
            } else {
                $incentive = Incentive::simplePaginate($paginate);
            }
            
            $no = 1;
            foreach($incentive as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
            'incentive' => $incentive,
            'actionmenu' => $this->PermissionActionMenu('incentive-management'),
            );

            return view('incentive/incentive-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function IncentiveMgmtView($pack)
    {
        if($this->PermissionActionMenu('incentive-management')->v==1){
            $incentive = MstPackage::where('incentive', $pack)->get();
            
            $data = array(
            'incentive' => $incentive,
            'packagename' => $pack
            );
        // echo $count;
            return view('incentive/incentive-management/view-incentive')->with('data', $data);
        }else{
            return redirect('incentive-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function IncentiveUpload(Request $request)
    {
        if($this->PermissionActionMenu('incentive-management')->c==1){
            if($request->file){
                $file_extention = $request->file->getClientOriginalExtension();
                $file_name = 'Inc_'.date('YmdHis').'.'.$file_extention;
                $fileSize = $request->file->getSize();
                $valid_extension = array("xlsx");
                $maxFileSize = 2097152;
                if(in_array(strtolower($file_extention),$valid_extension)){
                // Check file size
                if($fileSize <= $maxFileSize){
                    $file_path = $request->file->move($this->MapPublicPath().'incentive',$file_name);
                    // echo $this->MapPublicPath().'incentive';
                    // echo $file_name;
                    $filename = $this->MapPublicPath().'incentive/'.$file_name;
                    
                    $row = Excel::toArray(new IncentiveImport, $this->MapPublicPath().'incentive/'.$file_name);
                // print_r($row);
                $sales=array();
                $inc_data=array();
                $data = array(
                    'inc' => $row,
                    'sales' => array_values(array_unique(array_column($row[0],0))),
                    );

                
                // $sum_qty = array_sum(array_column($this->filter_by_value($row[0], '0', '1'),10));
                // $sum_price =array_column($this->filter_by_value($row[0], '0', '1'),12);
                // $price=0;
                // $tot_price=0;
                // echo '<pre>'; print_r($sum_price); echo '</pre>';
                // foreach($row[0] as $key){
                //     // echo $key[0];
                //     array_push($sales,$key[0]);
                //     array_push($inc_data,array(
                //         'inv' => $key[2],
                //         'inv_date' => $key[3], 
                //         'cash_date' => $key[4], 
                //         'sales' => $key[0], 
                //         'sales_name' => $key[1], 
                //         'id_cust' => $key[5], 
                //         'customer' => $key[6], 
                //         'cust_profile' => $key[7], 
                //         'product' => $key[8], 
                //         'segment' => $key[9], 
                //         'qty' => $key[10], 
                //         'tot_cost' => $key[11], 
                //         'tot_price' => $key[12]*$key[10], 
                //         'gpm'=> $this->Gpm($key[11],$key[12]*$key[10],$key[9]),
                //         'aging'=> $this->Aging($key[3],$key[4],$key[7],$key[9]),
                //         'inc_ef'=> $this->IncEF($key[9]),
                //     ));
                //     $price= $key[12]*$key[10];
                //     $tot_price = $tot_price+$price;
                // }
               
                
                // array_push($inc_data,$tot_price);
                return view('incentive/incentive-management/view')->with('data', $data);

                // echo '<pre>'; print_r(array_unique(array_column($row[0],0))); echo '</pre>';
                // echo '<pre>'; print_r($inc_data); echo '</pre>';
		        // Excel::import(new IncentiveImport, $this->MapPublicPath().'incentive/'.$file_name);
                    // return view('incentive/incentive-management/add-incentive')->with('data', $data);
            // return redirect('incentive-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'File too large. File must be less than 2MB.');
                }
                }else{
                return redirect()->back()->with('err_message', 'Invalid File Extension.');
                }
            }else{
            $file_name="file not exists";
            }
        }else{
            return redirect('incentive-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    
    function filter_by_value ($array, $index, $value){
        if(is_array($array) && count($array)>0) 
        {
            foreach(array_keys($array) as $key){
                $temp[$key] = $array[$key][$index];
                
                if ($temp[$key] == $value){
                    $newarray[$key] = $array[$key];
                }
            }
          }
      return $newarray;
    }
    public function IncentiveMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('incentive-management')->c==1){
            if(count($request->inv) > 0)
            {
            foreach($request->inv as $item=>$v){
                $target=0;
               
                $data2=array(
                    'inv' => $request->inv[$item],
                    'inv_date' => $request->inv_date[$item], 
                    'cash_date' => $request->cash_date[$item], 
                    'sales' => $request->sales[$item], 
                    'id_cust' => $request->id_cust[$item], 
                    'customer' => $request->cust[$item], 
                    'cust_profile' => $request->cust_profile[$item], 
                    'product' => $request->product[$item], 
                    'segment' => $request->segment[$item], 
                    'qty' => round($request->qty[$item],0), 
                    'tot_cost' => round($request->cost[$item],0), 
                    'tot_price' => round($request->price[$item],0), 
                    'cash_in' => round($request->cash_in[$item],0), 
                    'grading'=> $this->Grade($request->sales[$item]),
                    'gpm'=> $this->Gpm($request->cost[$item],$request->price[$item],$request->segment[$item]),
                    'aging'=> $this->Aging($request->inv_date[$item],$request->cash_date[$item],$request->cust_profile[$item],$request->segment[$item]),
                    'inc_ef'=> $this->IncEF($request->segment[$item]),
                    'target'=> $this->Target($request->total[$request->sales_val[$item]],$request->sales[$item],$request->segment[$item]),
                    'cust_type'=> $request->cust_type[$item],
                    'created_by'=> Auth::user()->id,
                    // 'totalamount'=>$request->total[$request->sales_val[$item]],
                );
                $customer = Customer::where('id', $request->id_cust[$item])->first();
                if(empty($customer)){
                    $create = Customer::create([
                        'id'=>$request->id_cust[$item],
                        'name'=>$request->cust[$item],
                        'status'=>0,
                        'created_by'=> Auth::user()->id,
                    ]);
                }
                Incentive::insert($data2);
                // echo '<pre>'; print_r($data2); echo '</pre>';

            }
            return redirect('incentive-management')->with('suc_message', 'Data berhasil ditambahkan!');
            
            }else{
                return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
            }
        }else{
            return redirect('incentive-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function IncentiveMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('incentive-management')->u==1){
            $inc = Incentive::where('id', $request->id)->first();
            if(!empty($inc)){
                $update = Incentive::where('id', $request->id)
                ->update([
                    'cash_date' => $request->cash_date, 
                    'aging'=> $this->Aging($request->inv_date,$request->cash_date,$request->cust_profile,$request->segment),
                    'updated_by'=> Auth::user()->id,
                ]);
                AdjustmentHistory::insert([
                    'inc'=> $request->id,
                    'cash_date_old'=>$request->cash_date_old,
                    'cash_date_new'=>$request->cash_date,
                    'created_by'=> Auth::user()->id,
                ]);
                if($update){
                    return redirect('incentive-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Inv Tidak ditemukan!');
            }
        }else{
            return redirect('incentive-management')->with('err_message', 'Akses Ditolak!');
        }    
    }
    
    public function IncentiveMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('incentive-management')->d==1){
            $del = MstPackage::where('id', $request->id)->first();
            if(!empty($del)){
                // MstPackage::where('id', $request->id)->delete();
                MstPackage::where('id', $request->id)
                ->update([
                    'updated_by'=> Auth::user()->id,
                    'flag' => $request->flag,
                ]);
                //add history
                MstHistoryPackage::insert([
                    'id_package' => $request->id,
                    'created_by'=> Auth::user()->id,
                    'flag' => $request->flag,
                ]);
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('incentive-management')->with('err_message', 'Akses Ditolak!');
        }
    }

}
