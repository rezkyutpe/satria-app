<?php

namespace App\Exports\POTracking;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\View\PoTracking\VwLocalnewpo;
use App\Models\View\PoTracking\VwOngoinglocal;
use App\Models\View\PoTracking\VwHistoryLocal;
use App\Models\View\PoTracking\VwSubcontNewpo;
use App\Models\View\PoTracking\VwSubcontOngoing;
use App\Models\View\PoTracking\VwSubcontHistory;
use App\Models\Table\PoTracking\Po;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Contracts\View\View;

class DownloadPO implements FromView

{
    protected $id;
    function __construct($id,$status) {
            $this->id       = $id;
            $this->status   = $status;
    }

        public function view(): View
        {
            $getData =  Po::where('VendorCode',Auth::user()->email)->orWhere('CreatedBy',Auth::user()->email)->first();
          
            if (isset($getData)){
                if(!empty($this->id == "polocalnewpo")){
                    $sql = VwLocalnewpo::where('VendorCode',Auth::user()->email)->orWhere('NRP',Auth::user()->email)->get();
                }elseif(!empty($this->id == "polocalongoing" )){
                    $sql = VwOngoinglocal::where('VendorCode',Auth::user()->email)->orWhere('NRP',Auth::user()->email)->get();
                }elseif(!empty($this->id == "polocalhistory")){
                    $sql = VwHistoryLocal::where('VendorCode',Auth::user()->email)->orWhere('NRP',Auth::user()->email)->get();
                }elseif(!empty($this->id == "subcontractornewpo")){
                    $sql = VwSubcontNewpo::where('VendorCode',Auth::user()->email)->orWhere('NRP',Auth::user()->email)->get();
                }elseif(!empty($this->id == "subcontractorongoing")){
                    $sql = VwSubcontOngoing::where('VendorCode',Auth::user()->email)->orWhere('NRP',Auth::user()->email)->get();
                }elseif(!empty($this->id == "subcontractorhistory")){
                    $sql = VwSubcontHistory::where('VendorCode',Auth::user()->email)->orWhere('NRP',Auth::user()->email)->get();
                }
                else{
                    $sql = [1, 2, 3];
                }
            }else{
                if(!empty($this->id == "polocalnewpo")){
                    $sql = VwLocalnewpo::all();
                }elseif(!empty($this->id == "polocalongoing" )){
                    $sql = VwOngoinglocal::all();
                }elseif(!empty($this->id == "polocalhistory")){
                    $sql = VwHistoryLocal::all();
                }elseif(!empty($this->id == "subcontractornewpo")){
                    $sql = VwSubcontNewpo::all();
                }elseif(!empty($this->id == "subcontractorongoing")){
                    $sql = VwSubcontOngoing::all();
                }elseif(!empty($this->id == "subcontractorhistory")){
                    $sql = VwSubcontHistory::all();
                }
                else{
                    $sql = [1, 2, 3];
                }
            }

            if(!empty($this->id == "polocalnewpo") || (!empty($this->id == "subcontractornewpo") )){
                $url = "DownloadNewPo";
                $menu = "newpo";
            }elseif(!empty($this->id == "polocalongoing" )){
                $url = "DownloadOngoing";
                $menu = "ongoinglocal";
            }elseif(!empty($this->id == "subcontractorongoing")){
                $url = "DownloadOngoing";
                $menu = "ongoingsubcount";
            }elseif(!empty($this->id == "subcontractorhistory" || !empty($this->id == "polocalhistory"))){
                $url = "DownloadHistory";
                $menu = "history";
            }
            else{
                $sql = [1, 2, 3];
            }

            if(isset($getData) && $getData->VendorCode == Auth::user()->email){
                $category = 1 ;
            }else{
                $category = 2 ;
            }

            return view("po-tracking/master/$url", [
                'dB' => $sql,
                'menu' => $menu,
                'category' => $category,
                'status' => $this->status,
            ]);

        }

}
