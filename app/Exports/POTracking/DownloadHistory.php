<?php

namespace App\Exports\POTracking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Table\PoTracking\LogHistory;
use App\Models\Table\PoTracking\UserVendor;
use App\Models\Table\PoTracking\MigrationProcurementPO;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use App\Models\User;

class DownloadHistory implements FromCollection, WithHeadings, WithStrictNullComparison

{
    protected $id;
    function __construct($id) {
            $this->id    = $id;
    }

    public function headings(): array
    {
        return [
            "User Code","User Name","Login Type","Menu","Access Name","PO NO","PO ITEM","Date","Time"
            ];
    }

    public function collection()
    {

        $data = UserVendor::all();
            foreach($data as $item){
                $vendor[] = $item->VendorCode ;
            }
        if($this->id == 1 ){
            $getData = LogHistory::select('user', 'CreatedBy', 'userlogintype', 'menu', 'description','ponumber','poitem','date','time')->whereNotIn('user', $vendor)->orderBy('id', 'desc')->get();
            foreach($getData as $a){
                if(strpos($a->user , 'PROC-S') !== false){
                    $val1 = MigrationProcurementPO::where('procurement',$a->user)->select('name')->first();
                    $data_user = User::where('name','like','%'.$val1->name.'%')->first();
                    if(isset($data_user)){
                        $a->setAttribute('CreatedBy',$data_user->name);
                        $a->setAttribute('userlogintype',$data_user->title);
                    }
                }
            }
        }else{
            $getData = LogHistory::select('user', 'CreatedBy', 'vendortype', 'menu', 'description','ponumber','poitem','date','time')->whereIn('user', $vendor)->orderBy('id', 'desc')->get();
        }

        $sql = $getData;
        return collect($sql);
    }
}
