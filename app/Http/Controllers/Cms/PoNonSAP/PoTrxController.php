<?php

namespace App\Http\Controllers\Cms\PoNonSAP;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Http;
use PDF;
use Storage;
use Exception;
use Excel;
use App\Exports\PickingExport;
use App\Models\Table\PoNonSAP\MstPro;
use App\Models\View\PoNonSAP\VwPoPro;
use App\Models\View\PoNonSAP\VwKomponenPro;
use App\Models\View\PoNonSAP\VwKomponenTrx;
use App\Models\View\PoNonSAP\VwPackageGroup;
use App\Models\Table\PoNonSAP\MstPo;
use App\Models\Table\PoNonSAP\MstPackage;
use App\Models\Table\PoNonSAP\MstKomponen;
use App\Models\Table\PoNonSAP\Komponen;
use App\Models\Table\PoNonSAP\MstHistoryPicking;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\View\VwPermissionAppsMenu;

class PoTrxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('auth');


    }

    public function PoTrxInit(Request $request)
    {
      if($this->PermissionActionMenu('transaction')->r==1){
          $paginate = 150;
          if (isset($request->query()['search'])){
              $search = $request->query()['search'];
              $po = VwPoPro::where('pro', 'like', "%" . $search. "%")->orWhere('pn', 'like', "%" . $search. "%")->orderBy('pro', 'asc')->orderBy('nopo', 'asc')->simplePaginate($paginate);
              $po->appends(['search' => $search]);
          } else {
              $po = VwPoPro::orderBy('pro', 'asc')->orderBy('nopo', 'asc')->simplePaginate($paginate);
          }
          
          $no = 1;
          foreach($po as $data){
              $data->no = $no;
              $no++;
          }
          $data = array(
            'po' => $po,
            'actionmenu' => $this->PermissionActionMenu('transaction'),
          );

          return view('po-non-sap/po-transaksi/index')->with('data', $data);
      }else{
        return redirect('/')->with('err_message', 'Akses Ditolak!');
      }  
    } 
    public function exportPicking($id)
    {
        // if($this->PermissionActionMenu('pajak-management')->v==1){
//           $doc = new \DOMDocument();
// $doc->loadHTML("<html><body>Test<br></body></html>");
// echo $doc->saveHTML();
            $nama_file = 'picking-'."-".$id."-".date('dmYHis').'.xlsx';
            return Excel::download(new PickingExport($id), $nama_file);
        // }else{
        //     return redirect('pajak-management')->with('err_message', 'Akses Ditolak!');
        // }
    }
    public function PoRcvInit(Request $request)
    {
      if($this->PermissionActionMenu('receive-transaction')->r==1){
          $paginate = 150;
          if (isset($request->query()['search'])){
              $search = $request->query()['search'];
              $po = VwPoPro::where('pro', 'like', "%" . $search. "%")->where('flag','>=',1)->orWhere('pn', 'like', "%" . $search. "%")->where('flag',1)->orderBy('created_at', 'asc')->simplePaginate($paginate);
              $po->appends(['search' => $search]);
          } else {
              $po = VwPoPro::where('flag','>=',1)->orderBy('created_at', 'asc')->simplePaginate($paginate);
          }
          
          $no = 1;
          foreach($po as $data){
              $data->no = $no;
              $no++;
          }
          $data = array(
            'po' => $po,
            'actionmenu' => $this->PermissionActionMenu('receive-transaction'),
          );

          return view('po-non-sap/po-transaksi/receive')->with('data', $data);
      }else{
        return redirect('/')->with('err_message', 'Akses Ditolak!');
      }  
      
    }
    public function PoFinishInit(Request $request)
    {
      if($this->PermissionActionMenu('finished-transaction')->r==1){
        $paginate = 150;
        if (isset($request->query()['search'])){
            $search = $request->query()['search'];
            $po = VwPoPro::where('pro', 'like', "%" . $search. "%")->where('flag','>=',3)->orWhere('pn', 'like', "%" . $search. "%")->where('flag',3)->orderBy('created_at', 'asc')->simplePaginate($paginate);
            $po->appends(['search' => $search]);
        } else {
            $po = VwPoPro::where('flag','>=',3)->orderBy('created_at', 'asc')->simplePaginate($paginate);
        }
        
        $no = 1;
        foreach($po as $data){
            $data->no = $no;
            $no++;
        }
        $data = array(
          'po' => $po,
          'actionmenu' => $this->PermissionActionMenu('finished-transaction'),
        );

        return view('po-non-sap/po-transaksi/finished')->with('data', $data);
      }else{
        return redirect('/')->with('err_message', 'Akses Ditolak!');
      }      
    }
    public function PoSupplyPdf($nopo)
    {
      if($this->PermissionActionMenu('transaction')->v==1){
        $po = VwPoPro::where('nopo', $nopo)->first();
        $komponen = VwKomponenTrx::where('po', $nopo)->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($po->uuid));
   
        $data = array(
          'po' => $po,
          'komponen' => $komponen,
          'qrcode' => $qrcode,
        );
          
        $pdf = PDF::loadView('po-non-sap/po-transaksi/print-supplyed', $data);
        
        // return view('po-non-sap/po-management/myPDF')->with('data', $data);
        return $pdf->stream();
      }else{
        return redirect('transaction')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function PoFinishPdf($nopo)
    {
      if($this->PermissionActionMenu('finished-transaction')->v==1){
        $po = VwPoPro::where('nopo', $nopo)->first();
        $komponen = VwKomponenTrx::where('po', $nopo)->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($po->uuid));
   
        $data = array(
          'po' => $po,
          'komponen' => $komponen,
          'qrcode' => $qrcode,
        );
          
        $pdf = PDF::loadView('po-non-sap/po-transaksi/print-finished', $data);
        
        // return view('po-non-sap/po-management/myPDF')->with('data', $data);
        return $pdf->stream();
      }else{
        return redirect('finished-transaction')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function PoHistory($nopo)
    {
      if($this->PermissionActionMenu('transaction')->v==1){
      $po = VwPoPro::where('nopo', $nopo)->first();
      $pro = MstPro::where('pro',  $po->pro)->first();
      $tkomponen =  VwKomponenTrx::where('po', $nopo)->get();
      $komponen = MstKomponen::orderBy('description', 'asc')->get();
      $history = MstHistoryPicking::select('t_history_picking.*','users.name')->join('satria.users', 't_history_picking.created_by', '=', 'users.id')->where('no_po', $nopo)->get();
         
        $data = array(
          'po' => $po,
          'pro' => $pro,
          'tkomponen' => $tkomponen,
          'komponen' => $komponen,
          'history' => $history,
        );
      // echo $count;
        // if($po->flag<=0){
          return view('po-non-sap/po-transaksi/history-po')->with('data', $data);
        // }else{
        //   return redirect('transaction')->with('err_message', 'Akses Ditolak! Status sudah Receive / Finish');
        // }
      }else{
        return redirect('transaction')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function PoTrxView($nopo)
    {
      if($this->PermissionActionMenu('transaction')->v==1){
      $po = VwPoPro::where('nopo', $nopo)->first();
      $pro = MstPro::where('pro',  $po->pro)->first();
      $tkomponen =  VwKomponenTrx::where('po', $nopo)->get();
      $komponen = MstKomponen::orderBy('description', 'asc')->get();
         
        $data = array(
          'po' => $po,
          'pro' => $pro,
          'tkomponen' => $tkomponen,
          'komponen' => $komponen,
        );
      // echo $count;
        if($po->flag<=0){
          return view('po-non-sap/po-transaksi/view-po')->with('data', $data);
        }else{
          return redirect('transaction')->with('err_message', 'Akses Ditolak! Status sudah Receive / Finish');
        }
      }else{
        return redirect('transaction')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function PoRcvView($nopo)
    {
      if($this->PermissionActionMenu('receive-transaction')->v==1){
      $po = VwPoPro::where('nopo', $nopo)->first();
      $pro = MstPro::where('pro',  $po->pro)->first();
      $tkomponen =  VwKomponenTrx::where('po', $nopo)->get();
      $komponen = MstKomponen::orderBy('description', 'asc')->get();
         
        $data = array(
          'po' => $po,
          'pro' => $pro,
          'tkomponen' => $tkomponen,
          'komponen' => $komponen,
        );
      // echo $count;
        return view('po-non-sap/po-transaksi/receive-po')->with('data', $data);
      }else{
        return redirect('receive-transaction')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function PoTrxUpdate(Request $request)
    {
      if($this->PermissionActionMenu('transaction')->r==1){
        $po = MstPo::where('nopo', $request->nopo)->first();
        
        if(!empty($po)){
              if(count($request->pn_patria) > 0)
              {
                // $createpackage = MstPackage::create([
                //   'name' => $request->package,
                // ]);
                foreach($request->pn_patria as $item=>$v){
                    $data2=array(
                      'description'=>$request->description[$item],
                      'pn_vendor'=>$request->pn_vendor[$item],
                      'price' => $request->price[$item],
                      'qty_order'=>$request->quantity[$item],
                      'qty_supply'=>$request->qty_supply[$item],
                      'status'=>1,
                    );
                  Komponen::where('id', $request->idkom[$item])
                  ->update($data2);
                  MstKomponen::where('pn_patria', $request->pn_patria[$item])
                  ->update([
                    'pn_vendor' => $request->pn_vendor[$item],
                    'price' => $request->price[$item],
                      ]
                    );
                }
              }
              MstPo::where('nopo', $request->nopo)
              ->update([
                'flag' => 1,
                  ]
                );
               MstHistoryPicking::insert([
                'no_po' => $request->nopo,
                'created_by'=> Auth::user()->id,
                'flag' => 1,
              ]);
            return redirect('transaction')->with('suc_message', 'Data berhasil Update !');
        }else{
            return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
        }
      }else{
        return redirect('transaction')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function PoTrxReceive(Request $request)
    {
      if($this->PermissionActionMenu('receive-transaction')->r==1){
        $po = MstPo::where('nopo', $request->nopo)->first();
        
        if(!empty($po)){
            if(count($request->id) > 0)
            {
              $sts=2;
              if($request->flag==3){
                $sts=3;
              }else{
                $sts=2;
              }
              foreach($request->id as $item=>$v){
                  $data2=array(
                      
                    'qty_rcv'=>$request->qty_rcv[$item],
                    'qty_use'=>$request->qty_use[$item],
                    'status'=>$sts,
                  );
                Komponen::where('id', $request->id[$item])
                ->update($data2);
              }
            }
            MstPo::where('nopo', $request->nopo)
            ->update([
              'flag' => $sts,
                ]
              );
             MstHistoryPicking::insert([
                'no_po' => $request->nopo,
                'created_by'=> Auth::user()->id,
                'flag' => $sts,
              ]);
          return redirect('receive-transaction')->with('suc_message', 'Data berhasil Update!');
        }else{
            return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
        }
      }else{
        return redirect('receive-transaction')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function PoTrxClose(Request $request)
    {
      if($this->PermissionActionMenu('finished-transaction')->r==1){
        $po = MstPo::where('nopo', $request->nopo)->first();
        
        if(!empty($po)){
          
            MstPo::where('nopo', $request->nopo)
            ->update([
              'flag' => 4,
                ]
              );
             MstHistoryPicking::insert([
                'no_po' => $request->nopo,
                'created_by'=> Auth::user()->id,
                'flag' => 4,
              ]);
          return redirect('finished-transaction')->with('suc_message', 'Data berhasil Update!');
        }else{
            return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
        }
      }else{
        return redirect('finished-transaction')->with('err_message', 'Akses Ditolak!');
      }
    }

    public function PoTrxInvoiced(Request $request)
    {
      if($this->PermissionActionMenu('finished-transaction')->r==1){
        $po = MstPo::where('nopo', $request->nopo)->first();
        
        if(!empty($po)){
          
            MstPo::where('nopo', $request->nopo)
            ->update([
              'is_invoiced' => 1,
                ]
              );
             MstHistoryPicking::insert([
                'no_po' => $request->nopo,
                'created_by'=> Auth::user()->id,
                'flag' => 5,
              ]);
          return redirect()->back()->with('suc_message', 'Data berhasil Update!');
        }else{
            return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
        }
      }else{
        return redirect('finished-transaction')->with('err_message', 'Akses Ditolak!');
      }
    }

}
