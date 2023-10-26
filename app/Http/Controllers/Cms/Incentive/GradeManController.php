<?php

namespace App\Http\Controllers\Cms\Incentive;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\Table\Incentive\Grade;
use App\Models\View\VwPermissionAppsMenu;
class GradeManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            if ($this->PermissionMenu('grade-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function GradeMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('grade-management')->r==1){
            $paginate = 15;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $grade = Grade::where('description', 'like', "%" . $search. "%")->orderBy('id', 'asc')->simplePaginate($paginate);
                $grade->appends(['search' => $search]);
            } else {
                $grade = Grade::orderBy('id', 'asc')->simplePaginate($paginate);
            }
            // $grade = Grade::with(['countrys'])->where('role_id', 1)->get();
            $no = 1;
            foreach($grade as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'grade' => $grade,
                'actionmenu' => $this->PermissionActionMenu('grade-management'),
            );
            return view('incentive/grade-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }            
    }
    public function GradeMgmtInsert(Request $request)
    {
        if($this->PermissionActionMenu('grade-management')->c==1){
            $grade = Grade::where('id', $request->id)->first();
            if(empty($grade)){
            $create = Grade::create([
                'description'=>$request->description,
                'percentage'=>$request->percentage,
                'month'=>$request->month,
                'year'=>$request->year,
                'created_by'=>Auth::user()->id,
            ]);
                if($create){
                    return redirect('grade-management')->with('suc_message', 'Data berhasil ditambahkan!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                }
            }else{
            return redirect()->back()->with('err_message', 'Grade Name Already Exist!');
            }
        }else{
            return redirect('grade-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function GradeMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('grade-management')->u==1){
            $grade = Grade::where('id', $request->id)->first();
            if(!empty($grade)){
                $update = Grade::where('id', $request->id)
                ->update([
                    'description'=>$request->description,
                    'percentage'=>$request->percentage,
                    'month'=>$request->month,
                    'year'=>$request->year,
                    'updated_by'=>Auth::user()->id,
                ]);
                if($update){
                    return redirect('grade-management')->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                }
            }else{
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('grade-management')->with('err_message', 'Akses Ditolak!');
        }
    }
   
    public function GradeMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('grade-management')->d==1){
            $grade = Grade::where('id', $request->id)->first();
            if(!empty($grade)){
                Grade::where('id', $request->id)->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('grade-management')->with('err_message', 'Akses Ditolak!');
        }
    }
}
