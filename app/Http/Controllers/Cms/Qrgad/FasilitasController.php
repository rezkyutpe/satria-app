<?php

namespace App\Http\Controllers\Cms\Qrgad;

use App\Http\Controllers\Controller;
use App\Models\Table\Qrgad\MsFasilitasRuangan;
use Exception;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Return_;

class FasilitasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
        if ($this->PermissionMenu('fasilitas') == 0){
                return redirect()->back()->with('alert', 'danger-notallowed-akses ditolak!');
            }
            return $next($request);
        });

    }

    public function index()
    {
        try{
            if($this->PermissionActionMenu('fasilitas')->r==1){

                $breadcrumb = [
                    [
                        'nama' => "Fasilitas",
                        'url' => "/fasilitas"
                    ],
                ];
                
                return view('Qrgad/fasilitas/index', [
                    "breadcrumbs" => $breadcrumb,
                    "actionmenu" => $this->permissionActionMenu('fasilitas')
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
            if($this->PermissionActionMenu('fasilitas')->r==1){

            return view('Qrgad/fasilitas/read', [
                "fasilitas" => MsFasilitasRuangan::where('status', 1)->get(),
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
            if($this->PermissionActionMenu('fasilitas')->c==1){
            
            return view('Qrgad/fasilitas/create',[
                "actionmenu" => $this->permissionActionMenu('fasilitas')
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
            if($this->PermissionActionMenu('fasilitas')->c==1){
            
            $kode = MsFasilitasRuangan::idOtomatis();
    
            $create = MsFasilitasRuangan::create([
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
            if($this->PermissionActionMenu('fasilitas')->u==1){
            
            return view('Qrgad/fasilitas/edit', [
                "fasilitas" =>  MsFasilitasRuangan::findOrFail($id),
                "actionmenu" => $this->permissionActionMenu('fasilitas')
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
            if($this->PermissionActionMenu('fasilitas')->u==1){
            
            $fasilitas = MsFasilitasRuangan::findOrFail($id);
            $fasilitas->nama = $request->nama;
            $fasilitas->save();

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
            if($this->PermissionActionMenu('fasilitas')->d==1){

            return view('Qrgad/fasilitas/delete', [
                'id' => $id,
                "actionmenu" => $this->permissionActionMenu('fasilitas')
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
            if($this->PermissionActionMenu('fasilitas')->d==1){

            $update = MsFasilitasRuangan::find($id);
        
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
