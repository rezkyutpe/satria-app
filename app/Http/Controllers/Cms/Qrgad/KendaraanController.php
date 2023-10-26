<?php

namespace App\Http\Controllers\Cms\Qrgad;

use App\Http\Controllers\Controller;
use App\Models\Table\Qrgad\MsKendaraan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KendaraanController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
        if ($this->PermissionMenu('kendaraan') == 0){
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            if($this->PermissionActionMenu('kendaraan')->r==1){

            $kendaraans = MsKendaraan::all()->where('status', 1);
            $breadcrumb = [
                [
                    'nama' => "Kendaraan",
                    'url' => "/kendaraan"
                ],
            ];
            
            return view('Qrgad/kendaraan/index', [
                "kendaraans" => $kendaraans,
                "breadcrumbs" => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('kendaraan')
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            if($this->PermissionActionMenu('kendaraan')->c==1){

            $breadcrumb = [
                [
                    'nama' => "Kendaraan",
                    'url' => "/kendaraan"
                ],
                [
                    'nama' => "Tambah",
                    'url' => "/kendaraan/create"
                ],
            ];
            
            return view('Qrgad/kendaraan/create', [
                "breadcrumbs" => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('kendaraan')
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            if($this->PermissionActionMenu('kendaraan')->c==1){

            $kode = MsKendaraan::idOtomatis();
            $validated = $request->validate([
                "id" => "",
                "nama" =>"required",
                "nopol" => "required",
            ]);
            
            $create = MsKendaraan::create([
                "id" => $kode,
                "nama" => $validated['nama'],
                "nopol" =>  $validated['nopol'],
                "status" => 1,
                "created_by" => Auth::user()->name, 
            ]);
    
            $alert = '';

            if($create){
                $alert = 'success-add-kendaraan';
            } else {
                $alert = 'danger-add-kendaraan';
            }
    
            return redirect('/kendaraan')->with('alert', $alert);
            

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            if($this->PermissionActionMenu('kendaraan')->u==1){
            
            $breadcrumb = [
                [
                    'nama' => "Kendaraan",
                    'url' => "/kendaraan"
                ],
                [
                    'nama' => "Edit",
                    'url' => "/kendaraan/".$id."/edit"
                ],
            ];
    
            return view('Qrgad/kendaraan/edit', [
                "kendaraan" => MsKendaraan::findOrFail($id),
                "breadcrumbs" => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('kendaraan')
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            if($this->PermissionActionMenu('kendaraan')->u==1){

            $validated = $request->validate([
                "id" => "",
                "nama" =>"required",
                "nopol" => "required",
            ]);
    
            $update = MsKendaraan::where('id', $id)->update([
                "nama" => $validated['nama'],
                "nopol" =>  $validated['nopol'],
                "updated_by" => Auth::user()->name, 
            ]);
    
            $alert = '';

            if($update){
                $alert = 'success-edit-kendaraan';
            } else {
                $alert = 'danger-edit-kendaraan';
            }
    
            return redirect('/kendaraan')->with('alert', $alert);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            if($this->PermissionActionMenu('kendaraan')->d==1){
            
            $update = MsKendaraan::where('id', $id)->update([
                "status" => 0
            ]);

            $alert = '';

            if($update){
                $alert = 'success-delete-kendaraan';
            } else {
                $alert = 'danger-delete-kendaraan';
            }
    
            return redirect('/kendaraan')->with('alert', $alert);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        }

    }
}
