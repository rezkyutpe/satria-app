<?php
   
namespace App\Http\Controllers\api\pononsap;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Table\PoNonSAP\MstPo;
use App\Models\View\PoNonSAP\VwPoPro;
use App\Models\Table\PoNonSAP\MstKomponen;
use App\Models\Table\PoNonSAP\Komponen;
use App\Models\View\PoNonSAP\VwKomponenPro;
   
class PickingController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $picking = VwPoPro::where('flag','>=',1)->orderBy('updated_at', 'desc')->limit(5)->get();
  
        return $this->sendResponse($picking, 'Berhasil Menampilkan Data.');
    }
    public function show(Request $request)
    {
        if($request->no_picking!=0){
            $picking =  VwPoPro::where('nopo','like', "%" . $request->no_picking. "%")->first();
        }else{
            $picking =  VwPoPro::where('uuid',$request->id)->first();
        }
        if (empty($picking)) {
            return $this->sendError('Data tidak ditemukan');
        }
        
        $tkomponen = VwKomponenPro::where('po', $picking->nopo)->get();
           
        $data = array(
            'picking' =>array($picking),
            'tkomponen' => $tkomponen,
        );
       
        return $this->sendResponse($data, 'Berhasil Menampilkan Data.');
    }
    public function search($id)
    {
       
        $picking =  VwPoPro::where('nopo',$id)->first();
        $tkomponen = VwKomponenPro::where('po', $id)->get();
           
        $data = array(
            'picking' => array($picking),
            'tkomponen' => $tkomponen,
          );
        if (empty($picking)) {
            return $this->sendError('Data tidak ditemukan');
        }
        
        return $this->sendResponse($data, 'Berhasil Menampilkan Data.');
    }
    public function received(Request $request)
    {
        $picking =  VwPoPro::where('uuid',$request->id)->first();
        
        if (empty($picking)) {
            return $this->sendError('Data tidak ditemukan');
        }else{
            MstPo::where('nopo', $picking->nopo)
                ->update([
                'flag' => 2,
                ]
            );
            Komponen::where('po', $picking->nopo)
                ->update([
                    'status' => 2,
            ]);
            return $this->sendResponse($picking, 'Berhasil Mengupdate Data.');
        }
    }
    public function finished(Request $request)
    {
        $picking =  VwPoPro::where('uuid',$request->id)->first();
        
        if (empty($picking)) {
            return $this->sendError('Data tidak ditemukan');
        }else{
            MstPo::where('nopo', $picking->nopo)
                ->update([
                'flag' => 3,
                ]
            );
            Komponen::where('po', $picking->nopo)
                ->update([
                    'status' => 3,
            ]);
            return $this->sendResponse($picking, 'Berhasil Mengupdate Data.');
        }
    }
    public function update(Request $request)
    {
        $tkomponen = Komponen::where('id',$request->idkom)->first();
        
        if (empty($tkomponen)) {
            return $this->sendError('Data tidak ditemukan');
        }else{
            Komponen::where('id', $request->idkom)
                ->update([
                    'qty_use' => $request->qty_use,
            ]);
          
            return $this->sendResponse($tkomponen, 'Berhasil Mengupdate Data.');
        }
    }
}