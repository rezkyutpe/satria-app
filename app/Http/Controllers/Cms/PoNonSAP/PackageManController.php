<?php

namespace App\Http\Controllers\Cms\PoNonSAP;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Http;
use PDF;
use App\Models\Table\PoNonSAP\MstPro;
use App\Models\View\PoNonSAP\VwPackageGroup;
use App\Models\View\PoNonSAP\VwPoPro;
use App\Models\Table\PoNonSAP\MstPo;
use App\Models\Table\PoNonSAP\MstPackage;
use App\Models\Table\PoNonSAP\MstHistoryPackage;
use App\Models\Table\PoNonSAP\MstKomponen;
use App\Models\View\VwPermissionAppsMenu;

class PackageManController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('package-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function PackageMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('package-management')->r==1){
            $paginate = 15;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $package = VwPackageGroup::where('package', 'like', "%" . $search. "%")->simplePaginate($paginate);
                $package->appends(['search' => $search]);
            } else {
                $package = VwPackageGroup::simplePaginate($paginate);
            }
            
            $no = 1;
            foreach($package as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
            'package' => $package,
            'actionmenu' => $this->PermissionActionMenu('package-management'),
            );

            return view('po-non-sap/package-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function PackageMgmtView($pack)
    {
        if($this->PermissionActionMenu('package-management')->v==1){
            $package = MstPackage::where('package', $pack)->get();
            
            $data = array(
            'package' => $package,
            'packagename' => $pack
            );
        // echo $count;
            return view('po-non-sap/package-management/view-package')->with('data', $data);
        }else{
            return redirect('package-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function PackageUpload(Request $request)
    {
        if($this->PermissionActionMenu('package-management')->c==1){
            if($request->file){
                $file_extention = $request->file->getClientOriginalExtension();
                $file_name = 'Package_'.date('YmdHis').'.'.$file_extention;
                $fileSize = $request->file->getSize();
                $valid_extension = array("csv");
                $maxFileSize = 2097152;
                if(in_array(strtolower($file_extention),$valid_extension)){
                // Check file size
                if($fileSize <= $maxFileSize){
                    $file_path = $request->file->move($this->MapPublicPath().'package',$file_name);
                    echo $this->MapPublicPath().'package';
                    echo $file_name;
                    $filename = $this->MapPublicPath().'package/'.$file_name;
                    $csvdata = []; 
                    $package = array();
                    if (($h = fopen("{$filename}", "r")) !== FALSE) 
                    {
                        while (($datas = fgetcsv($h, 1000, ";")) !== FALSE) 
                        {
                            $csvdata[] = $datas;		
                        }
                    fclose($h);
                    }
                    // echo count($csvdata);
                    // echo "<pre>";
                    // // print_r($csvdata[3][5]);
                    // print_r($csvdata);
                    // echo "</pre>";
                    for ($i=3; $i <  count($csvdata); $i++) { 
                        array_push($package,array(
                            'ket' =>$csvdata[$i][1],
                            'qty' => $csvdata[$i][2],
                            'name' =>$csvdata[$i][3],
                            'descr' =>$csvdata[$i][4],
                            'pn_eaton' =>''
                        )); 
                    }
                
                    $response = Http::get('http://10.48.10.43/imaapi/api/GetPROCustProduct?nopro=0');
                    $getpro = json_decode($response,true);
                    $komponen = MstKomponen::orderBy('description', 'asc')->get();
                    $data = array(
                        'package' => $package,
                        'getpro' => $getpro,
                        'komponen' => $komponen,
                    );
                    return view('po-non-sap/package-management/add-package')->with('data', $data);
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
            return redirect('package-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function PackageMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('package-management')->c==1){
            if(count($request->name) > 0)
            {
            foreach($request->name as $item=>$v){
                if($request->name!=''){
                    $data2=array(
                        'package'=>$request->nopo,
                        'ket'=>$request->ket[$item],
                        'qty'=>$request->qty[$item],
                        'name'=>$request->name[$item],
                        'descr'=>$request->descr[$item],
                        'pn_eaton'=>$request->pn_eaton[$item],
                        'created_by'=> Auth::user()->id,
                    );
                    $komponen = MstKomponen::where('pn_patria', $request->name[$item])->first();
                    if(empty($komponen)){
                        $create = MstKomponen::create([
                            'pn_patria'=>$request->name[$item],
                            'description'=>$request->descr[$item],
                            'pn_vendor'=>'',
                            'type' => '',
                        ]);
                    }
                MstPackage::insert($data2);
                }
            }
            return redirect('package-management')->with('suc_message', 'Data berhasil ditambahkan!');
            
            }else{
                return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
            }
        }else{
            return redirect('package-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function PackageMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('package-management')->u==1){
            $komponen = MstPackage::where('pn_patria', $request->pn_patria)->first();
            if(!empty($komponen)){
                $update = MstPackage::where('pn_patria', $request->pn_patria)
                ->update([
                    'pn_patria'=>$request->pn_patrian,
                    'description'=>$request->desc,
                    'pn_vendor'=>$request->pn_vendor,
                    'type' => $request->type,
                ]);
                if($update){
                   
                    return redirect('package-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'PN Patria Gagal ditemukan!');
            }
        }else{
            return redirect('package-management')->with('err_message', 'Akses Ditolak!');
        }    
    }
    public function PackageMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('package-management')->d==1){
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
            return redirect('package-management')->with('err_message', 'Akses Ditolak!');
        }
    }


}
