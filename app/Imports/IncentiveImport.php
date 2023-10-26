<?php
namespace App\Imports;
 
use App\Http\Controllers\Controller;
use App\Models\Table\Incentive\Incentive;
use Maatwebsite\Excel\Concerns\ToModel;
 
class IncentiveImport  extends Controller implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Incentive([
            'inv' => $row[2],
            'inv_date' => $row[3], 
            'cash_date' => $row[4], 
            'sales' => $row[0], 
            'sales_name' => $row[1], 
            'id_cust' => $row[5], 
            'customer' => $row[6], 
            'cust_profile' => $row[7], 
            'product' => $row[8], 
            'segment' => $row[9], 
            'qty' => $row[10], 
            'tot_cost' => $row[11], 
            'tot_price' => $row[12]*$row[10], 
            'gpm'=> $this->Gpm($row[11],$row[12]*$row[10],$row[9]),
            'aging'=> $this->Aging($row[3],$row[4],$row[7],$row[9]),
            'target'=>0,
            'inc_ef'=> $this->IncEF($row[9]),
        ]);
    }
    
}