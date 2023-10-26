<?php

namespace App\Http\Controllers\Cms\Qfd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

use App\Models\User;
use App\Models\MstApps;
use App\Models\Table\Qfd\MstProcess;
use App\Models\Table\Qfd\TrxMat;
use App\Models\Table\Qfd\TrxMatDetail;
use App\Models\Table\Qfd\TrxBomDetail;
use App\Models\Table\Qfd\TrxMatDraft;
use App\Models\Table\Qfd\TrxMatDetailDraft;
use App\Models\Table\Qfd\TrxBomDetailDraft;
use App\Models\Table\Qfd\TrxBomHistory;
use App\Models\Table\Qfd\MstSapMat;
use App\Models\Table\Qfd\MstSapComp;
use App\Models\Table\Qfd\MstBom;
use App\Models\View\VwPermissionAppsMenu;
class TrxMatManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('qfd-management') == 0){
                    return redirect('/')->with('err_message', 'Akses Ditolak!');
                }
                return $next($request);
            });
    }

    public function TrxMatMgmtInit(Request $request)
    {
        if($this->PermissionActionMenu('qfd-management')->r==1){
            $paginate = 150;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $trxmat = TrxMat::where('material_description', 'like', "%" . $search. "%")->orderBy('created_at', 'desc')->simplePaginate($paginate);
                $trxmat->appends(['search' => $search]);
            } else {
                $trxmat = TrxMat::orderBy('created_at', 'desc')->simplePaginate($paginate);
            }
            $mstsap = MstSapMat::where('smt_name', 'like', "%" . 'A1000000'. "%")->orderBy('smt_desc', 'asc')->get();
           
            $no = 1;
            foreach($trxmat as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'trxmat' => $trxmat,
              'mstsap' => $mstsap,
                'actionmenu' => $this->PermissionActionMenu('qfd-management'),
            );
            return view('qfd/qfd-management/index')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function TrxMatMgmtDraft(Request $request)
    {
        if($this->PermissionActionMenu('qfd-management')->r==1){
            $paginate = 150;
            if (isset($request->query()['search'])){
                $search = $request->query()['search'];
                $trxmat = TrxMatDraft::where('material_description', 'like', "%" . $search. "%")->orderBy('created_at', 'desc')->simplePaginate($paginate);
                $trxmat->appends(['search' => $search]);
            } else {
                $trxmat = TrxMatDraft::orderBy('created_at', 'desc')->simplePaginate($paginate);
            }
           
            $no = 1;
            foreach($trxmat as $data){
                $data->no = $no;
                $no++;
            }
            $data = array(
                'trxmat' => $trxmat,
                'actionmenu' => $this->PermissionActionMenu('qfd-management'),
            );
            return view('qfd/qfd-management/draft')->with('data', $data);
        }else{
            return redirect('/')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function TrxMatMgmtAdd(Request $request)
    {
      if($this->PermissionActionMenu('qfd-management')->c==1){
          
            $mstsap = MstSapMat::where('smt_name', 'like', "%" . 'A1000000'. "%")->orderBy('smt_desc', 'asc')->get();
            $bom = MstBom::where('material_desc', 'disableat07032023requestbymarketing')->get();
            // $bom = MstBom::where('material_desc', $request->matdesc)->get();
            $mstcomp = MstSapComp::orderBy('material_desc', 'asc')->get();
            $process = MstProcess::orderBy('nama', 'asc')->get();
            $response = $this->getUserSF(999);
            $arr = $response;
            $data = array(
              'process' => $process,
              'mstcomp' => $mstcomp,
              'bom' => $bom,
              'num' => $request->num,
              'matdesc' => $request->matdesc,
              'mstsap' => $mstsap,
              'emp' => $arr,
            );
          // echo $count;
            return view('qfd/qfd-management/add')->with('data', $data);
      }else{
        return redirect('qfd-management')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function TrxMatMgmtInsert(Request $request)
    {    
    // echo $request->material_qfd;  
        if($this->PermissionActionMenu('qfd-management')->c==1){  
            if($request->productspec){
                $file_extention = $request->productspec->getClientOriginalExtension();
                $file_name = $request->num.date('YmdHis').'.'.$file_extention;
                $file_path = $request->productspec->move($this->MapPublicPath().'qfd',$file_name);
            }else{
              $file_name=$request->productspec_old;
            }
            if($request->submit=='Submit'){
              // echo "submit";
                $create = TrxMat::create([
                  'material_number' => $request->num,
                  'material_description' => $request->matdesc,
                  'no_so' => $request->no_so,
                  'cust' => $request->cust,
                  'qty' => $request->qty_item,
                  'req_deliv_date' => $request->req_deliv_date,
                  'note' => $request->note,
                  'attendance' => $request->attendance,
                  'file' => $file_name,
                  'created_by' => Auth::user()->id,

                ]);
                if($request->material_qfd !='')
                {
                  foreach($request->material_qfd as $items=>$v){
                    
                      $data2=array(
                          'material_qfd'=>$request->num,
                          'material_desc'=>$request->matdesc,
                          'item'=>$request->item[$items],
                          'component'=>$request->component[$items],
                          'component_desc'=>$request->component_desc[$items],
                          'qty'=>$request->qty[$items],
                          'oum'=>$request->oum[$items],
                          'trx_material'=>$create->id,
                          'flag'=>$request->flag[$items],
                          'created_by' => Auth::user()->id,
                      );
                    TrxBomDetail::insert($data2);
                  }
                }
                if(count($request->process) > 0)
                {
                  foreach($request->process as $item=>$v){
                     $mstprocess = MstProcess::where('nama', $request->process[$item])->first();
                      if(empty($mstprocess)){
                      $created = MstProcess::create([
                          'nama'=>$request->process[$item],
                          'created_by' => Auth::user()->id,
                      ]);
                      }
                      $data2=array(
                          'id_mat'=>$request->num,
                          'trx_material'=>$create->id,
                          'id_proses'=>$request->process[$item],
                          'from'=>$request->from[$item],
                          'to'=>$request->to[$item],
                          'diff'=>$request->datediff[$item],
                          'pic'=>$request->pic[$item],
                          'pic_nrp'=>$request->pic_nrp[$item],
                          'pic_email'=>$request->pic_email[$item],
                          'created_by' => Auth::user()->id,
                          'remark'=>$request->remark[$item],
                      );
                    TrxMatDetail::insert($data2);
                  }
                }
              if($create){
                  return redirect('qfd-management')->with('suc_message', 'Data berhasil ditambahkan!');
              }else{
                  return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
              }
            }else{
              // echo "save";
              $create = TrxMatDraft::create([
                'material_number' => $request->num,
                'material_description' => $request->matdesc,
                'no_so' => $request->no_so,
                'cust' => $request->cust,
                'qty' => $request->qty_item,
                'req_deliv_date' => $request->req_deliv_date,
                'note' => $request->note,
                'attendance' => $request->attendance,
                'file' => $file_name,
                'created_by' => Auth::user()->id,

              ]);
              if($request->material_qfd !='')
              {
                foreach($request->material_qfd as $items=>$v){
                  
                    $data2=array(
                        'material_qfd'=>$request->num,
                        'material_desc'=>$request->matdesc,
                        'item'=>$request->item[$items],
                        'component'=>$request->component[$items],
                        'component_desc'=>$request->component_desc[$items],
                        'qty'=>$request->qty[$items],
                        'oum'=>$request->oum[$items],
                        'trx_material'=>$create->id,
                        'flag'=>$request->flag[$items],
                        'created_by' => Auth::user()->id,
                    );
                  TrxBomDetailDraft::insert($data2);
                }
              }
              if(count($request->process) > 0)
              {
                foreach($request->process as $item=>$v){
                   $mstprocess = MstProcess::where('nama', $request->process[$item])->first();
                    if(empty($mstprocess)){
                    $created = MstProcess::create([
                        'nama'=>$request->process[$item],
                        'created_by' => Auth::user()->id,
                    ]);
                    }
                    $data2=array(
                        'id_mat'=>$request->num,
                        'trx_material'=>$create->id,
                        'id_proses'=>$request->process[$item],
                        'from'=>$request->from[$item],
                        'to'=>$request->to[$item],
                        'diff'=>$request->datediff[$item],
                        'pic'=>$request->pic[$item],
                        'pic_nrp'=>$request->pic_nrp[$item],
                        'pic_email'=>$request->pic_email[$item],
                        'created_by' => Auth::user()->id,
                        'remark'=>$request->remark[$item],
                    );
                  TrxMatDetailDraft::insert($data2);
                }
              } 
              if($create){
                return redirect('qfd-draft')->with('suc_message', 'Data berhasil ditambahkan!');
              }else{
                  return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
              }
            }
        }else{
            return redirect('qfd-management')->with('err_message', 'Akses Ditolak!');
        }
    }

    public function TrxMatMgmtInsertDraft(Request $request)
    {    
    echo $request->material_qfd;  
        if($this->PermissionActionMenu('qfd-management')->c==1){  
            if($request->productspec){
                $file_extention = $request->productspec->getClientOriginalExtension();
                $file_name = $request->num.date('YmdHis').'.'.$file_extention;
                $file_path = $request->productspec->move($this->MapPublicPath().'qfd',$file_name);
            }else{
              $file_name='';
            }

            $create = TrxMatDraft::create([
              'material_number' => $request->num,
              'material_description' => $request->matdesc,
              'no_so' => $request->no_so,
              'cust' => $request->cust,
              'qty' => $request->qty_item,
              'req_deliv_date' => $request->req_deliv_date,
              'note' => $request->note,
              'attendance' => $request->attendance,
              'file' => $file_name,
              'created_by' => Auth::user()->id,

            ]);
            if($request->material_qfd !='')
            {
              foreach($request->material_qfd as $items=>$v){
                
                  $data2=array(
                      'material_qfd'=>$request->num,
                      'material_desc'=>$request->matdesc,
                      'item'=>$request->item[$items],
                      'component'=>$request->component[$items],
                      'component_desc'=>$request->component_desc[$items],
                      'qty'=>$request->qty[$items],
                      'oum'=>$request->oum[$items],
                      'trx_material'=>$create->id,
                      'flag'=>$request->flag[$items],
                      'created_by' => Auth::user()->id,
                  );
                TrxBomDetailDraft::insert($data2);
              }
            }
            if(count($request->process) > 0)
            {
              foreach($request->process as $item=>$v){
                 $mstprocess = MstProcess::where('nama', $request->process[$item])->first();
                  if(empty($mstprocess)){
                  $created = MstProcess::create([
                      'nama'=>$request->process[$item],
                      'created_by' => Auth::user()->id,
                  ]);
                  }
                  $data2=array(
                      'id_mat'=>$request->num,
                      'trx_material'=>$create->id,
                      'id_proses'=>$request->process[$item],
                      'from'=>$request->from[$item],
                      'to'=>$request->to[$item],
                      'diff'=>$request->datediff[$item],
                      'pic'=>$request->pic[$item],
                      'pic_nrp'=>$request->pic_nrp[$item],
                      'pic_email'=>$request->pic_email[$item],
                      'created_by' => Auth::user()->id,
                      'remark'=>$request->remark[$item],
                  );
                TrxMatDetailDraft::insert($data2);
              }
            }
          if($create){
              return redirect('qfd-draft')->with('suc_message', 'Data berhasil ditambahkan!');
          }else{
              return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
          }
        }else{
            return redirect('qfd-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function TrxMatMgmtCopy($id)
    {
      if($this->PermissionActionMenu('qfd-management')->c==1){
            $trxmat = TrxMat::where('id', $id)->first();
            $trxmatdetail = TrxMatDetail::where('trx_material', $id)->get();
            $trxbomdetail = TrxBomDetail::where('trx_material', $id)->get();
            $mstsap = MstSapMat::where('smt_name', 'like', "%" . 'A1000000'. "%")->orderBy('smt_desc', 'asc')->get();
            $mstcomp = MstSapComp::orderBy('material_desc', 'asc')->get();
            $process = MstProcess::orderBy('nama', 'asc')->get();
            $response = $this->getUserSF(999);
            $arr = $response;
            $data = array(
              'trxmat' => $trxmat,
              'trxmatdetail' => $trxmatdetail,
              'trxbomdetail' => $trxbomdetail,
              'mstcomp' => $mstcomp,
              'process' => $process,
              'mstsap' => $mstsap,
              'emp' => $arr,
            );
          // echo $count;
            return view('qfd/qfd-management/copy')->with('data', $data);
      }else{
        return redirect('qfd-management')->with('err_message', 'Akses Ditolak!');
      }
    }

    public function TrxMatMgmtCopyDraft($id)
    {
      if($this->PermissionActionMenu('qfd-management')->c==1){
            $trxmat = TrxMatDraft::where('id', $id)->first();
            $trxmatdetail = TrxMatDetailDraft::where('trx_material', $id)->get();
            $trxbomdetail = TrxBomDetailDraft::where('trx_material', $id)->get();
            $mstsap = MstSapMat::where('smt_name', 'like', "%" . 'A1000000'. "%")->orderBy('smt_desc', 'asc')->get();
            $mstcomp = MstSapComp::orderBy('material_desc', 'asc')->get();
            $process = MstProcess::orderBy('nama', 'asc')->get();
            $response = $this->getUserSF(999);
            $arr = $response;
            $data = array(
              'trxmat' => $trxmat,
              'trxmatdetail' => $trxmatdetail,
              'trxbomdetail' => $trxbomdetail,
              'mstcomp' => $mstcomp,
              'process' => $process,
              'mstsap' => $mstsap,
              'emp' => $arr,
            );
          // echo $count;
            return view('qfd/qfd-management/copy')->with('data', $data);
      }else{
        return redirect('qfd-management')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function TrxMatMgmtView($id)
    {
      if($this->PermissionActionMenu('qfd-management')->c==1){
            $trxmat = TrxMat::where('id', $id)->first();
            $trxmatdetail = TrxMatDetail::where('trx_material', $id)->get();
            $trxbomdetail = TrxBomDetail::where('trx_material', $id)->where('flag', 1)->get();
            
            $data = array(
              'trxmat' => $trxmat,
              'trxmatdetail' => $trxmatdetail,
              'trxbomdetail' => $trxbomdetail,
            );
            return view('qfd/qfd-management/view')->with('data', $data);
      }else{
        return redirect('qfd-management')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function TrxMatMgmtHistory($id)
    {
      if($this->PermissionActionMenu('qfd-management')->v==1){
            $trxmat = TrxMat::where('id', $id)->first();
            $trxmatdetail = TrxMatDetail::where('trx_material', $id)->get();
            $trxbomdetail = TrxBomDetail::where('trx_material', $id)->get();
            $trxbomhistory = TrxBomHistory::where('trx_material', $id)->get();
            
            $data = array(
              'trxmat' => $trxmat,
              'trxmatdetail' => $trxmatdetail,
              'trxbomdetail' => $trxbomdetail,
              'trxbomhistory' => $trxbomhistory,
            );
            return view('qfd/qfd-management/history')->with('data', $data);
      }else{
        return redirect('qfd-management')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function TrxMatMgmtEdit($id)
    {
      if($this->PermissionActionMenu('qfd-management')->u==1){
            $trxmat = TrxMat::where('id', $id)->first();
            $trxmatdetail = TrxMatDetail::where('trx_material', $id)->get();
            $trxbomdetail = TrxBomDetail::where('trx_material', $id)->get();
            $mstcomp = MstSapComp::orderBy('material_desc', 'asc')->get();
            $mstsap = MstSapMat::where('smt_name', 'like', "%" . 'A1000000'. "%")->orderBy('smt_desc', 'asc')->get();
            $process = MstProcess::orderBy('nama', 'asc')->get();
            $response = $this->getUserSF(999);
            $arr = $response;
            $data = array(
              'trxmat' => $trxmat,
              'trxmatdetail' => $trxmatdetail,
              'trxbomdetail' => $trxbomdetail,
              'mstcomp' => $mstcomp,
              'process' => $process,
              'mstsap' => $mstsap,
              'emp' => $arr,
            );
          // echo $count;
            return view('qfd/qfd-management/edit')->with('data', $data);
      }else{
        return redirect('qfd-management')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function TrxMatMgmtEditBom($id)
    {
      if($this->PermissionActionMenu('qfd-management')->u==1){
            $bom = MstBom::where('id', $id)->first();
            $data = array(
              'bom' => $bom,
            );
          // echo $count;
            return view('qfd/qfd-management/edit-bom')->with('data', $data);
      }else{
        return redirect('qfd-management')->with('err_message', 'Akses Ditolak!');
      }
    }
    public function TrxMatMgmtUpdate(Request $request)
    {
        if($this->PermissionActionMenu('qfd-management')->u==1){
              $trxmat = TrxMat::where('id', $request->id)->first();
              if(!empty($trxmat)){
                  if($request->productspec){
                      $file_extention = $request->productspec->getClientOriginalExtension();
                      $file_name = $request->num.date('YmdHis').'.'.$file_extention;
                      $file_path = $request->productspec->move($this->MapPublicPath().'qfd',$file_name);
                  }else{
                    $file_name=$trxmat->file;
                  }
                  $update = TrxMat::where('id', $request->id)
                  ->update([
                      'material_number' => $request->num,
                      'material_description' => $request->matdesc,
                      'no_so' => $request->no_so,
                      'cust' => $request->cust,
                      'qty' => $request->qty_item,
                      'req_deliv_date' => $request->req_deliv_date,
                      'note' => $request->note,
                      'status' => 31,
                      'file' => $file_name,
                      'updated_by' => Auth::user()->id,

                  ]);
                  if($update){
                    if($request->material_qfd !='')
                    {
                      foreach($request->material_qfd as $items=>$v){
                        
                          $data2=array(
                              'material_qfd'=>$request->num,
                              'material_desc'=>$request->matdesc,
                              'item'=>$request->item[$items],
                              'component'=>$request->component[$items],
                              'component_desc'=>$request->component_desc[$items],
                              'qty'=>$request->qty[$items],
                              'oum'=>$request->oum[$items],
                              'trx_material'=>$request->id,
                              'flag'=>$request->flag[$items],
                              'created_by' => Auth::user()->id,
                          );
                        TrxBomDetail::where('id', $request->id_bom[$items])->update($data2);
                      }
                    }
                      if(count($request->id_detail) > 0)
                      {
                        foreach($request->id_detail as $item=>$v){
                            $data2=array(
                                'id_mat'=>$request->num,
                                'trx_material'=>$request->id,
                                'id_proses'=>$request->process[$item],
                                'from'=>$request->from[$item],
                                'to'=>$request->to[$item],
                                'diff'=>$request->datediff[$item],
                                'pic'=>$request->pic[$item],
                                'pic_nrp'=>$request->pic_nrp[$item],
                                'pic_email'=>$request->pic_email[$item],
                                'updated_by' => Auth::user()->id,
                                'remark'=>$request->remark[$item],
                            );
                          TrxMatDetail::where('id', $request->id_detail[$item])->update($data2);
                        }
                      return redirect('qfd-management')->with('suc_message', 'Data berhasil diupdate!');
                  }else{
                      return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                  }
              }else{
                  return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
              }
          }else{
              return redirect('qfd-management')->with('err_message', 'Akses Ditolak!');
          }
        }
    }
     public function TrxMatMgmtEscalate(Request $request)
    {
        
        if($this->PermissionActionMenu('qfd-management')->u==1){
            $user =  User::where('id',Auth::user()->id)->first();
            if (empty($user)) {
                return redirect('qfd-management')->with('err_message', 'User Unauthorised');
            }else{
                TrxMat::where('id', $request->id)
                    ->update([
                    'status' => 5,
                    'updated_by' =>Auth::user()->id,
                ]);
                return redirect()->back()->with('suc_message', 'Data telah diescalate!');
            }
        }else{
            return redirect('qfd-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function TrxMatMgmtAccept(Request $request)
    {
        
        if($this->PermissionActionMenu('qfd-management')->u==1){
            $user =  User::where('id',Auth::user()->id)->first();
            if (empty($user)) {
                return redirect('qfd-management')->with('err_message', 'User Unauthorised');
            }else{
                TrxMat::where('id', $request->id)
                    ->update([
                    'accepted'=>Auth::user()->id,
                    'accepted_date'=>date('Y-m-d H:i:s'),
                    'status' => 1,
                    'updated_by' =>Auth::user()->id,
                ]);
                return redirect()->back()->with('suc_message', 'Data telah diescalate!');
            }
        }else{
            return redirect('qfd-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function TrxMatMgmtApprove(Request $request)
    {
        
        if($this->PermissionActionMenu('qfd-management')->c==1){
            $user =  User::where('id',Auth::user()->id)->first();
            if (empty($user)) {
                return redirect('qfd-management')->with('err_message', 'User Unauthorised');
            }else{
                TrxMat::where('id', $request->id)
                    ->update([
                    'approved'=>Auth::user()->id,
                    'approved_date'=>date('Y-m-d H:i:s'),
                    'status' => 2,
                    'updated_by' =>Auth::user()->id,
                ]);
                return redirect()->back()->with('suc_message', 'Data telah diapprove!');
            }
        }else{
            return redirect('qfd-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    
    public function TrxMatMgmtDelete(Request $request)
    {
        if($this->PermissionActionMenu('qfd-management')->d==1){
            $trxmat = TrxMat::where('id', $request->id)->first();
            if(!empty($trxmat)){
                  TrxMat::where('id', $request->id)
                    ->update([
                    'status' => 99,
                ]);
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('qfd-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function TrxMatMgmtDeleteDraft(Request $request)
    {
        if($this->PermissionActionMenu('qfd-management')->d==1){
            $trxmat = TrxMatDraft::where('id', $request->id)->first();
            if(!empty($trxmat)){
              TrxMatDraft::where('id', $request->id)
                    ->delete();
                    TrxMatDetailDraft::where('trx_material', $request->id)
                    ->delete();
                    TrxBomDetailDraft::where('trx_material', $request->id)
                    ->delete();
                return redirect()->back()->with('suc_message', 'Data telah dihapus!');
            } else {
                return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
            }
        }else{
            return redirect('qfd-management')->with('err_message', 'Akses Ditolak!');
        }
    }
    public function getProcess($app){

        $empData['data'] = TrxMat::orderBy('nama', 'asc')->get();
  
        return response()->json($empData);
     
    }
    public function getSapMat($smt_desc){

        $empData['data'] = MstSapMat::where('smt_desc', $smt_desc)->first();
  
        return response()->json($empData);
     
    }
     public function getSapComp($smt_desc){

        $empData['data'] = MstSapComp::where('material', $smt_desc)->first();
  
        return response()->json($empData);
     
    }
}
