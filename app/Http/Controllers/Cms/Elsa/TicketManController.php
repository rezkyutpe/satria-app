<?php

namespace App\Http\Controllers\Cms\Elsa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;
use App\Models\UserRoleGroup;
use App\Models\Table\Elsa\History;
use App\Models\Table\Elsa\Ticket;
use App\Models\Table\Elsa\MstSla;
use App\Models\View\Elsa\VwTicket;
use App\Models\View\VwPermissionAppsMenu;
use App\Models\Table\Elsa\MstDept;
use App\Models\Table\Elsa\Comment;
use App\Models\View\Elsa\VwComment;
use App\Models\View\Elsa\VwInventory;
use App\Models\View\Elsa\VwNotifComment;
use App\Models\Table\Elsa\MstInventory;
use App\Exports\TicketExport;
use Carbon\Carbon;
use Excel;
use Exception;
class TicketManController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            
            if ($this->PermissionMenu('ticket-management') == 0){
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
            return $next($request);
            });
    }

    public function TicketMgmtInit(Request $request)
    {
        try{
            if($this->PermissionActionMenu('ticket-management')->r==1){
                $paginate = 250;
                if (isset($request->query()['search'],$request->query()['status'])){
                    if($request->query()['status']=='O'){
                        $status = [1];
                    }else if($request->query()['status']=='P'){
                        $status = [2,3,4,9];
                    }else{
                        $status = [5,6];
                    }
                    $search = $request->query()['search'];
                    
                    $ticket = VwTicket::where('dept',Auth::user()->dept)->where('status','2')->whereIn('flag',$status)->where('ticket_id', 'like', "%" . $search. "%")->orderBy('created_at', 'desc')->get();
                }else if(isset($request->query()['search'])){
                    $search = $request->query()['search'];
                    $ticket = VwTicket::where('dept',Auth::user()->dept)->where('status','2')->where('ticket_id', 'like', "%" . $search. "%")->orderBy('created_at', 'desc')->get();
                } else if(isset($request->query()['status'])){
                    if($request->query()['status']=='O'){
                        $status = [1];
                    }else if($request->query()['status']=='P'){
                        $status = [2,3,4,9];
                    }else{
                        $status = [5,6];
                    }
                    $ticket = VwTicket::where('dept',Auth::user()->dept)->where('status','2')->whereIn('flag',$status)->orderBy('created_at', 'desc')->get();
                } else {
                    $ticket = VwTicket::where('dept',Auth::user()->dept)->where('status','2')->orderBy('created_at', 'desc')->simplePaginate($paginate);
                }
                // $ticket = VwTicket::where('dept',Auth::user()->dept)->whereIn('flag',[1,2,3,4,9])->orderBy('created_at', 'desc')->get();
                $sla = MstSla::where('dept',Auth::user()->dept)->orderBy('name', 'asc')->get();
                $dept= MstDept::whereIn('id',['8927','8884','8915','8921','8933'])->get();
                $response = $this->getUserSF(999);
                $arr = $response;
                $assist = UserRoleGroup::select('user_role_group.*','users.name')->join('users', 'users.id', '=', 'user_role_group.user')->where('users.dept',Auth::user()->dept)->whereIn('group', [14,16])->get();
                $inventory = VwInventory::where('dept',Auth::user()->dept)->where('flag',1)->orderBy('inventory_nama', 'asc')->get();
                $no = 1;
                foreach($ticket as $data){
                    $data->no = $no;
                    $no++;
                }
                $data = array(
                    'inventory' => $inventory,
                    'dept' => $dept,
                    'sla' => $sla,
                    'assist' => $assist,
                    'ticket' => $ticket,
                    'emp' => $arr,
                    'actionmenu' => $this->PermissionActionMenu('ticket-management'),
                );
                return view('elsa/ticket-management/index')->with('data', $data);
               
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }      
    }
    public function TicketMgmtNew(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('ticket-management')->r == 1) {
                $paginate = 250;
                $ticket = VwTicket::where('dept', Auth::user()->dept)->where('flag', 1)->where('created_at', 'like', "%" . date('Y-m') . "%")->orderBy('created_at', 'desc')->simplePaginate($paginate);
                $sla = MstSla::where('dept', Auth::user()->dept)->orderBy('name', 'asc')->get();
                $assist = UserRoleGroup::select('user_role_group.*', 'users.name')->join('users', 'users.id', '=', 'user_role_group.user')->where('users.dept', Auth::user()->dept)->whereIn('group', [14, 16])->get();
                $inventory = VwInventory::where('dept', Auth::user()->dept)->where('flag', 1)->orderBy('inventory_nama', 'asc')->get();
                $no = 1;
                foreach ($ticket as $data) {
                    $data->no = $no;
                    $no++;
                }
                $data = array(
                    'startdatemonth' => Carbon::now()->startOfMonth()->toDateString(),
                    'enddatemonth' => Carbon::now()->endOfMonth()->toDateString(),
                    'inventory' => $inventory,
                    'sla' => $sla,
                    'assist' => $assist,
                    'ticket' => $ticket,
                    'actionmenu' => $this->PermissionActionMenu('ticket-management'),
                );
                return view('elsa-assist/open-ticket')->with('data', $data);
            } else {
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function FilterTicketMgmtNew(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('ticket-management')->r == 1) {
                $paginate = 250;
                if($request->startdate != null && $request->enddate != null){
                    $ticket = VwTicket::where('dept', Auth::user()->dept)->whereBetween('created_at', [$request->startdate . " 00:00:00", $request->enddate . " 23:59:59"])->where('flag', 1)->orderBy('created_at', 'desc')->simplePaginate($paginate);
                    $sla = MstSla::where('dept', Auth::user()->dept)->orderBy('name', 'asc')->get();
                    $assist = UserRoleGroup::select('user_role_group.*', 'users.name')->join('users', 'users.id', '=', 'user_role_group.user')->where('users.dept', Auth::user()->dept)->whereIn('group', [14, 16])->get();
                    $inventory = VwInventory::where('dept', Auth::user()->dept)->where('flag', 1)->orderBy('inventory_nama', 'asc')->get();
                    $no = 1;
                    foreach ($ticket as $data) {
                        $data->no = $no;
                        $no++;
                    }
                }else{
                    $ticket = VwTicket::where('dept', Auth::user()->dept)->where('flag', 1)->orderBy('created_at', 'desc')->simplePaginate($paginate);
                    $sla = MstSla::where('dept', Auth::user()->dept)->orderBy('name', 'asc')->get();
                    $assist = UserRoleGroup::select('user_role_group.*', 'users.name')->join('users', 'users.id', '=', 'user_role_group.user')->where('users.dept', Auth::user()->dept)->whereIn('group', [14, 16])->get();
                    $inventory = VwInventory::where('dept', Auth::user()->dept)->where('flag', 1)->orderBy('inventory_nama', 'asc')->get();
                    $no = 1;
                    foreach ($ticket as $data) {
                        $data->no = $no;
                        $no++;
                    }
                }
                
                $data = array(
                    'inventory' => $inventory,
                    'sla' => $sla,
                    'assist' => $assist,
                    'ticket' => $ticket,
                    'startdate_filter' => $request->startdate,
                    'enddate_filter' => $request->enddate,
                    'actionmenu' => $this->PermissionActionMenu('ticket-management'),
                );
                return view('elsa-assist/open-ticket')->with('data', $data);
            } else {
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function TicketMgmtAssist(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('ticket-management')->r == 1) {
                $paginate = 250;
                $ticket = VwTicket::where('dept', Auth::user()->dept)->where('assist_id', Auth::user()->id)->where('created_at', 'like', "%" . date('Y-m') . "%")->orderBy('created_at', 'desc')->get();
                $sla = MstSla::where('dept', Auth::user()->dept)->orderBy('name', 'asc')->get();
                $assist = UserRoleGroup::select('user_role_group.*', 'users.name')->join('users', 'users.id', '=', 'user_role_group.user')->where('users.dept', Auth::user()->dept)->whereIn('group', [14, 16])->get();
                $inventory = VwInventory::where('dept', Auth::user()->dept)->where('flag', 1)->orderBy('inventory_nama', 'asc')->get();
                $no = 1;
                foreach ($ticket as $data) {
                    $data->no = $no;
                    $no++;
                }
                $data = array(
                    'startdatemonth' => Carbon::now()->startOfMonth()->toDateString(),
                    'enddatemonth' => Carbon::now()->endOfMonth()->toDateString(),
                    'inventory' => $inventory,
                    'sla' => $sla,
                    'assist' => $assist,
                    'ticket' => $ticket,
                    'actionmenu' => $this->PermissionActionMenu('ticket-management'),
                );
                return view('elsa-assist/assist-ticket')->with('data', $data);
            } else {
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function FilterTicketMgmtAssist(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('ticket-management')->r == 1) {
                $paginate = 250;
                if($request->startdate != null && $request->enddate != null){
                    $ticket = VwTicket::where('dept', Auth::user()->dept)->where('assist_id', Auth::user()->id)->whereBetween('created_at', [$request->startdate . " 00:00:00", $request->enddate . " 23:59:59"])->orderBy('created_at', 'desc')->get();
                    $sla = MstSla::where('dept', Auth::user()->dept)->orderBy('name', 'asc')->get();
                    $assist = UserRoleGroup::select('user_role_group.*', 'users.name')->join('users', 'users.id', '=', 'user_role_group.user')->where('users.dept', Auth::user()->dept)->whereIn('group', [14, 16])->get();
                    $inventory = VwInventory::where('dept', Auth::user()->dept)->where('flag', 1)->orderBy('inventory_nama', 'asc')->get();
                    $no = 1;
                    foreach ($ticket as $data) {
                        $data->no = $no;
                        $no++;
                    }
                }else{
                    $ticket = VwTicket::where('dept', Auth::user()->dept)->where('assist_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
                    $sla = MstSla::where('dept', Auth::user()->dept)->orderBy('name', 'asc')->get();
                    $assist = UserRoleGroup::select('user_role_group.*', 'users.name')->join('users', 'users.id', '=', 'user_role_group.user')->where('users.dept', Auth::user()->dept)->whereIn('group', [14, 16])->get();
                    $inventory = VwInventory::where('dept', Auth::user()->dept)->where('flag', 1)->orderBy('inventory_nama', 'asc')->get();
                    $no = 1;
                    foreach ($ticket as $data) {
                        $data->no = $no;
                        $no++;
                    }
                }
                
                $data = array(
                    'inventory' => $inventory,
                    'sla' => $sla,
                    'assist' => $assist,
                    'ticket' => $ticket,
                    'startdate_filter' => $request->startdate,
                    'enddate_filter' => $request->enddate,
                    'actionmenu' => $this->PermissionActionMenu('ticket-management'),
                );
                return view('elsa-assist/assist-ticket')->with('data', $data);
            } else {
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function AllTicketMgmtAssist(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('ticket-management')->r == 1) {
                $paginate = 250;
                $ticket = VwTicket::where('dept', Auth::user()->dept)->where('assist_id','!=',NULL)->where('created_at', 'like', "%" . date('Y-m') . "%")->orderBy('created_at', 'desc')->get();
                $sla = MstSla::where('dept', Auth::user()->dept)->orderBy('name', 'asc')->get();
                $assist = UserRoleGroup::select('user_role_group.*', 'users.name')->join('users', 'users.id', '=', 'user_role_group.user')->where('users.dept', Auth::user()->dept)->whereIn('group', [14, 16])->get();
                $inventory = VwInventory::where('dept', Auth::user()->dept)->where('flag', 1)->orderBy('inventory_nama', 'asc')->get();
                $no = 1;
                foreach ($ticket as $data) {
                    $data->no = $no;
                    $no++;
                }
                $data = array(
                    'startdatemonth' => Carbon::now()->startOfMonth()->toDateString(),
                    'enddatemonth' => Carbon::now()->endOfMonth()->toDateString(),
                    'inventory' => $inventory,
                    'sla' => $sla,
                    'assist' => $assist,
                    'ticket' => $ticket,
                    'actionmenu' => $this->PermissionActionMenu('ticket-management'),
                );
                return view('elsa-assist/all-assist-ticket')->with('data', $data);
            } else {
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function FilterAllTicketMgmtAssist(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('ticket-management')->r == 1) {
                $paginate = 250;
                if($request->startdate != null && $request->enddate != null){
                    $ticket = VwTicket::where('dept', Auth::user()->dept)->where('assist_id','!=',NULL)->whereBetween('created_at', [$request->startdate . " 00:00:00", $request->enddate . " 23:59:59"])->orderBy('created_at', 'desc')->get();
                    $sla = MstSla::where('dept', Auth::user()->dept)->orderBy('name', 'asc')->get();
                    $assist = UserRoleGroup::select('user_role_group.*', 'users.name')->join('users', 'users.id', '=', 'user_role_group.user')->where('users.dept', Auth::user()->dept)->whereIn('group', [14, 16])->get();
                    $inventory = VwInventory::where('dept', Auth::user()->dept)->where('flag', 1)->orderBy('inventory_nama', 'asc')->get();
                    $no = 1;
                    foreach ($ticket as $data) {
                        $data->no = $no;
                        $no++;
                    }
                }else{
                    $ticket = VwTicket::where('dept', Auth::user()->dept)->where('assist_id','!=',NULL)->orderBy('created_at', 'desc')->get();
                    $sla = MstSla::where('dept', Auth::user()->dept)->orderBy('name', 'asc')->get();
                    $assist = UserRoleGroup::select('user_role_group.*', 'users.name')->join('users', 'users.id', '=', 'user_role_group.user')->where('users.dept', Auth::user()->dept)->whereIn('group', [14, 16])->get();
                    $inventory = VwInventory::where('dept', Auth::user()->dept)->where('flag', 1)->orderBy('inventory_nama', 'asc')->get();
                    $no = 1;
                    foreach ($ticket as $data) {
                        $data->no = $no;
                        $no++;
                    }
                }      
                $data = array(
                    'inventory' => $inventory,
                    'sla' => $sla,
                    'assist' => $assist,
                    'ticket' => $ticket,
                    'startdate_filter' => $request->startdate,
                    'enddate_filter' => $request->enddate,
                    'actionmenu' => $this->PermissionActionMenu('ticket-management'),
                );
                return view('elsa-assist/all-assist-ticket')->with('data', $data);
            } else {
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function TicketMgmtView($id)
    {
        try{
            if($this->PermissionActionMenu('ticket-management')->r==1){
                    
                $ticket = VwTicket::where('dept',Auth::user()->dept)->where('id',$id)->first();
               
                return view('elsa/ticket-management/view')->with('data', $ticket);
                   
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            } 
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }          
    }

    public function exportTicket(Request $request)
    {
        try{
            if($this->PermissionActionMenu('ticket-management')->v==1){
                $start=isset($request->start) ? $request->start : null;
                $end=isset($request->end) ? $request->end : null;
                $subject = isset($request->subject) ? $request->subject : null;
                $nama_file = 'export-ticket-'."-".date('dmYHis').'.xlsx';
                return Excel::download(new TicketExport($start,$end,$subject), $nama_file);
                // echo $start.$end.$subject;
            }else{
                return redirect()->back()->with('err_message', 'Akses Ditolak!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }
    }
    public function TicketMgmtInsert(Request $request)
    {
        try{
            if($this->PermissionActionMenu('ticket-management')->c==1){
                $no = Ticket::where('created_at', 'like', date('Y-m-d'). "%")->count();
                $tk_num = 'TK'.date('Ymd').str_pad($no, 4, "0", STR_PAD_LEFT); 
                 if($request->media){
                    $file_extention = $request->media->getClientOriginalExtension();
                    $file_name = $tk_num.date('YmdHis').'.'.$file_extention;
                    $file_path = $request->media->move($this->MapPublicPath().'ticket',$file_name);
                }else{
                  $file_name='blank.png';
                }//PR202//PR20210101000
                $prioriy = '2';
                $cekpriority = User::where('email',$request->nrp)->whereRaw("title REGEXP 'Department Head| Division Head| Director'")->first();
                if(!empty($cekpriority)){
                    $prioriy = '3';
                }
                    $create = Ticket::create([
                        'ticket_id'=>$tk_num,
                        'reporter_nrp'=>$request->nrp,
                        'subject'=>$request->subject,
                        'dept'=>$request->dept,
                        'message'=>$request->message,
                        'priority'=>$prioriy,
                        'status'=>2,
                        'media'=>$file_name,
                        'created_by' => Auth::user()->id,
                    ]);
                    if($create){
                        $ticket = VwTicket::where('ticket_id', $tk_num)->first();
                        if(!empty($ticket->email_sf)){
                            $this->send($ticket->reporter_name,'Ticketing -  Ticket Created '.$ticket->ticket_id,$ticket->email_sf,$this->emailElsa($ticket->ticket_id,"atas pembuatan tiket baru anda.",$ticket->asset_name,$ticket->message,$ticket->assist_name,"berhasil dibuat"));
                        }
                        return redirect('ticket-management')->with('suc_message', 'Data berhasil ditambahkan!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal ditambahkan!');
                    }
                
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }   
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }     
    }
    public function TicketMgmtUpdate(Request $request)
    {
        try{
            if($this->PermissionActionMenu('ticket-management')->u==1){
                $ticket = Ticket::where('id', $request->id)->first();
                if(!empty($ticket)){
                    $update = Ticket::where('id', $request->id)
                    ->update([
                        'name'=>$request->name,
                        // 'resolution_time'=>$request->resolution_time,
                        'updated_by' => Auth::user()->id,
                    ]);
                    if($update){
                        return redirect('ticket-management')->with('suc_message', 'Data berhasil diupdate!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }     
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }     
    }
    public function TicketMgmtMove(Request $request)
    {
        try{
            if($this->PermissionActionMenu('ticket-management')->u==1){
                $ticket = Ticket::where('id', $request->id)->first();
                if(!empty($ticket)){
                    $update = Ticket::where('id', $request->id)
                    ->update([
                        'dept'=>$request->dept,
                        'updated_by' => Auth::user()->id,
                    ]);
                    if($update){
                        return redirect()->back()->with('suc_message', 'Data berhasil diupdate!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }      
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }             
    }
    
    public function TicketMgmtRequestApproval(Request $request)
    {
        try{
            if($this->PermissionActionMenu('ticket-management')->u==1){
                $ticket = Ticket::where('id', $request->id)->first();
                if(!empty($ticket)){
                    $update = Ticket::where('id', $request->id)
                    ->update([
                        'status'=>0,
                        'updated_by' => Auth::user()->id,
                    ]);
                    if($update){
                        return redirect()->back()->with('suc_message', 'Data berhasil diupdate!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }      
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }             
    }
    public function TicketMgmtAsign(Request $request)
    {
        try{
            if($this->PermissionActionMenu('ticket-management')->u==1){
                $ticket = VwTicket::where('id', $request->id)->first();
                $user = User::where('id', $request->assist)->first();
                if(!empty($ticket)){
                    $update = Ticket::where('id', $request->id)
                    ->update([
                        // 'respond_time'=>date("Y-m-d H:i:s"),
                        'assist_id'=>$user->id,
                        'updated_by' => Auth::user()->id,
                    ]);
                    if($update){
                        $this->setHistory($request->id,'Assigned to '.$user->name,$request->note,0);
                        
                        if(!empty($ticket->email_sf)){
                            $this->send($user->name,'Ticketing - Your are assign to  '.$ticket->ticket_id,$ticket->email_sf,$this->emailElsa($ticket->ticket_id,"atas penugasan tiket anda.",$ticket->asset_name,$request->note,$user->name,"di tugaskan kepada anda ".$user->name));
                        }
                
                        return  redirect()->back()->with('suc_message', 'Ticket Succesfully Assign!');
                    }else{
                        return redirect()->back()->with('err_message', 'Ticket gagal Assign!');
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Ticket tidak ditemukan!');
                }
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        } 
    }
    public function TicketMgmtProccess(Request $request)
    {
        try{
            if($this->PermissionActionMenu('ticket-management')->u==1){
                $ticket = VwTicket::where('id', $request->id)->first();
                $user = User::where('id', Auth::user()->id)->first();
                if(!empty($ticket)){
                    $update = Ticket::where('id', $request->id)
                    ->update([
                        'flag'=>2,
                        'assist_id'=>$user->id,
                        'respond_time'=>date("Y-m-d H:i:s"),
                        'sla'=>$request->sla,
                        'updated_by' => Auth::user()->id,
                    ]);
                    if($update){
                        $this->setHistory($request->id,'Proccesed',$request->note,2);
                        $ticketok = VwTicket::where('id', $request->id)->first();
                        
                        if(!empty($ticket->email_sf)){
                            $this->send($ticket->reporter_name,'Ticketing - Your Ticket Proccesed '.$ticket->ticket_id,$ticket->email_sf,$this->emailElsa($ticket->ticket_id,"atas respon terhadap tiket anda.",$ticket->asset_name,$request->note,$ticketok->assist_name,"diproses oleh ".$ticketok->assist_name));
                        }
                    
                        return redirect()->back()->with('suc_message', 'Data berhasil diupdate!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }    
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }       
        
    }
    
    public function TicketMgmtEscalate(Request $request)
    {
        try{
            if($this->PermissionActionMenu('ticket-management')->u==1){
                $ticket = VwTicket::where('id', $request->id)->first();
                $user = User::where('id', $request->assist)->first();
                if(!empty($ticket)){
                    $update = Ticket::where('id', $request->id)
                    ->update([
                        'flag'=>9,
                        // 'respond_time'=>date("Y-m-d H:i:s"),
                        'assist_id'=>$user->id,
                        'updated_by' => Auth::user()->id,
                    ]);
                    if($update){
                        $this->setHistory($request->id,'Esaclated to '.$user->name,$request->note,9);
                        
                        if(!empty($ticket->email_sf)){
                            $this->send($user->name,'Ticketing - Your are Esaclate to  '.$ticket->ticket_id,$ticket->email_sf,$this->emailElsa($ticket->ticket_id,"atas pergantian penugasan tiket anda.",$ticket->asset_name,$request->note,$user->name,"diescalate kepada anda ".$user->name));
                        }
                        return redirect()->back()->with('suc_message', 'Data berhasil diupdate!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            } 
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }   
    }
    public function TicketMgmtResolve(Request $request)
    {
        try{
            if($this->PermissionActionMenu('ticket-management')->u==1){
                $ticket = VwTicket::where('id', $request->id)->first();
                $inventory = MstInventory::where('inventory_id', $request->inventory)->first();
                if(!empty($ticket)){
                    $update = Ticket::where('id', $request->id)
                    ->update([
                        'analisis'=>$request->analisis,
                        'corrective'=>$request->corrective,
                        'preventive'=>$request->preventive,
                        'consumable'=>$request->consumable,
                        'costs'=>$request->costs,
                        'flag'=>3,
                        'resolve_time'=>date("Y-m-d H:i:s"),
                        'updated_by' => Auth::user()->id,
                    ]);
                    if($update){
                        if($request->inventory!=""){
                            MstInventory::where('inventory_id', $request->inventory)->update([
                                'inventory_qty'=>$inventory->inventory_qty-$request->reduce,
                                'updated_by' => Auth::user()->id,
                            ]);
                            $this->setInventoryHistory($request->inventory,'Reduce Ticket Problem',$request->id,$request->reduce);
                        }
                        
                        $this->setHistory($request->id,'Resolved',$request->note,3);
                        
                        if(!empty($ticket->email_sf)){
                            $this->send($ticket->reporter_name,'Ticketing - Your Ticket Resolved '.$ticket->ticket_id,$ticket->email_sf,$this->emailElsa($ticket->ticket_id,"atas selesainya tiket anda.",$ticket->asset_name,$request->note,$ticket->assist_name,"selesai di proses oleh ".$ticket->assist_name));
                        }
                    
                        return redirect()->back()->with('suc_message', 'Data berhasil diupdate!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }      
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }     
    }
    
    public function TicketMgmtMultipleClose(Request $request)
    {
        try{
            if($this->PermissionActionMenu('ticket-management')->u==1){
                if(count($request->ticketselect) > 0)
                {
                    foreach($request->ticketselect as $item=>$v){
                            // echo $request->ticketselect[$item].' '.$request->noteclose."<br>";
                        $update = Ticket::where('id', $request->ticketselect[$item])
                        ->update([
                            'flag'=>5,
                            // 'resolve_time'=>date("Y-m-d H:i:s"),
                            'updated_by' => Auth::user()->id,
                        ]);
                        $ticketok = VwTicket::where('id', $request->ticketselect[$item])->first();

                        if($update){
                            $this->setSla($request->ticketselect[$item]);
                            $this->setHistory($request->ticketselect[$item],'Closed',$request->noteclose,5);
                            
                            if(!empty($ticket->email_sf)){
                                $this->send($ticket->reporter_name,'Ticketing - Your Ticket Closed '.$ticket->ticket_id,$ticket->email_sf,$this->emailElsa($ticket->ticket_id,$ticketok->resolve_result,$ticket->asset_name,$request->noteclose,$ticketok->assist_name,"Selesai ditutup dengan waktu "));
                            }
                        }
                    }
                    return redirect()->back()->with('suc_message', 'Data berhasil diupdate!');
                }else{
                    return redirect()->back()->with('err_message', 'Not Selected Data!');
                }
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }     
    }
    public function TicketMgmtClose(Request $request)
    {
        try{
            if($this->PermissionActionMenu('ticket-management')->u==1){
                $ticket = VwTicket::where('id', $request->id)->first();
                if(!empty($ticket)){
                    $update = Ticket::where('id', $request->id)
                    ->update([
                        'flag'=>5,
                        // 'resolve_time'=>date("Y-m-d H:i:s"),
                        'updated_by' => Auth::user()->id,
                    ]);
                    $ticketok = VwTicket::where('id', $request->id)->first();

                    if($update){
                        $this->setSla($request->id);
                        $this->setHistory($request->id,'Closed',$request->note,5);
                        
                        if(!empty($ticket->email_sf)){
                            $this->send($ticket->reporter_name,'Ticketing - Your Ticket Closed '.$ticket->ticket_id,$ticket->email_sf,$this->emailElsa($ticket->ticket_id,$ticketok->resolve_result,$ticket->asset_name,$request->note,$ticketok->assist_name,"Selesai ditutup dengan waktu "));
                        }
                        return redirect()->back()->with('suc_message', 'Data berhasil diupdate!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal diupdate!');
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }  
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }     
    }
    public function TicketMgmtCancel(Request $request)
    {
        try{
            if($this->PermissionActionMenu('ticket-management')->u==1){
                $ticket = Ticket::where('id', $request->id)->first();
                if(!empty($ticket)){
                    $update = Ticket::where('id', $request->id)
                    ->update([
                        'flag'=>6,
                        'updated_by' => Auth::user()->id,
                    ]);
                    if($update){
                        $this->setHistory($request->id,'Canceled',$request->note,6);
                        if(!empty($ticket->email_sf)){
                            $this->send($ticket->reporter_name,'Ticketing - Your Ticket Canceled '.$ticket->ticket_id,$ticket->email_sf,$this->emailElsa($ticket->ticket_id,$ticket->resolve_time,$ticket->asset_name,$request->note,$ticket->assist_name,"Dicancel"));
                        }
                        return redirect()->back()->with('suc_message', 'Data berhasil dicancel!');
                    }else{
                        return redirect()->back()->with('err_message', 'Data gagal dicancel!');
                    }
                }else{
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            } 
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }     
    }
    public function getHistory($ticket){
        try{
            $empData = History::where('id_ticket',$ticket)->get();
            $ticket = VwTicket::where('id', $ticket)->first();
      
            $data = array(
                'data' => $empData,
                'ticket' => $ticket,
            );
            return response()->json($data);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }     
    }
    public function setHistory($idticket,$title,$desc,$flow)
    {
        try{
            $create = History::create([
                'id_ticket'=>$idticket,
                'title'=>$title,
                'description'=>$desc,
                'order'=>$flow,
                'people' => Auth::user()->id,
            ]);
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }     
    }
    
    public function setSla($id)
    {
        $ticket =  Ticket::where('id', $id)->first();

        $sla = MstSla::where('id', $ticket->sla)->first();

        $data =  $this->get_sla_count($ticket->respond_time, $ticket->resolve_time, $sla->resolution_time);  

        if ($data['status'] == 'passed') 
        {
            $update = Ticket::where('id', $id)
            ->update([
                'resolve_status' => '1'
            ]);
        }
        $update = Ticket::where('id', $id)
        ->update([
            'resolve_result' => $data['hasil'],
            'resolve_percent' => $data['percent']
        ]);
    }
    public function get_sla_count($awal, $akhir, $sla)
    { 
        $intime = '07:30:00'; //get from DB or API
        $outtime = '16:30:00'; //get rom DB or API

        $dateToday = date("Y-m-d"); //Date today
        
        $dateTimeA  = $awal; //parameter
        $timestampA = strtotime($dateTimeA);
        $dateA      = date("Y-m-d", $timestampA);

        $dateTimeB  = $akhir; //get from date todays
        $timestampB = strtotime($dateTimeB);
        $dateB      = date("Y-m-d", $timestampB);

        $dateYesterday = date('Y-m-d',(strtotime ( '-'.$this->getDiffDay($dateTimeA, $dateTimeB).' day' , strtotime ( $dateToday) ) ));

        $intimeToday = date('Y-m-d H:i:s', strtotime("$dateToday $intime"));
        $outtimeYesterday = date('Y-m-d H:i:s', strtotime("$dateYesterday $outtime"));

        $between_check = $this->check_hour_between($dateTimeB, $outtime, $intime);

        if($dateA == $dateB  || $between_check  ) 
        {
            $hasil = abs($timestampB - $timestampA) / (60 * 60); 
        }
        else
        {
            $diff = abs($timestampB - $timestampA) / (60 * 60); // perbedaan jam asli
            $offTime = $this->perkurangan($intimeToday, $outtimeYesterday); // waktu offtime mentah

            $offDay = $this->checkDay($dateA, $dateB); // waktu pendukung offtime, cek libur
            $hasil = ($diff - $offTime) + $offDay ; 
        }
        $hasil = number_format($hasil, 2, '.', '');

        $left = $sla - $hasil;
        $left = number_format($left, 2, '.', ''); 
        $left = $left * 60;
        $left = $this->convertToHoursMins($left, '%02d hours %02d minutes');

        $percent = $hasil / $sla * 100;
        $percent = number_format($percent, 2, '.', '');
        if($percent > 100) {$percent = 100;}

        $status = $this->countSLA($hasil, $sla);

        $hasil = $hasil * 60;
        $hasil = $this->convertToHoursMins($hasil, '%02d hours %02d minutes');

        $data = array('status' => $status, 'hasil' => $hasil, 'left' => $left, 'percent' => $percent);
        return $data;
    } 

    function convertToHoursMins($time, $format = '%02d:%02d') {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }
    
    public function check_hour_between($currentTime, $startTime, $endTime)
    {    
        $currentTime = strtotime($currentTime);
        $currentTime      = date("H:i", $currentTime);  

        if (
                (
                $startTime < $endTime &&
                $currentTime >= $startTime &&
                $currentTime <= $endTime
                ) ||
                (
                $startTime > $endTime && (
                $currentTime >= $startTime ||
                $currentTime <= $endTime
                )
                )
        ) 
        {
            return true; 
        } 
        else 
        {
            return false;
        }
    }
    public function perkurangan($tgl1, $tgl2)
    {
        $timestampA = strtotime($tgl1);
        $timestampB = strtotime($tgl2);
        return abs($timestampA - $timestampB) / (60 * 60);;
    }

    public function checkDay($date = null, $end_date = null)
    {
        $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
        $end_date = date ("Y-m-d", strtotime("-1 day", strtotime($end_date)));

        $offDay = array('2020-03-26');
        $count = 0;
        $countActual = 0;

        while (strtotime($date) <= strtotime($end_date)) 
        {   
            if(!in_array($date, $offDay))
            {
                $count++;
            } 
            

            $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
        }  

        $hasil = ($count * 8); 
        return $hasil; 
    }

    public function getDiffDay($date, $end_date)
    { 
        $count = 0;
        while (strtotime($date) <= strtotime($end_date)) 
        {  
            $count++; 

            $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
        } 

        return ($count - 1);
    }

    public function countSLA($workHour, $sla)
    {
        if($workHour <= $sla)
        {
            return 'passed';
        }
        else
        {
            return 'not passed';
        }
    }

    public function TicketMgmtDelete(Request $request)
    {
        try{
            if($this->PermissionActionMenu('ticket-management')->d==1){
                $ticket = Ticket::where('id', $request->id)->first();
                if(!empty($ticket)){
                    Ticket::where('id', $request->id)->delete();
                    return redirect()->back()->with('suc_message', 'Data telah dihapus!');
                } else {
                    return redirect()->back()->with('err_message', 'Data tidak ditemukan!');
                }
            }else{
                return redirect('/')->with('err_message', 'Akses Ditolak!');
            }   
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error ');
        }        
    }
    
}
