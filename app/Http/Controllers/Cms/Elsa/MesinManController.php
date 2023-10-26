<?php

namespace App\Http\Controllers\Cms\Elsa;

use Illuminate\Http\Request;
use App\Models\Table\Elsa\MstMesin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use App\Models\Table\Elsa\MstFileMesin;
use App\Models\Table\Elsa\MstSpekMesin;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\Table\Elsa\MstDetailMesin;
use App\Models\View\VwPermissionAppsMenu;

class MesinManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('mesin-management') == 0) {
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
            return $next($request);
        });
    }

    public function MesinIndex()
    {
        if ($this->PermissionActionMenu('mesin-management')->r == 1) {
            // $mesin = MstMesin::all();
            $mesin = MstMesin::all();
            $m = MstMesin::all();
            $actionmenu = $this->PermissionActionMenu('mesin-management');
            return view('elsa.mesin-management.index', compact('mesin', 'm', 'actionmenu'));
        } else {
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }

    public function MesinDetail($id)
    {
        if ($this->PermissionActionMenu('mesin-management')->v == 1) {
            $Mesin = MstMesin::where('id_mesin', $id)->first();
            $DetailMesin = MstDetailMesin::leftJoin('mst_mesin', 'mst_mesin.id_mesin', '=', 'mst_detail_mesin.id_mesin')
                ->where('mst_mesin.id_mesin', $id)
                ->first();
            // dd(count($DetailMesin));
            $SpekMesin = MstSpekMesin::select('mst_spek_mesin.*')
                ->leftJoin('mst_mesin as m', 'm.id_mesin', '=', 'mst_spek_mesin.id_mesin')
                ->where('m.id_mesin', $id)
                ->get();
            $FileMesin = MstFileMesin::select('mst_file_mesin.*')
                ->leftJoin('mst_mesin as m', 'm.id_mesin', '=', 'mst_file_mesin.id_mesin')
                ->where('m.id_mesin', $id)
                ->get();
            // dd($FileMesin);

            $actionmenu = $this->PermissionActionMenu('mesin-management');
            return view('elsa.mesin-management.detail', compact('Mesin', 'DetailMesin', 'SpekMesin', 'FileMesin', 'actionmenu'));
        } else {
            return redirect('/')->with('err_message', 'Akses Ditolak');
        }
    }

    public function MesinDetailViewFile($file_name)
    {
        if ($this->PermissionActionMenu('mesin-management')->v == 1) {
            return Response::make(file_get_contents('public/mesinfile/' . $file_name), 200, [
                'content-type' => 'application/pdf',
            ]);
        } else {
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }

    public function MesinInsert(Request $request)
    {
        if ($this->PermissionActionMenu('mesin-management')->c == 1) {
            $mesin = MstMesin::create([
                'nama_mesin' => $request->nama_mesin,
                'category' => $request->kategori,
                'function' => $request->function,
                'dimensi' => $request->dimensi,
                'tools' => $request->tools,
                'remarks' => $request->remarks
            ]);
            if ($mesin) {
                return redirect()->back()->with('suc_message', 'Data Mesin Berhasil Ditambahkan');
            } else {
                return redirect()->back()->with('err_message', 'Data Mesin Gagal Ditambahkan');
            }
        } else {
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }

    public function MesinUpdate(Request $request)
    {
        if ($this->PermissionActionMenu('mesin-management')->u == 1) {
            $FileMesinExist = MstFileMesin::where('id_file', $request->id_file_mesin_update)->first();

            if (empty($request->description[0])){
                if(empty($request->unit[0]) && empty($request->value[0])){
                    $DetailMesin = [];
                    if (!empty($request->id_detail_mesin_update)) {
                        if ($request->category != 'Choose') {
                            $DetailMesin = MstDetailMesin::where('id_detail_mesin', $request->id_detail_mesin_update)->update([
                                'id_mesin' => $request->id_mesin_update,
                                'category' => $request->category,
                                'function' => $request->function,
                                'material' => $request->material,
                                'length_mesin' => $request->length_mesin,
                                'width' => $request->width,
                                'thickness' => $request->thickness,
                                'tools' => $request->tools,
                                'remarks' => $request->remarks
                            ]);
                        }
                    } else {
                        if ($request->category != 'Choose') {
                            $DetailMesin = MstDetailMesin::create([
                                'id_mesin' => $request->id_mesin_update,
                                'category' => $request->category,
                                'function' => $request->function,
                                'material' => $request->material,
                                'length_mesin' => $request->length_mesin,
                                'width' => $request->width,
                                'thickness' => $request->thickness,
                                'tools' => $request->tools,
                                'remarks' => $request->remarks
                            ]);
                        }
                    }

                    $UMFile = [];
                    if (!empty($request->id_file_mesin_update)) {
                        if ($request->has('mesinFile')) {
                            $fileMesin = $request->file('mesinFile');
                            $fileName = uniqid() . '_File_' . $fileMesin->getClientOriginalName();
                            $fileMesin->move(public_path('mesinfile'), $fileName);

                            File::delete(public_path('mesinfile/' . $FileMesinExist->file_name));
                            $UMFile = MstFileMesin::where('id_file', $request->id_file_mesin_update)->update([
                                'id_mesin' => $request->id_mesin_update,
                                'file_name' => $fileName
                            ]);
                        }
                    } else {
                        if ($request->has('mesinFile')) {
                            $fileMesin = $request->file('mesinFile');
                            $fileName = uniqid() . '_File_' . $fileMesin->getClientOriginalName();

                            if (Storage::disk('public')->exists('mesinfile')) {
                                $fileMesin->move(public_path('mesinfile'), $fileName);
                            } else {
                                Storage::disk('public')->makeDirectory('mesinfile');
                                $fileMesin->move(public_path('mesinfile'), $fileName);
                            }

                            $UMFile = MstFileMesin::create([
                                'id_mesin' => $request->id_mesin_update,
                                'file_name' => $fileName
                            ]);
                        }
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data Mesin Gagal Diupdate (Description required)');
                }
            }else{
                if(!empty($request->unit[0]) && !empty($request->value[0])){
                    $DetailMesin = [];
                    if (!empty($request->id_detail_mesin_update)) {
                        if ($request->category != 'Choose') {
                            $DetailMesin = MstDetailMesin::where('id_detail_mesin', $request->id_detail_mesin_update)->update([
                                'id_mesin' => $request->id_mesin_update,
                                'category' => $request->category,
                                'function' => $request->function,
                                'material' => $request->material,
                                'length_mesin' => $request->length_mesin,
                                'width' => $request->width,
                                'thickness' => $request->thickness,
                                'tools' => $request->tools,
                                'remarks' => $request->remarks
                            ]);
                        }
                    } else {
                        if ($request->category != 'Choose') {
                            $DetailMesin = MstDetailMesin::create([
                                'id_mesin' => $request->id_mesin_update,
                                'category' => $request->category,
                                'function' => $request->function,
                                'material' => $request->material,
                                'length_mesin' => $request->length_mesin,
                                'width' => $request->width,
                                'thickness' => $request->thickness,
                                'tools' => $request->tools,
                                'remarks' => $request->remarks
                            ]);
                        }
                    }

                    $SpekMesin = [];
                    for ($i = 0; $i < count($request->description); $i++) {
                        $data[] = [
                            'id_mesin' => $request->id_mesin_update,
                            'description' => $request->description[$i],
                            'unit' => $request->unit[$i],
                            'value' => $request->value[$i],
                        ];
                    }
                    $SpekMesin = MstSpekMesin::insert($data);
                    // dd($data);

                    $UMFile = [];
                    if (!empty($request->id_file_mesin_update)) {
                        if ($request->has('mesinFile')) {
                            $fileMesin = $request->file('mesinFile');
                            $fileName = uniqid() . '_File_' . $fileMesin->getClientOriginalName();
                            $fileMesin->move(public_path('mesinFile'), $fileName);

                            File::delete(public_path('mesinFile/' . $FileMesinExist->file_name));
                            $UMFile = MstFileMesin::where('id_file', $request->id_file_mesin_update)->update([
                                'id_mesin' => $request->id_mesin_update,
                                'file_name' => $fileName
                            ]);
                        }
                    } else {
                        if ($request->has('mesinFile')) {
                            $fileMesin = $request->file('mesinFile');
                            $fileName = uniqid() . '_File_' . $fileMesin->getClientOriginalName();

                            if (Storage::disk('public')->exists('mesinfile')) {
                                $fileMesin->move(public_path('mesinfile'), $fileName);
                            } else {
                                Storage::disk('public')->makeDirectory('mesinfile');
                                $fileMesin->move(public_path('mesinfile'), $fileName);
                            }

                            $UMFile = MstFileMesin::create([
                                'id_mesin' => $request->id_mesin_update,
                                'file_name' => $fileName
                            ]);
                        }
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data Mesin Gagal Diupdate (Unit or Value required)');
                } 
            }

            if ($DetailMesin || $UMFile || $SpekMesin) {
                return redirect()->back()->with('suc_message', 'Data Mesin Berhasil Diupdate');
            } else {
                return redirect()->back()->with('err_message', 'Data Mesin Gagal Diupdate');
            }
        } else {
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }

    public function MesinDelete(Request $request)
    {
        if ($this->PermissionActionMenu('mesin-management')->d == 1) {
            $mesin = MstMesin::where('id_mesin', $request->id_mesin_delete)->delete();
            return redirect()->back()->with('suc_message', 'Data Mesin Berhasil Dihapus');
        } else {
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }

    public function GetDetailMesin(Request $request)
    {
        // dd($request->id);
        $datadetail = MstDetailMesin::leftJoin('mst_mesin', 'mst_mesin.id_mesin', '=', 'mst_detail_mesin.id_mesin')
            ->where('mst_mesin.id_mesin', $request->id)
            ->first();

        $datamesin = MstMesin::where('id_mesin', $request->id)->first();
        if (empty($datadetail)) {
            $datadetail = [];
        }

        $datafile = MstFileMesin::leftJoin('mst_mesin', 'mst_mesin.id_mesin', '=', 'mst_file_mesin.id_mesin')
            ->where('mst_mesin.id_mesin', $request->id)
            ->first();
        if (empty($datafile)) {
            $datafile = [];
        }

        $dataspek = MstSpekMesin::where('id_mesin', $request->id)->get();
        if (empty($dataspek)) {
            $dataspek = [];
        }

        $data = [
            'datamesin' => $datamesin,
            'datadetail' => $datadetail,
            'datafile' => $datafile,
            'dataspek' => $dataspek
        ];
        echo json_encode($data);
    }

    public function SpekMesinDelete($id){
        if ($this->PermissionActionMenu('mesin-management')->d == 1) {
            $mesinid = MstSpekMesin::where('id_spek_mesin', $id)->first();
            $mesin = MstSpekMesin::where('id_spek_mesin', $id)->delete();
            return redirect('/mesin-detail'.'/'.$mesinid->id_mesin)->with('suc_message', 'Data Spek Mesin Berhasil Dihapus');
        } else {
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
}
