<?php
  
namespace App\Http\Controllers\Cms\Pajak;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Table\Pajak\MstFakturPajak;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\Table\Pajak\MstDetailPajak;
use PDF;
use Illuminate\Support\Facades\Http;
use Auth;
use Storage;
use Exception;
use Excel;
use App\Exports\DownloadExport;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
  
class FakturPajakManController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('pajak-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function PajakMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('pajak-management')->r==1){
            $paginate = 15;
            if (isset($request->query()['date'],$request->query()['status'])){
                $status = $request->query()['status'];
                $date = $request->query()['date'];
                
                $pajak = MstFakturPajak::where('export',$status)->where('date_scan', 'like', "%" . $date. "%")->orderBy('date_scan', 'asc')->get();
            }else if(isset($request->query()['date'])){
                $date = $request->query()['date'];
                $pajak = MstFakturPajak::where('date_scan', 'like', "%" . $date. "%")->orderBy('date_scan', 'asc')->get();
            } else if(isset($request->query()['status'])){
                $status = $request->query()['status'];
                $pajak = MstFakturPajak::where('export',$status)->orderBy('date_scan', 'asc')->get();
            } else {
                $pajak = MstFakturPajak::where('export','N')->orderBy('date_scan', 'desc')->limit(1000)->get();
            }
            $no = 1;
            foreach($pajak as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
            'pajak' => $pajak
            );
        // echo $count;
        
            return view('pajak/faktur-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function exportPajak(Request $request)
    {
        if($this->PermissionActionMenu('pajak-management')->v==1){
            $date=isset($request->query()['date']) ? $request->query()['date'] : null;
            $status = isset($request->query()['status']) ? $request->query()['status'] : null;
            $id='';
            if ($date == 0) {
                return redirect()->back()->with('err_message', 'Bulan Tidak Boleh Kosong!');
            }else{
                $nama_file = 'masapajak-'."-".date('dmYHis').'.xlsx';
                return Excel::download(new DownloadExport($date,$status,$id), $nama_file);
            }
        }else{
            return redirect('pajak-management')->with('err_message', 'Akses Ditolak!');
        }

    }
    public function exportFaktur($id)
    {
        if($this->PermissionActionMenu('pajak-management')->v==1){
            $date=null;
            $status=null;
            $nama_file = 'masapajak-'."-".date('dmYHis').'.xlsx';
            return Excel::download(new DownloadExport($date,$status,$id), $nama_file);
        }else{
            return redirect('pajak-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function FakturPrint($id)
    {
      if($this->PermissionActionMenu('pajak-management')->v==1){
        $faktur = MstFakturPajak::where('id',$id)->first();
        $detailfaktur =  MstDetailPajak::where('nomorfaktur',$faktur->nomorfaktur)->get();

        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($faktur->url_scan));

        $data = array(
          'faktur' => $faktur,
          'detailfaktur' => $detailfaktur,
          'qrcode' => $qrcode,
        );
          
        $pdf = PDF::loadView('pajak/faktur-management/print-faktur', $data);
        
        // return view('po-non-sap/po-management/myPDF')->with('data', $data);
        return $pdf->stream();
      }else{
        return redirect('pajak-management')->with('err_message', 'Akses Ditolak!');
      }
    }
}