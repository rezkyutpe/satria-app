<?php
   
namespace App\Http\Controllers\api\ccr;
   
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Models\Table\CompletenessComponent\MaterialTemporary;
use App\Models\View\CompletenessComponent\VwPowerBIMaterial;
use App\Models\View\CompletenessComponent\VwPowerBIPro;
use App\Models\View\CompletenessComponent\VwPowerBISn;
use App\Models\View\CompletenessComponent\VwProWithSN;
use Illuminate\Support\Facades\DB;
   
class CCRController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

    public function dataPRO(Request $request) {
        if ($request->header('Authorization') == "P@s5w0rd_P0w3rBI") {
            $pro =  VwPowerBIPro::get()->toArray();
            return $this->sendResponse($pro, 'Berhasil Menampilkan Data.');
        } else {
            return $this->sendError('Token Not Found');
        }
        
    }

    public function dataSN(Request $request) {
        if ($request->header('Authorization') == "P@s5w0rd_P0w3rBI") {
            $pro =  VwPowerBISn::get()->toArray();
            return $this->sendResponse($pro, 'Berhasil Menampilkan Data.');
        } else {
            return $this->sendError('Token Not Found');
        }
    }

    public function dataMaterialOnGoing(Request $request) {
        if ($request->header('Authorization') == "P@s5w0rd_P0w3rBI") {
            $pro =  VwPowerBIMaterial::get()->toArray();
            return $this->sendResponse($pro, 'Berhasil Menampilkan Data.');
        } else {
            return $this->sendError('Token Not Found');
        }
    }
    
}