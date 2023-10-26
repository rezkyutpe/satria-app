<?php

namespace App\Http\Controllers\Cms\Qrgad;

use App\Http\Controllers\Controller;
use App\Models\Table\Qrgad\MsPerusahaan;
use Exception;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerusahaanController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
        if ($this->PermissionMenu('jadwal-ruangan') == 0){
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
        //
    }

    public function read(){

        try{
            if($this->PermissionActionMenu('jadwal-ruangan')->c==1){
        
            $perusahaans = MsPerusahaan::all();

            return view('Qrgad/perusahaan/read', [
                "perusahaans" => $perusahaans,
                "actionmenu" => $this->permissionActionMenu('jadwal-ruangan')
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
            if($this->PermissionActionMenu('jadwal-ruangan')->c==1){

            return view('Qrgad/perusahaan/create',[
                "actionmenu" => $this->permissionActionMenu('jadwal-ruangan')
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
            if($this->PermissionActionMenu('jadwal-ruangan')->c==1){

            $create = MsPerusahaan::create([
                'id' => MsPerusahaan::idOtomatis(),
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
