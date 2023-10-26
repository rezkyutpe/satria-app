<?php
  
namespace App\Http\Controllers\Cms\PoNonSAP;
use App\Http\Controllers\Controller;
  
use Illuminate\Http\Request;
use App\Models\TPajak\MstPajak;
use PDF;
use Illuminate\Support\Facades\Http;
  
class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function PajakMgmtInit(Request $request)
    {
      //   $paginate = 15;
      //       $pajak = MstPajak::orderBy('nomorfaktur', 'asc')->simplePaginate($paginate);
      //   $data = array(
      //     'pajak' => $pajak
      //   );
      // // echo $count;
      
      //   return view('user-management/pajak')->with('data', $data);

    }
}