<?php

namespace App\Http\Controllers\Cms\PartsTracking;
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
use App\Models\Table\PartsTracking\JenisHose;
use App\Models\Table\PartsTracking\KonfHose;
use App\Models\Table\PartsTracking\Diameter;
use App\Models\Table\PartsTracking\Fitting;
use App\Models\Table\PartsTracking\Lokasi;
use App\Models\Table\PartsTracking\Kondisi;
use App\Models\Table\PartsTracking\KondisiMwp;
use App\Models\Table\PartsTracking\SnUnit;
use App\Models\Table\PartsTracking\Aplikasi;
use App\Models\Table\PartsTracking\Lifetime;
use App\Models\Table\PartsTracking\PartsTrx;
use App\Models\Table\PartsTracking\PartsHistory;
use App\Models\View\PartsTracking\VwPartsTracking;
use App\Models\View\PartsTracking\VwHistoryTrx;
use App\Models\View\PartsTracking\VwHistoryPn;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\View\VwPermissionAppsMenu;

class PartsTrxController extends Controller
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

    public function PartsTrxInit(Request $request)
    {
      if($this->PermissionActionMenu('parts-transaction')->r==1){
          $paginate = 1500;
          if (isset($request->query()['search'])){
              $search = $request->query()['search'];
              $parts = VwPartsTracking::where('id_transaksi', 'like', "%" . $search. "%")->orderBy('tgl_transaksi', 'desc')->simplePaginate($paginate);
              $parts->appends(['search' => $search]);
          } else {
              $parts = VwPartsTracking::orderBy('tgl_transaksi', 'desc')->simplePaginate($paginate);
          }
          
          $no = 1;
          $snunit = SnUnit::get();
          foreach($parts as $data){
              $data->no = $no;
              $no++;
          }
          $data = array(
            'parts' => $parts,
            'snunit' => $snunit,
            'actionmenu' => $this->PermissionActionMenu('parts-transaction'),
          );

          return view('parts-tracking/parts-transaction/index')->with('data', $data);
      }else{
        return redirect('/')->with('err_message', 'Akses Ditolak!');
      }  
    } 
    public function PartsTrxAdd()
    {
      if($this->PermissionActionMenu('parts-transaction')->c==1){
      $now = date('Y-m');
      $tgl    = date('my');
      $custom = "HT" . $tgl;
      $custId = PartsTrx::where('tgl_transaksi','like', $now. "%")->max('id');
      $delfront = substr($custId , -8, 4);
      $no = ((int) $delfront + 1);
      $cus_id = str_pad($no, 4, "0", STR_PAD_LEFT) . $tgl;
      
      $jenishose =  JenisHose::get();
      $konfhose =  KonfHose::get();
      $diameter =  Diameter::get();
      $fitting =  Fitting::get();
      $lokasi =  Lokasi::get();
      $kondisi =  Kondisi::get();
      $snunit =  SnUnit::get();
      $aplikasi =  Aplikasi::get();
      $lifetime =  Lifetime::get();
         
        $data = array(
          'cus_id' => $cus_id,
          'jenishose' => $jenishose,
          'konfhose' => $konfhose,
          'diameter' => $diameter,
          'fitting' => $fitting,
          'lokasi' => $lokasi,
          'kondisi' => $kondisi,
          'snunit' => $snunit,
          'aplikasi' => $aplikasi,
          'lifetime' => $lifetime,
        );
        return view('parts-tracking/parts-transaction/add')->with('data', $data);
      }else{
        return redirect('parts-transaction')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function PartsTrxInsert(Request $request)
    {
      if($this->PermissionActionMenu('parts-transaction')->c==1){

        $now  = date('Y-m-d', strtotime($request->tgl_transaksi));
        $life = "+" . $request->lifetime . " days";
        $create = PartsHistory::create([
      			'transaksi' => $request->id,
      			'kondisi'    => $request->kondisi_transaksi,
            'lokasi'      => $request->lokasi,
      			'created_by' => Auth::user()->name,
        ]);
        if($create){
            $parts = PartsTrx::create([

            'id'      => $request->id,
            'id_transaksi'      => $request->id_transaksi,
            'tgl_transaksi'     => $request->tgl_transaksi,
            'hose_transaksi'    => $request->hose_transaksi,
            'konf_transaksi'    => $request->konf_transaksi,
            'diameter'          => $request->diameter,
            'panjang'           => $request->panjang,
            'fitting1'          => $request->fitting1,
            'ukuran1'           => $request->ukuran1,
            'fitting2'          => $request->fitting2,
            'ukuran2'           => $request->ukuran2,
            'sn_unit'           => $request->sn_unit,
            'aplikasi'          => $request->aplikasi,
            'customer'          => 0,
            'pn_assy'           => $request->pn_assy,
            'lokasi'            => $request->lokasi,
            'kondisi_transaksi' => $request->kondisi_transaksi,
            'lifetime'          => $request->lifetime,
            'tgl_lifetime'      => date('Y-m-d', strtotime($life, strtotime($now))),
            'status'          => $request->status,
                'created_by' => Auth::user()->id,
            ]);
            return redirect('parts-transaction')->with('suc_message', 'Data berhasil ditambahkan!');
        }else{
            return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
        }
      }else{
        return redirect('parts-transaction')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function PartsTrxView ($id_transaksi)
    {
      if($this->PermissionActionMenu('parts-transaction')->v==1){
      $parts = VwPartsTracking::where('id_transaksi', $id_transaksi)->first();
      $historytrx =  VwHistoryTrx::where('transaksi', $parts->id)->orderBy('id','asc')->get();
      $historypn =  VwHistoryPn::where('id', $parts->id)->orderBy('tgl_transaksi','asc')->get();
         
        $data = array(
          'parts' => $parts,
          'historytrx' => $historytrx,
          'historypn' => $historypn,
        );
     
        return view('parts-tracking/parts-transaction/view')->with('data', $data);
      }else{
        return redirect('parts-transaction')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function PartsTrxPrint ($id_transaksi)
    {
      if($this->PermissionActionMenu('parts-transaction')->d==1){
      $parts = VwPartsTracking::where('id_transaksi', $id_transaksi)->first();
         
        $data = array(
          'parts' => $parts,
        );
     
        return view('parts-tracking/parts-transaction/print')->with('data', $data);
      }else{
        return redirect('parts-transaction')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function export(Request $request)
    {
      // $item = $this->post_model->export_transaksi();
      $date = $request->date;
      $sn = $request->sn;
      $start=substr($date,0,10)." 00:00:00";
      $end=substr($date,13,23)." 23:59:59";
      // $between=str_replace("/", "-",$date);
      // echo $date.'<br>';
      // echo $between;
      if ($date==null & $sn=='null') {
        $item = VwPartsTracking::orderBy('tgl_transaksi', 'desc')->get();
      }else if($date!=null & $sn==null){
        $item = VwPartsTracking::whereBetween('tgl_transaksi', [$start,$end])->orderBy('tgl_transaksi', 'desc')->get(); 
      }else if($sn!=null & $date==null){
        $item = VwPartsTracking::where('id_unit', $sn)->orderBy('tgl_transaksi', 'desc')->get(); 
      }else{
        $item = VwPartsTracking::where('id_unit', $sn)->whereBetween('tgl_transaksi', [$start,$end])->orderBy('tgl_transaksi', 'desc')->get(); 
      }
      $filename = 'Parts-Tracking-'.Date('Ymd').'-Rekap-Data-Transaksi.xls';
              header("Content-type: application/vnd-ms-excel");
              header("Content-Disposition: attachment; filename=".$filename);
      // echo $request->date;
      echo '
  
          <center><h2>Rekap Data Transaksi </h2> ('.$request->date.')</center><br>
          <table border="1" width="100%">
              <thead>
                  <tr>
                  <th>ID</th>
                  <th>Status</th>
                  <th>Tanggal_Transaksi</th>
                  <th>Jenis_Hose</th>
                  <th>Konfigurasi_Hose</th>
                  <th>Diameter</th>
                  <th>Panjang</th>
                  <th>Nama_Fitting_1</th>
                  <th>Ukuran_1</th>
                  <th>Nama_Fitting_2</th>
                  <th>Ukuran_2</th>
                  <th>SN_Unit</th>
                  <th>Aplikasi</th>
                  <th>PN_Assy</th>
                  <th>Lokasi</th>
                  <th>Kondisi</th>
                  <th>MWP</th>
                  <th>MBP</th>
                  <th>Lifetime</th>
                  <th>Tanggal Lifetime</th>
                  </tr>
              </thead>';
               $i=1; foreach ($item as $key){ $no= $i++;
                 $now  = new DateTime(date('Y-m-d'));
                 $life = new DateTime(date('Y-m-d', strtotime($key['tgl_lifetime'])));
                 $sisa = $life->diff($now)->days;
                 $status='';
                 if ($key['status']==1) {
                   $status='New';
                 }else {
                   $status='Old';
                 }
                  echo "
                      <tr>
                          <td>".$key['id_transaksi']."</td>
                          <td>".$status."</td>
                          <td>".date('Y-m-d H:i:s', strtotime($key['tgl_transaksi']))."</td>
                          <td>".$key['hose_transaksi']."</td>
                          <td>".$key['konf_transaksi']."</td>
                          <td>".$key['diameter']."</td>
                          <td>".$key['panjang']."</td>
                          <td>".$key['fitting1']."</td>
                          <td>".$key['ukuran1']."</td>
                          <td>".$key['fitting2']."</td>
                          <td>".$key['ukuran2']."</td>
                          <td>".$key['sn_unit']."</td>
                          <td>".$key['nama_app']."</td>
                          <td>".$key['pn_assy']."</td>
                          <td>".$key['lokasi']."</td>
                          <td>".$key['kondisi_transaksi']."</td>
                          <td>".$key['mwp']."</td>
                          <td>".$key['mwp']."</td>
                          <td>".$sisa." Days</td>
                          <td>".date('Y-m-d', strtotime($key['tgl_lifetime'])) ."</td>
  
                      </tr>
                  ";
  
  
               }
               echo "</table>";
      }
    public function PartFetchMwp($jhose,$diameter)
    {
        $query = KondisiMwp::where('jhose', $jhose)->where('diameter',$diameter)->get();
        $output = "";
        foreach ($query as $row) {
            $output .= $row->id_mwp;
        }
        return $output;
    }
    public function PartFetchFitting($fitting_id)
    {
        if ($fitting_id == 0) {
            $where = "0";
        } else {
          $urut = Fitting::where('id_fitting', $fitting_id)->first();
          $where = $urut->no_urut;
        }
        
        $query = Fitting::where('no_urut','>=', $where)->orderBy('no_urut','asc')->get();
        $output = "<option value=\"\">Nothing selected</option>";
        foreach ($query as $row) {
            $output .= '<option value="' . $row->id_fitting . '">' . $row->nama_fitting . '</option>';
        }
        return $output;
    }
    public function PartFetchUkuranfitting($ukuran)
    {
        if ($ukuran == 0) {
            $where = "0";
        } else {
            $urut = Diameter::where('id_diameter', $ukuran)->first();
            $where = $urut->no_urut;
        }
        $query = Diameter::where('no_urut','>=', $where)->orderBy('no_urut','asc')->get();
        $output = "<option value=\"\">Nothing selected</option>";
        foreach ($query as $row) {
            $output .= '<option value="' . $row->id_diameter . '">' . $row->ukuran_diameter . '</option>';
        }
        return $output;
    }
    public function PartFetchFittingsize($fitting, $diameter)
    {
        if ($fitting == 0) {
          $res = Fitting::get();
        } else {
          $res = Fitting::where('id_fitting', $fitting)->get();
        }
        // $res  = $this->db->query("SELECT size FROM t_fitting $where ");
        $size = "";
        foreach ($res as $row) {
            $size .= $row->size;
        }
        if ($size == "") {
            $size = "-%";
        } else {
            $size = "%$size";
        }
        if ($diameter == 0) {
            $query = Diameter::where('ukuran_diameter','like', $size)->orderBy('no_urut','asc')->get();
        } else {
            $urut = Diameter::where('id_diameter', $diameter)->first();
            $query = Diameter::where('ukuran_diameter','like', $size)->orderBy('no_urut','asc')->where('no_urut','>=',$urut->no_urut)->get();
            // $whered = "and no_urut >= (SELECT no_urut FROM t_diameter WHERE id_diameter='$diameter')";
        }
        
        $output = "<option value=\"\">Nothing selected</option>";
        // $query = $this->db->query("SELECT * FROM t_diameter  $wheres  $whered order by no_urut asc");
        foreach ($query as $row) {
            $output .= '<option value="' . $row->id_diameter . '">' . $row->ukuran_diameter . '</option>';
        }
        return $output;
    }
}
