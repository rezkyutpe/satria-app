<?php
namespace App\Http\Controllers\Cms\PoTracking;

use App\Http\Controllers\Controller;
use App\Models\Table\PoTracking\DataKbli;
use App\Models\Table\PoTracking\UserVendor;
use Illuminate\Support\Facades\Storage;
use Response;
use Illuminate\Http\Request;


class FileVendorController extends Controller
{
    public function fileKbli($id)
    {     
        
        $fileData = DataKbli::where('VendorCode', $id)->first(); // Mengambil data file dari database berdasarkan vendorcode
        $file = public_path()."/potracking/vendor/KBLIFILE/$fileData->FileKbli";
       
        return response()->file($file);

    }

    public function fileNib($id)
    {     
        
        $fileData = UserVendor::where('VendorCode', $id)->first(); // Mengambil data file dari database berdasarkan vendorcode
        $file = public_path()."/potracking/vendor/NIB/$fileData->FileNib";
       
        return response()->file($file);

    }

    public function fileNpwp($id)
    {     
        
        $fileData = UserVendor::where('VendorCode', $id)->first(); // Mengambil data file dari database berdasarkan vendorcode
        $file = public_path()."/potracking/vendor/NPWP/$fileData->FileNpwp";
       
        return response()->file($file);

    }

    public function fileSkdp($id)
    {     
        
        $fileData = UserVendor::where('VendorCode', $id)->first(); // Mengambil data file dari database berdasarkan vendorcode
        $file = public_path()."/potracking/vendor/KBLIFILE/$fileData->FileKbli";
       
        return response()->file($file);

    }

    public function fileSkt($id)
    {     
        
        $fileData = UserVendor::where('VendorCode', $id)->first(); // Mengambil data file dari database berdasarkan vendorcode
        $file = public_path()."/potracking/vendor/NIB/$fileData->FileNib";
       
        return response()->file($file);

    }

    public function fileSkpkp($id)
    {     
        
        $fileData = UserVendor::where('VendorCode', $id)->first(); // Mengambil data file dari database berdasarkan vendorcode
        $file = public_path()."/potracking/vendor/NPWP/$fileData->FileNpwp";
       
        return response()->file($file);

    }

    public function fileAhu($id)
    {     
        
        $fileData = UserVendor::where('VendorCode', $id)->first(); // Mengambil data file dari database berdasarkan vendorcode
        $file = public_path()."/potracking/vendor/AHU/$fileData->FileAhu";
       
        return response()->file($file);
    }

    public function fileKtpDireksi($id)
    {     
        
        $fileData = UserVendor::where('VendorCode', $id)->first(); // Mengambil data file dari database berdasarkan vendorcode
        $file = public_path()."/potracking/vendor/KTPDireksi/$fileData->FileKtpDireksi";
       
        return response()->file($file);

    }

    public function fileBukuRekening($id)
    {     
        
        $fileData = UserVendor::where('VendorCode', $id)->first(); // Mengambil data file dari database berdasarkan vendorcode
        $file = public_path()."/potracking/vendor/Rekening/$fileData->FileRekening";
       
        return response()->file($file);

    }

    public function fileAktePendirian($id)
    {     
        
        $fileData = UserVendor::where('VendorCode', $id)->first(); // Mengambil data file dari database berdasarkan vendorcode
        $file = public_path()."/potracking/vendor/AktaPendirian/$fileData->FileAktaPendirian";
       
        return response()->file($file);

    }

    public function fileAnggaranDasar($id)
    {     
        
        $fileData = UserVendor::where('VendorCode', $id)->first(); // Mengambil data file dari database berdasarkan vendorcode
        $file = public_path()."/potracking/vendor/AnggaranDasar/$fileData->FileAnggaranDasar";
       
        return response()->file($file);

    }

    

    public function fileIzinUsaha($id)
    {     
        
        $fileData = UserVendor::where('VendorCode', $id)->first(); // Mengambil data file dari database berdasarkan vendorcode
        $file = public_path()."/potracking/vendor/IjinUsaha/$fileData->FileIjinUsaha";
       
        return response()->file($file);

    }

    public function fileSuratAgen($id)
    {     
        
        $fileData = UserVendor::where('VendorCode', $id)->first(); // Mengambil data file dari database berdasarkan vendorcode
        $file = public_path()."/potracking/vendor/Keagenan/$fileData->SuratAgen";
       
        return response()->file($file);

    }

    public function filePernyataanRekening($id)
    {     
        
        $fileData = UserVendor::where('VendorCode', $id)->first(); // Mengambil data file dari database berdasarkan vendorcode
        $file = public_path()."/potracking/vendor/PernyataanRekening/$fileData->FilePernyataanRekening";
       
        return response()->file($file);
    }

    
    public function filePernyataanPajak($id)
    {     
        
        $fileData = UserVendor::where('VendorCode', $id)->first(); // Mengambil data file dari database berdasarkan vendorcode
        $file = public_path()."/potracking/vendor/TaatPajak/$fileData->FilePernyataanPajak";
       
        return response()->file($file);

    }

    public function fileEtikaBertransaksi($id)
    {     
        
        $fileData = UserVendor::where('VendorCode', $id)->first(); // Mengambil data file dari database berdasarkan vendorcode
        $file = public_path()."/potracking/vendor/EtikaTransaksi/$fileData->FileEtikaBertransaksi";
       
        return response()->file($file);
    }
}