<?php

namespace App\Http\Controllers\Cms\CompletenessComponent;

use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\Comments;
use App\Models\Table\CompletenessComponent\CommentsMaterial;
use App\Models\Table\CompletenessComponent\LogHistory;
use App\Models\Table\CompletenessComponent\MaterialTemporary;
use App\Models\Table\CompletenessComponent\Md_komentar;
use App\Models\Table\PoTracking\Comments as PoTrackingComments;
use App\Models\View\CompletenessComponent\VwComments;
use App\Models\View\CompletenessComponent\VwMaterialListHistory;
use App\Models\View\PoTracking\VwPo;
use App\Models\View\VwUserRoleGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('ccr-komentar') == 0) {
                return redirect()->back()->with('error', 'Access denied!');
            }
            return $next($request);
        });
    }

    // Master Data Komentar
    public function CommentInit()
    {
        try{
            if ($this->PermissionActionMenu('ccr-komentar')->r == 1) {
                $date   = Carbon::now();
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->id,
                    'menu'  => 'Master Data Komentar',
                    'date'  => $date->toDateString()
                ],[
                    'time'  => $date->toTimeString()
                ]);
                $komentar = Md_komentar::where('status', '!=', 'X')->get();
                $data = array(
                    'title'         => 'Komentar',
                    'komentar'      => $komentar,
                    'actionmenu'    => $this->PermissionActionMenu('ccr-komentar')
                );
                return view('completeness-component/master-data/komentar')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function CreateComment(Request $request)
    {
        try{
            if ($this->PermissionActionMenu('ccr-komentar')->c == 1) {
                $create = Md_komentar::create([
                    'komentar'  => htmlspecialchars($request->komentar),
                    'status'    => htmlspecialchars($request->status),
                    'created_by'=> Auth::user()->id
                ]);
                if ($create) {
                    return redirect()->back()->with('success', 'Data saved successfully!')->with('title', 'Success!');
                } else {
                    return redirect()->back()->with('error', 'Data failed to save!')->with('title', 'Failed!');
                }
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function EditComment(Request $request)
    {
        try{
            if ($this->PermissionActionMenu('ccr-komentar')->d == 1) {
                $edit = Md_komentar::where('id', htmlspecialchars($request->id))->update([
                    'komentar'  => htmlspecialchars($request->komentar),
                    'status'    => htmlspecialchars($request->status),
                    'updated_by'=> Auth::user()->id
                ]);
                if ($edit) {
                    return redirect()->back()->with('success', 'Data saved successfully!')->with('title', 'Success!');
                } else {
                    return redirect()->back()->with('error', 'Data failed to save!')->with('title', 'Failed!');
                }
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function DeleteComment(Request $request)
    {
        try{
            if ($this->PermissionActionMenu('ccr-komentar')->d == 1) {
                $edit = Md_komentar::where('id', htmlspecialchars($request->id))->update([
                    'status'    => 'X',
                    'updated_by'=> Auth::user()->id
                ]);
                if ($edit) {
                    return redirect()->back()->with('success', 'Data deleted successfully!')->with('title', 'Success!');
                } else {
                    return redirect()->back()->with('error', 'Data failed to deleted!')->with('title', 'Failed!');
                }
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    // END Master data Komentar


    // Header Show All Coment Notifikasi
    public function showAllComments()
    {
        try{
            if ($this->PermissionActionMenu('ccr-komentar')->c == 1) {
                
                Comments::where('user_to', Auth::user()->id)->where('user_by', Auth::user()->id)->where('is_read', NULL)->update(['is_read' => 1]);
 
                $ccr_last_chat    = VwComments::where('user_to', Auth::user()->id)->groupby('MATNR')->selectRaw('MAX(id) as id')->get();
                $comment_ccr      = VwComments::whereIn('id', $ccr_last_chat)->latest()->get();
                                
                $po_last_chat   = PoTrackingComments::where('user_to', Auth::user()->name)->groupby('Number','ItemNumber')->selectRaw('MAX(id) as id')->get();
                $comment_po     = PoTrackingComments::whereIn('id', $po_last_chat)->latest()->get();
                
                $data_ccr       = [];
                $data_po        = [];
                
                foreach ($comment_ccr as $ccr) {
                    $data_ccr[] = [
                        'apps'      => 'CCR',
                        'sender'    => $ccr->nama_pengirim,
                        'po_no'     => '-',
                        'itemNumber'=> '-',
                        'material'  => $ccr->MATNR,
                        'chat'      => $ccr->comment,
                        'is_read'   => $ccr->is_read,
                        'created_at'=> $ccr->created_at,
                    ];
                }
                
                foreach ($comment_po as $po) {
                    $data_po[] = [
                        'apps'      => 'PO Tracking',
                        'sender'    => $po->user_by,
                        'po_no'     => $po->Number,
                        'itemNumber'=> $po->ItemNumber,
                        'material'  => '-',
                        'chat'      => $po->comment,
                        'is_read'   => $po->is_read,
                        'created_at'=> $po->created_at,
                    ];
                }

                $comment[]    = array_merge($data_ccr, $data_po);
                $comment      = $comment[0];
                array_multisort( array_column($comment, "is_read"), SORT_ASC, array_column($comment, "created_at"), SORT_DESC, $comment );

                $data           = array(
                    'title'     => 'Notifications',
                    'comment'   => (object)$comment
                );
                
                return view('completeness-component.comments')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    public function MarkAllAsRead()
    {
        try{
            Comments::where('user_to', Auth::user()->id)->where('is_read', NULL)->update(['is_read' => 1]);
            PoTrackingComments::where('user_to', Auth::user()->name)->where('is_read', NULL)
               ->update([
                   'is_read'     => 1,
                   'updated_at'  => Carbon::now()
               ]);
            return redirect()->back();
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    // Create Comment pada halaman PRO
    public function CreateCommentsPRO(Request $request)
    {
        try{
            if ($this->PermissionActionMenu('ccr-komentar')->c == 1) {
                // komentar halaman PRO On Going
                if ($request->id_material != null) {
                    $data_material = MaterialTemporary::where('id', htmlspecialchars($request->id_material))->first();
                    $result = CommentsMaterial::updateOrCreate([
                        'production_order'  => $data_material->AUFNR,
                        'product_number'    => $data_material->PLNBEZ,
                        'material_number'   => $data_material->MATNR
                    ],[
                        'komentar'      => htmlspecialchars($request->value),
                        'updated_by'    => Auth::user()->name
                    ]);
                }

                // Komentar halaman PRO History
                if ($request->id_material_history != null) {
                    $data_material = VwMaterialListHistory::where('id', htmlspecialchars($request->id_material_history))->first();
                    $result = CommentsMaterial::updateOrCreate([
                        'production_order'  => $data_material->production_order,
                        'product_number'    => $data_material->product_number,
                        'material_number'   => $data_material->material_number
                    ],[
                        'komentar'      => htmlspecialchars($request->value),
                        'updated_by'    => Auth::user()->name
                    ]);
                }
                
                if ($result) {
                    return response()::json(['kode' => 1], 200);
                }
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    
    // Create comment pada halaman detail material
    public function CreateCommentsbyMaterial(Request $request)
    {
        try
        {
            if ($this->PermissionActionMenu('ccr-komentar')->c == 1) {
                $user_by    = Auth::user()->id;
                $user_to    = VwUserRoleGroup::select('user')->distinct()->where('app_name', 'CCR')->get();
                
                if ($user_to) {
                    foreach ($user_to as $user) {
                        $insert = Comments::insert([
                            'MATNR'     => htmlspecialchars($request->material),
                            'comment'   => htmlspecialchars($request->comment),
                            'user_by'   => $user_by,
                            'user_to'   => $user['user']
                        ]);
                    }
                    if ($insert) {
                        return response()::json(['kode' => 1], 200);
                    }
                } else {
                    return redirect()->back()->with('error', 'Data user not found!');
                }
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    // get master data komentar
    public function getDataKomentar(){
        try{
            $komentar      = Md_komentar::select('id', 'komentar')->where('status',1)->get();
            $data   = array(
                'komentar' => $komentar
            );
            echo json_encode($data);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    // get data komentar sesuai material pada PRO
    public function getKomentarMaterial(Request $request){
        try{
            if ($request->production_order != null && $request->material_number != null) {
                $id = CommentsMaterial::select('id', 'komentar')->where('production_order', $request->production_order)->where('material_number', $request->material_number)->first();
                if ($id == null) {
                    $id = 0;
                }
            }else{
                $id = 0;
            }
            $data   = array(
                'id' => $id
            );
            echo json_encode($data);
        }catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    // get data komentar pada halaman detail material
    public function getKomentarDetailMaterial(Request $request)
    {
        $komentar = VwComments::select('comment', 'user_by', 'created_at', 'nama_pengirim')->distinct()->where('MATNR', $request->material)->where('user_to', Auth::user()->id)->get();
        // Ubah Kondisi is_read jadi 1
        Comments::where('MATNR', $request->material)->where('user_to', Auth::user()->id)->where('is_read', NULL)->update(['is_read' => 1]);

        $data   = array(
            'komentar' => $komentar
        );
        echo json_encode($data);
    }

    // PO TRACKING

    // get data komentar po tracking di untuk modal
    public function CekCommentPoTracking(Request $request)
    {
         $Name    = Auth::user()->name ;
         $po      = VwPo::where('Number', $request->number)->where('ItemNumber', $request->item)->first();
         $datar   = PoTrackingComments::where('Number', $request->number)->where('ItemNumber', $request->item)->groupBy('comment', 'user_by', 'created_at')->orderBy('id','ASC')->get();
        
         PoTrackingComments::where('Number', $request->number)->where('ItemNumber', $request->item)->where('user_to', $Name)
            ->update([
                'is_read'     => 1,
                'updated_at'  => Carbon::now()
            ]);

         $data    = array(
             'datar'    => $datar,
             'Name'     => $Name,
             'Po'       => $po,
         );
         echo json_encode($data);
    }

     // insert komentar po tracking ke table comment potracking
    public function InsertCommentPoTracking(Request $request)
    {
         try{
            if($request->Name == $request->Vendor){
                $user_to = $request->Proc ;
            }else{
                $user_to = $request->Vendor ;
            }
            //  17 admin CCR, 18 PPC CCR, , 20 WHS CCR, 45 PROC CCR
            $data    = VwUserRoleGroup::select('username')->distinct()->where('username','!=',$request->Name)->whereIn('group', [30, 31, 17, 18, 20, 45])->get();

            if($request->Name == $request->Vendor || $request->Name == $request->Proc){
                foreach ($data as $q) {
                    $datarole[] = $q->username;
                }
                array_push($datarole,$user_to);
            }else{
                foreach ($data as $q) {
                    $datarole[] = $q->username;
                }
                array_push($datarole,$request->Vendor,$request->Proc);
            }

            $datavendor = count($datarole);
            if(!empty($request->Comment)){
                for ($i = 0; $i < $datavendor; $i++) {
                    PoTrackingComments::create([
                        'Number'    => $request->Number,
                        'ItemNumber'=> $request->Item,
                        'user_by'   => $request->Name,
                        'user_to'   => $datarole[$i],
                        'menu'      => 'Pesan',
                        'comment'   => $request->Comment,
                        'created_at'=> Carbon::now()
                    ]);
                }
                return response()->json(['message'=>'Sended.']);
            } else {
                return response()->json(['message'=>'Not Sent']);
            }
        } catch (Exception $e) {
            $this->ErrorLog($e);
            return redirect()->back()->with('err_message', 'Error Request, Exception Error');
        }
    }
}
