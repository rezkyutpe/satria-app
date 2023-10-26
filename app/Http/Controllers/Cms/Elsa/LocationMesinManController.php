<?php

namespace App\Http\Controllers\Cms\Elsa;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Table\Elsa\MstMesin;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Table\Elsa\MstLocMesin;
use App\Models\Table\Elsa\MstFileMesin;
use App\Models\Table\Elsa\MstSpekMesin;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\Table\Elsa\MstDetailMesin;
use App\Models\View\VwPermissionAppsMenu;

class LocationMesinManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('loc-mesin-management') == 0) {
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
            return $next($request);
        });
    }

    public function LocMesinIndex()
    {
        if ($this->PermissionActionMenu('loc-mesin-management')->r == 1) {
            $LocMesin = MstLocMesin::all();
            $actionmenu = $this->PermissionActionMenu('loc-mesin-management');
            // dd($actionmenu);
            return view('elsa.loc-mesin-management.index', compact('LocMesin', 'actionmenu'));
        } else {
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }

    public function LocMesinInsert(Request $request)
    {
        if ($this->PermissionActionMenu('loc-mesin-management')->c == 1) {
            // dd($request);
            $cek = MstLocMesin::where('location_mesin', $request->location_mesin)->first();
            $locationmesin = [];
            if (empty($cek)) {
                $locationmesin = MstLocMesin::insert([
                    'location_mesin' => $request->location_mesin,
                    'kategori_loc' => $request->kategori_loc,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            if ($locationmesin) {
                return redirect()->back()->with('suc_message', 'Data Lokasi Mesin Berhasil Ditambahkan');
            } else {
                return redirect()->back()->with('err_message', 'Data Lokasi Mesin Gagal Ditambahkan (Data mesin sudah ada)');
            }
        } else {
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }

    public function GetLocation(Request $request)
    {
        // dd($request->id);
        $data = MstLocMesin::where('id_loc_mesin', $request->id)->first();
        echo json_encode($data);
    }

    public function LocMesinUpdate(Request $request)
    {
        if ($this->PermissionActionMenu('loc-mesin-management')->u == 1) {
            // dd($request);
            $cek = MstLocMesin::where('location_mesin', $request->location_mesin_update)->first();
            $locationmesin = [];
            if (empty($cek)) {
                $locationmesin = MstLocMesin::where('id_loc_mesin', $request->idloc_update)->update([
                    'location_mesin' => $request->location_mesin_update,
                    'kategori_loc' => $request->kategori_loc_update,
                    'updated_at' => Carbon::now()
                ]);
            }
            if ($locationmesin) {
                return redirect()->back()->with('suc_message', 'Data Lokasi Mesin Berhasil Diupdate');
            } else {
                return redirect()->back()->with('err_message', 'Data Lokasi Mesin Gagal Diupdate (Data mesin sudah ada)');
            }
        } else {
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }

    public function LocMesinDelete(Request $request)
    {
        if ($this->PermissionActionMenu('loc-mesin-management')->d == 1) {
            $deletelocation = MstLocMesin::where('id_loc_mesin', $request->idloc_delete)->delete();
            return redirect()->back()->with('suc_message', 'Data Lokasi Mesin Berhasil Dihapus');
        } else {
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
}
