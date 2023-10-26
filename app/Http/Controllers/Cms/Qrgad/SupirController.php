<?php

namespace App\Http\Controllers\Cms\Qrgad;

use App\Http\Controllers\Controller;
use App\Models\Table\Qrgad\MsSupir;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupirController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
        if ($this->PermissionMenu('supir') == 0){
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
            if($this->PermissionActionMenu('supir')->r==1){

            $supirs = MsSupir::all()->where('status', 1)->whereNotNull('kontak');
            $breadcrumb = [
                [
                    'nama' => "Driver",
                    'url' => "/supir"
                ],
            ];
            
            return view('Qrgad/supir/index', [
                "supirs" => $supirs,
                "breadcrumbs" => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('supir')
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
            if($this->PermissionActionMenu('supir')->c==1){

            $breadcrumb = [
                [
                    'nama' => "Driver",
                    'url' => "/supir"
                ],
                [
                    'nama' => "Tambah",
                    'url' => "/supir/create"
                ],
            ];
            
            return view('Qrgad/supir/create', [
                "breadcrumbs" => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('supir')
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
            if($this->PermissionActionMenu('supir')->c==1){

            $kode = MsSupir::idOtomatis();
            $validated = $request->validate([
                "id" => "",
                "nama" =>"required",
                "kontak" =>"required",
            ]);
            
            $create = MsSupir::create([
                "id" => $kode,
                "nama" => $validated['nama'],
                "kontak" => $validated['kontak'],
                "status" => 1,
                "created_by" => Auth::user()->name, 
            ]);
    
            $alert = '';

            if($create){
                $alert = 'success-add-driver';
            } else {
                $alert = 'danger-add-driver';
            }

            return redirect('/supir')->with('alert', $alert);
            

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
            if($this->PermissionActionMenu('supir')->u==1){
            
            $breadcrumb = [
                [
                    'nama' => "Driver",
                    'url' => "/supir"
                ],
                [
                    'nama' => "Edit",
                    'url' => "/supir/".$id."/edit"
                ],
            ];
    
            return view('Qrgad/supir/edit', [
                "supir" => MsSupir::findOrFail($id),
                "breadcrumbs" => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('supir')
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
            if($this->PermissionActionMenu('supir')->u==1){

            $validated = $request->validate([
                "id" => "",
                "nama" =>"required",
                "kontak" =>"required",
            ]);
            
            $update = MsSupir::where('id', $id)->update([
                "nama" => $validated['nama'],
                "kontak" => $validated['kontak'],
                "updated_by" => Auth::user()->name, 
            ]);
    
            $alert = '';

            if($update){
                $alert = 'success-edit-driver';
            } else {
                $alert = 'danger-edit-driver';
            }

            return redirect('/supir')->with('alert', $alert);
            

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
            if($this->PermissionActionMenu('supir')->d==1){
            
            $update = MsSupir::where('id', $id)->update([
                "status" => 0
            ]);

            $alert = '';

            if($update){
                $alert = 'success-delete-driver';
            } else {
                $alert = 'danger-delete-driver';
            }

            return redirect('/supir')->with('alert', $alert);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }
}
