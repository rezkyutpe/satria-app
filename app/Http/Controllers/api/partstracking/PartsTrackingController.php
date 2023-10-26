<?php
   
namespace App\Http\Controllers\api\partstracking;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Table\PartsTracking\PartsTrx;
use App\Models\Table\PartsTracking\History;
use App\Models\Table\PartsTracking\Lokasi;
use App\Models\Table\PartsTracking\Kondisi;
use App\Models\View\PartsTracking\VwPartsTracking;
class PartsTrackingController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        $user =  User::where('token',$request->uid)->first();
        if (empty($user)) {
            return $this->sendError('Token Unauthorised');
        }
        $inc = VwPartsTracking::get();
  
        return $this->sendResponse($inc, 'Berhasil Menampilkan Data.');
    }
    public function getLokasi(Request $request)
    {
        $inc = Lokasi::get();
  
        return $this->sendResponse($inc, 'Berhasil Menampilkan Data.');
    }
    public function getKondisi(Request $request)
    {
        
        $inc = Kondisi::get();
  
        return $this->sendResponse($inc, 'Berhasil Menampilkan Data.');
    }
    public function show(Request $request)
    {
        $user =  User::where('token',$request->uid)->first();  
        if (empty($user)) {
            return $this->sendError('Token Unauthorised');
        }
        $inc = VwPartsTracking::where('id_transaksi',  $request->id)->get();
           
        
        if ($inc=='[]') {
            return $this->sendError('QR Code Data Tidak Ditemukan');
        }else{
            return $this->sendResponse($inc, 'Berhasil Menampilkan Data.');
        }
    }
    public function update(Request $request)
    {
        $user =  User::where('token',$request->uid)->first();
        if (empty($user)) {
            return $this->sendError('Token Unauthorised');
        }else{
            
                PartsTrx::where('id_transaksi', $request->id)
                    ->update([
                    'kondisi_transaksi'=>$request->kondisi,
                    'lokasi' => $request->lokasi,
                ]);
                History::insert([
                    'transaksi'=>$request->id,
                    'kondisi'=>$request->kondisi,
                    'lokasi'=>$request->lokasi,
                    'created_by'=> $user->name,
                ]);
                
                return $this->sendResponse([], 'Berhasil Mengupdate Data.');
        }
    }
}