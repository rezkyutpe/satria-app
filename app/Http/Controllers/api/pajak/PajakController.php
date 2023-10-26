<?php
   
namespace App\Http\Controllers\api\pajak;
   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Models\Table\Pajak\MstFakturPajak;
use App\Models\Table\Pajak\MstDetailPajak;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
   
class PajakController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $pajak = MstFakturPajak::orderBy('updated_at', 'desc')->limit(5)->get();
  
        return $this->sendResponse($pajak, 'Berhasil Menampilkan Data.');
    }
    public function show(Request $request)
    {
        // $url = 'http://svc.efaktur.pajak.go.id/validasi/faktur/010606028007000/0341653107998/21c3b47965e27e33a92c7ab1ea70de74148aac25c437326aa4b9cb2e2d31180c';
        
        // $response = Http::get($request->id);
        //     $xmlObject = simplexml_load_string($response);
        
        //           $datas = json_decode($response,true);
        //         return $this->sendResponse($xmlObject, 'Nomor Faktur Sudah di Input');

        if($request->nomorfaktur=='0'){
            $response = Http::get($request->id);
            $xmlObject = simplexml_load_string($response);
            $json = json_encode($xmlObject);
            $phpArray = json_decode($json, true); 
            if(isset($phpArray["nomorFaktur"])){


            $faktur =  MstFakturPajak::where('nomorfaktur',$phpArray["nomorFaktur"])->where('fgpengganti',$phpArray["fgPengganti"])->first();
            $detailfaktur =  MstDetailPajak::where('nomorfaktur',$phpArray["nomorFaktur"])->get();
                 $datas = array(
                'pajak' => array($faktur),
                'detail' => $detailfaktur,
              );
            if (!empty($faktur)) {
                return $this->sendResponse($datas, 'Nomor Faktur Sudah di Input');
            }else{
                $referensi="";
                if(isset($phpArray["referensi"])){
                    if($phpArray["referensi"]!=[]){
                        $referensi = $phpArray["referensi"];
                    }
                }
                $pecah = explode('/', $phpArray["tanggalFaktur"]);
                MstFakturPajak::create([
                        "url_scan"=> $request->id,
                        "kdjenistransaksi"=>$phpArray["kdJenisTransaksi"],
                        "fgpengganti"=>$phpArray["fgPengganti"],
                        "nomorfaktur"=>$phpArray["nomorFaktur"],
                        "tanggalfaktur"=>$pecah[2].'-'.$pecah[1].'-'.$pecah[0],
                        "npwppenjual"=>$phpArray["npwpPenjual"],
                        "namapenjual"=>$phpArray["namaPenjual"],
                        "alamatpenjual"=>$phpArray["alamatPenjual"],
                        "npwplawantransaksi"=>$phpArray["npwpLawanTransaksi"],
                        "namalawantransaksi"=>$phpArray["namaLawanTransaksi"],
                        "alamatlawantransaksi"=>$phpArray["alamatLawanTransaksi"],
                        "jumlahdpp"=>$phpArray["jumlahDpp"],
                        "jumlahppn"=>$phpArray["jumlahPpn"],
                        "jumlahppnbm"=>$phpArray["jumlahPpnBm"],
                        "statusapproval"=>$phpArray["statusApproval"],
                        "statusfaktur"=>$phpArray["statusFaktur"],
                        "referensi"=>$referensi,
                        "masa_pajak"=>date('m'),
                        "date_scan"=>date('Y-m-d'),
                        "tahun_pajak"=>date('Y'),
                        "jenis_faktur"=>'FM',
                        "is_creditable"=>1,
                ]);
                if(isset($phpArray["detailTransaksi"]["nama"])){
                	MstDetailPajak::create([
	                        "nomorfaktur"=>$phpArray["nomorFaktur"],
	                        "nama"=>$phpArray["detailTransaksi"]["nama"],
	                        "hargasatuan"=>$phpArray["detailTransaksi"]["hargaSatuan"],
	                        "jumlahbarang"=>$phpArray["detailTransaksi"]["jumlahBarang"],
	                        "hargatotal"=>$phpArray["detailTransaksi"]["hargaTotal"],
	                        "diskon"=>$phpArray["detailTransaksi"]["diskon"],
	                        "dpp"=>$phpArray["detailTransaksi"]["dpp"],
	                        "ppn"=>$phpArray["detailTransaksi"]["ppn"],
	                        "tarifppnbm"=>$phpArray["detailTransaksi"]["tarifPpnbm"],
	                        "ppnbm"=>$phpArray["detailTransaksi"]["ppnbm"],
	                	]);
                }else{
                	for ($i=0; $i < count($phpArray["detailTransaksi"]); $i++) { 
	                    MstDetailPajak::create([
	                        "nomorfaktur"=>$phpArray["nomorFaktur"],
	                        "nama"=>$phpArray["detailTransaksi"][$i]["nama"],
	                        "hargasatuan"=>$phpArray["detailTransaksi"][$i]["hargaSatuan"],
	                        "jumlahbarang"=>$phpArray["detailTransaksi"][$i]["jumlahBarang"],
	                        "hargatotal"=>$phpArray["detailTransaksi"][$i]["hargaTotal"],
	                        "diskon"=>$phpArray["detailTransaksi"][$i]["diskon"],
	                        "dpp"=>$phpArray["detailTransaksi"][$i]["dpp"],
	                        "ppn"=>$phpArray["detailTransaksi"][$i]["ppn"],
	                        "tarifppnbm"=>$phpArray["detailTransaksi"][$i]["tarifPpnbm"],
	                        "ppnbm"=>$phpArray["detailTransaksi"][$i]["ppnbm"],
	                	]);
	                }
                }
                $pajak =  MstFakturPajak::where('nomorfaktur',$phpArray["nomorFaktur"])->first();
                $detail =  MstDetailPajak::where('nomorfaktur',$phpArray["nomorFaktur"])->get();
                 $data = array(
                'pajak' => array($pajak),
                'detail' => $detail,
              );
                return $this->sendResponse($data, 'Nomor Faktur Berhasil Disimpan');
            }
        }else{
        	return $this->sendError($phpArray["statusApproval"],$phpArray["statusApproval"]);
        }
        }else{
            $datafaktur =  MstFakturPajak::where('nomorfaktur',$request->nomorfaktur)->first();
            $detailtrx =  MstDetailPajak::where('nomorfaktur',$request->nomorfaktur)->get();
                 $datas = array(
                'pajak' => array($datafaktur),
                'detail' => $detailtrx,
              );
            if (!empty($datafaktur)) {
                return $this->sendResponse($datas, 'Nomor Faktur Sudah di Input');
            }else{
                 return $this->sendError('Nomor Faktur Not Found',$datas);
            }
        }  
    }
    public function search($id)
    {
       
        $faktur =  MstFakturPajak::where('nomorfaktur',$id)->first();
        if (empty($faktur)) {
            return $this->sendError('Nomor Faktur Not Found',$faktur);
        }else{
            
            return $this->sendResponse($faktur, 'Berhasil Menampilkan Data.');
        }
    }
}