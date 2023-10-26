<?php

namespace App\Http\Controllers\Cms\Qrgad;

use App\Http\Controllers\Controller;
use App\Models\Table\Qrgad\MsLokasi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LokasiController extends Controller
{

    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            
        if ($this->PermissionMenu('lokasi') == 0){
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
            if($this->PermissionActionMenu('lokasi')->r==1){


            $breadcrumb = [
                [
                    'nama' => "Lokasi",
                    'url' => "/lokasi"
                ],
            ];
            
            return view('Qrgad/lokasi/index', [
                "breadcrumbs" => $breadcrumb,
                "actionmenu" => $this->permissionActionMenu('lokasi')
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function read()
    {
        try{
            if($this->PermissionActionMenu('lokasi')->r==1){

            return view('Qrgad/lokasi/read', [
                "lokasi" => MsLokasi::where('status', 1)->get()
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
            if($this->PermissionActionMenu('lokasi')->c==1){

            return view('Qrgad/lokasi/create',[
                "actionmenu" => $this->permissionActionMenu('lokasi')
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
            if($this->PermissionActionMenu('lokasi')->c==1){

            $kode = MsLokasi::idOtomatis();

            $create = MsLokasi::create([
                'id' => $kode,
                'nama' => $request->nama,
                'created_by' => Auth::user()->name,
                'status' => 1,
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
            if($this->PermissionActionMenu('lokasi')->u==1){

            return view('Qrgad/lokasi/edit', [
                "lokasi" =>  MsLokasi::findOrFail($id),
                "actionmenu" => $this->permissionActionMenu('lokasi')
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
            if($this->PermissionActionMenu('lokasi')->u==1){


            $lokasi = MsLokasi::findOrFail($id);
            $lokasi->nama = $request->nama;
            $lokasi->save();

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

    public function delete($id)
    {
        try{
            if($this->PermissionActionMenu('lokasi')->d==1){
            
            return view('Qrgad/lokasi/delete', [
                'id' => $id,
                "actionmenu" => $this->permissionActionMenu('lokasi')
            ]);

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }

    public function destroy($id)
    {
        try{
            if($this->PermissionActionMenu('lokasi')->d==1){

            $update = MsLokasi::find($id);
        
            $update->status= 0;
            $update->save();

            }else{
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('alert', 'danger-errorrequest-Error Request, Exception Error');
        } 
    }
}
