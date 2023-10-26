<?php
  
namespace App\Http\Controllers\Cms\Pajak;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Table\Pajak\MstFakturPajak;
use App\Models\Table\Pajak\MstVendor;
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
  
class VendorManController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('vendor-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }
	public function VendorMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('vendor-management')->r==1){
            $paginate = 150;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $vendor = MstVendor::where('nama_vendor', 'like', "%" . $search. "%")->orderBy('nama_vendor', 'asc')->simplePaginate($paginate);
                $vendor->appends(['search' => $search]);
            } else {
                $vendor = MstVendor::orderBy('nama_vendor', 'asc')->simplePaginate($paginate);
            }
            // $vendor = MstVendor::with(['countrys'])->where('role_id', 1)->get();
            $no = 1;
            foreach($vendor as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'vendor' => $vendor,
                'actionmenu' => $this->PermissionActionMenu('vendor-management'),
            );
            return view('pajak/vendor-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function VendorMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('vendor-management')->c==1){
            $apps = MstVendor::where('npwp', $request->npwp)->first();
            if(empty($apps)){
            $create = MstVendor::create([
                'kode_vendor'=>$request->kode_vendor,
                'title'=>$request->title,
                'nama_vendor'=>$request->nama_vendor,
                'npwp'=>$request->npwp,
                'pic'=>$request->pic,
                'alamat'=>$request->alamat,
                'kota'=>$request->kota,
                'created_by'=>Auth::user()->id,
            ]);
                if($create){
                    return redirect('vendor-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Apps Name Already Exist!');
            }
        }else{
            return redirect('vendor-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function VendorMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('vendor-management')->u==1){
            $apps = MstVendor::where('id', $request->id)->first();
            if(!empty($apps)){
                $update = MstVendor::where('id', $request->id)
                ->update([
                    'kode_vendor'=>$request->kode_vendor,
                    'title'=>$request->title,
                    'nama_vendor'=>$request->nama_vendor,
                    'npwp'=>$request->npwp,
                    'pic'=>$request->pic,
                    'alamat'=>$request->alamat,
                    'kota'=>$request->kota,
                    'updated_by'=>Auth::user()->id,
                ]);
                if($update){
                    return redirect('vendor-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('vendor-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function VendorMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('vendor-management')->d==1){
            $apps = MstVendor::where('id', $request->id)->first();
            if(!empty($apps)){
                MstVendor::where('id', $request->id)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('vendor-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}