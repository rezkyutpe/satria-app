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
use App\Models\View\PoNonSAP\VwPoPro;
use App\Models\View\PoNonSAP\VwKomponenPro;
use App\Models\View\PoNonSAP\VwPackageGroup;
use App\Models\Table\PoNonSAP\MstPo;
use App\Models\Table\PoNonSAP\MstHistoryPicking;
use App\Models\Table\PoNonSAP\MstPackage;
use App\Models\Table\PoNonSAP\MstKomponen;
use App\Models\Table\PoNonSAP\Komponen;
use App\Models\View\VwPermissionAppsMenu;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PoManController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware(function ($request, $next) {
        if ($this->PermissionMenu('picking-management') == 0){
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
            return $next($request);
        });
    }

    public function PoMgmtInit(Request $request)
    {
      if($this->PermissionActionMenu('picking-management')->r==1){
        $paginate = 15;
        if (isset($request->query()['search'])){
            $search = $request->query()['search'];
            $po = VwPoPro::where('pro', 'like', "%" . $search. "%")->orWhere('pn', 'like', "%" . $search. "%")->orderBy('created_at', 'asc')->simplePaginate($paginate);
            $po->appends(['search' => $search]);
        } else {
            $po = VwPoPro::orderBy('created_at', 'asc')->simplePaginate($paginate);
        }
        $package = VwPackageGroup::orderBy('package', 'asc')->get();
        
        $no = 1;
        foreach($po as $data){
            $data->no = $no;
            $no++;
        }
        $data = array(
          'po' => $po,
          'package' => $package,
          'actionmenu' => $this->PermissionActionMenu('picking-management'),
        );

        return view('po-non-sap/po-management/index')->with('data', $data);
      }else{
        return redirect('/')->with('err_message', 'Akses Ditolak!');
      }

    }
    public function PoMgmtPdf($nopo)
    {
      if($this->PermissionActionMenu('picking-management')->v==1){
        $po = VwPoPro::where('nopo', $nopo)->first();
        $komponen = Komponen::where('po', $nopo)->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($po->uuid));
   
        $data = array(
          'po' => $po,
          'komponen' => $komponen,
          'qrcode' => $qrcode,
        );
          
        $pdf = PDF::loadView('po-non-sap/po-management/print-pdf', $data);
        
        // return view('po-non-sap/po-management/myPDF')->with('data', $data);
        return $pdf->stream();
      }else{
        return redirect('picking-management')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function PoMgmtView($nopo)
    {
      if($this->PermissionActionMenu('picking-management')->c==1){
        $response = Http::get('http://10.48.10.43/imaapi/api/GetPROCustProduct?nopro=0');
        $getpro = json_decode($response,true);

        $po = VwPoPro::where('nopo', $nopo)->first();
        $tkomponen =  VwKomponenPro::where('po', $nopo)->get();
        $komponen = MstKomponen::orderBy('description', 'asc')->get();
          
          $data = array(
            'po' => $po,
            'tkomponen' => $tkomponen,
            'komponen' => $komponen,
            'getpro' => $getpro,
          );
        // echo $count;
        return view('po-non-sap/po-management/view-po')->with('data', $data);
      }else{
        return redirect('picking-management')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function PoMgmtEdit($nopo)
    {
      if($this->PermissionActionMenu('picking-management')->c==1){
        $response = Http::get('http://10.48.10.43/imaapi/api/GetPROCustProduct?nopro=0');
        $getpro = json_decode($response,true);

        $po = VwPoPro::where('nopo', $nopo)->first();

          $pro = MstPro::where('pro', $po->pro)->first();
        $tkomponen =  VwKomponenPro::where('po', $nopo)->get();
        $komponen = MstKomponen::orderBy('description', 'asc')->get();
          
          $data = array(
            'po' => $po,
            'pro' => $pro,
            'tkomponen' => $tkomponen,
            'komponen' => $komponen,
            'getpro' => $getpro,
          );
        // echo $count;
        return view('po-non-sap/po-management/edit-po')->with('data', $data);
      }else{
        return redirect('picking-management')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function getPackage($name){

    	// Fetch Employees by name
        $empData['data'] = MstPackage::where('name',$name)->first();
  
        return response()->json($empData);
     
    }
    public function PoMgmtAdd()
    {
      if($this->PermissionActionMenu('picking-management')->c==1){
          $response = Http::get('http://10.48.10.43/imaapi/api/GetPROCustProduct?nopro=0');
          $getpro = json_decode($response,true);
          $pro = MstPro::orderBy('pro', 'asc')->get();
          $komponen = MstKomponen::orderBy('description', 'asc')->get();
          $count = count(MstPo::orderBy('nopo', 'asc')->get())+1;
          $no = 1;
            foreach($pro as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
              'komponen' => $komponen,
              'pro' => $pro,
              'getpro' => $getpro,
              'count' => $count
            );
          // echo $count;
            return view('po-non-sap/po-management/add-po')->with('data', $data);
      }else{
        return redirect('picking-management')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function PoMgmtAddPackage(Request $request)
    {
      if($this->PermissionActionMenu('picking-management')->c==1){
          $response = Http::get('http://10.48.10.43/imaapi/api/GetPROCustProduct?nopro=0');
          $getpro = json_decode($response,true);
          $pro = MstPro::orderBy('pro', 'asc')->get();
          $komponen = MstKomponen::orderBy('description', 'asc')->get();
          // $package = MstPackage::where('package', $request->package)->where('flag', 1)->get();
          $package = \DB::table('satria_picking.t_package')->join('satria_picking.t_komponen', 't_komponen.pn_patria', '=', 't_package.name')
                              ->select('t_package.id', 't_package.package', 't_package.ket', 't_package.qty', 't_package.name', 't_komponen.description as descr', 't_package.pn_eaton', 't_package.flag')
                              ->where('t_package.package', $request->package)->where('t_package.flag', 1)
                              ->get();
          $count = count(MstPo::orderBy('nopo', 'asc')->get())+1;
          $no = 1;
            foreach($pro as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
              'komponen' => $komponen,
              'pro' => $pro,
              'package' => $package,
              'getpro' => $getpro,
              'count' => $count
            );
          // echo $count;
            return view('po-non-sap/po-management/add-po-package')->with('data', $data);
      
      }else{
        return redirect('picking-management')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function PoMgmtInsertPackage(Request $request)
    {
        if($this->PermissionActionMenu('picking-management')->c==1){
          $pro = MstPro::where('pro', $request->pro)->first();
            if(empty($pro)){
            $createpro = MstPro::create([
                'pro' => $request->pro,
                'pn' => $request->pn,
                'product' => $request->product,
                'qty' => $request->qty,
                'cust' => $request->cust,
            ]);
            }
            if(count($request->pn_patria) > 0)
            {
              foreach($request->pn_patria as $item=>$v){
                  $data2=array(
                      'pn_patria'=>$request->pn_patria[$item],
                      'description'=>$request->description[$item],
                      'pn_vendor'=>'',
                      'qty_order'=>$request->quantity[$item],
                      'qty_supply'=>0,
                      'status'=>0,
                      'ket'=>'-',
                      'po'=>$request->nopo,
                      'package'=>$request->package,
                  );
                Komponen::insert($data2);
              }
            }
            $create = MstPo::create([
              'nopo' => $request->nopo,
              'po_ref' => $request->po_ref,
              'pro' => $request->pro,
              'flag' => 0,
              'uuid' => md5(rand(1, 50) . microtime()),
              'ttd1' => 'Asep Syarip Mahmud',
              'ttd2' => 'Sri Zuwibhi',
              'created_by' => Auth::user()->id,
          ]);
          if($create){

              return redirect('picking-management')->with('suc_message', 'Data berhasil ditambahkan!');
          }else{
              return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
          }
        }else{
          return redirect('picking-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function PoMgmtInsert(Request $request)
    {
      if($this->PermissionActionMenu('picking-management')->c==1){
          $pro = MstPro::where('pro', $request->pro)->first();
          if(empty($pro)){
          $createpro = MstPro::create([
              'pro' => $request->pro,
              'pn' => $request->pn,
              'product' => $request->product,
              'qty' => $request->qty,
              'cust' => $request->cust,
          ]);
          }
          if(count($request->pn_patria) > 0)
          {
            // $createpackage = MstPackage::create([
            //   'name' => $request->package,
            // ]);
            foreach($request->pn_patria as $item=>$v){
                $data2=array(
                    'pn_patria'=>$request->pn_patria[$item],
                    'description'=>$request->description[$item],
                    'pn_vendor'=>$request->pn_vendor[$item],
                    'qty_order'=>$request->quantity[$item],
                    'qty_supply'=>0,
                    'status'=>0,
                    'ket'=>'-',
                    'po'=>$request->nopo,
                );
              Komponen::insert($data2);
            }
          }
          $create = MstPo::create([
            'nopo' => $request->nopo,
            'po_ref' => $request->po_ref,
            'pro' => $request->pro,
            'qty_unit' => $request->qty,
            'flag' => 0,
            'uuid' => Hash::make(md5(rand(1, 50) . microtime())),
            'ttd1' => 'Asep Syarip Mahmud',
            'ttd2' => 'Sri Zuwibhi',
            'created_by' => Auth::user()->id,
        ]);
        if($create){
           MstHistoryPicking::insert([
                'no_po' => $request->nopo,
                'created_by'=> Auth::user()->id,
                'flag' => 0,
            ]);
            $this->send('Vendor','Picking '. $request->nopo,'mochr.patria@gmail.com','New Picking has created with Picking Number '.$request->nopo);
            return redirect('picking-management')->with('suc_message', 'Data berhasil ditambahkan!');
        }else{
            return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
        }
      }else{
        return redirect('picking-management')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function PoMgmtDelete(Request $request)
    {
      if($this->PermissionActionMenu('picking-management')->d==1){
          $picking = MstPo::where('nopo', $request->nopo)->first();
          if(!empty($picking)){
              
            MstPo::where('nopo', $request->nopo)
              ->update([
                'is_deleted' => 1,
                ]
                );
              return redirect()->back()->with('suc_message', 'Data telah dihapus!');
          } else {
              return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
          }
      }else{
          return redirect('picking-management')->with('err_message', 'Akses Ditolak!');
      }
    }


}
