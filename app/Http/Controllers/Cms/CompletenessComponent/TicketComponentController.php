<?php

namespace App\Http\Controllers\Cms\CompletenessComponent;

use App\Http\Controllers\Controller;
use App\Models\Table\CompletenessComponent\TicketPRO;
use App\Models\Table\CompletenessComponent\TicketListComponent;
use App\Models\Table\CompletenessComponent\TicketAccQty;
use App\Models\Table\CompletenessComponent\LogHistory;
use App\Models\Table\CompletenessComponent\MaterialTemporary;
use App\Models\View\CompletenessComponent\VwProductionOrder;
use App\Models\View\CompletenessComponent\VwProWithSN;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class TicketComponentController extends Controller
{
    public $status_ongoing = ['CRTD', 'REL', 'DLV', 'PDLV'];

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($this->PermissionMenu('ticket-component-ccr') == 0) {
                return redirect()->back()->with('error', 'Access denied for this user!')->with('title', 'Access denied!');
            }
            return $next($request);
        });
    }

    // Halaman Report Data Ticket Component
    public function TicketInit(Request $request)
    {
        try {
            $date   = Carbon::now();
            if ($this->PermissionActionMenu('ticket-component-ccr')->r == 1) {
                LogHistory::updateOrCreate([
                    'user'  => Auth::user()->id,
                    'menu'  => 'Ticket Material',
                    'date'  => $date->toDateString()
                ], [
                    'time'  => $date->toTimeString()
                ]);
                
                if ($request->ajax()) {
                    $tiket            = TicketPRO::orderBy('id', 'DESC')->get();
                    return DataTables::of($tiket)
                        ->escapeColumns()  //mencegah XSS Attack
                        ->toJson(); //merubah response dalam bentuk Json
                }
                $data           = array(
                    'title'         => 'Ticket Component',
                    'actionmenu'    => $this->PermissionActionMenu('ticket-component-ccr')
                );
                return view('completeness-component/ticket-component/index')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    // Halaman Detail Report Ticket Component
    public function TicketReportDetail($id_ticket)
    {
        try {
            if ($this->PermissionActionMenu('ticket-component-ccr')->r == 1) {
                $date   = Carbon::now();
                $query  = TicketPRO::where('id', htmlspecialchars($id_ticket))->first();

                LogHistory::updateOrCreate([
                    'user'          => Auth::user()->id,
                    'menu'          => 'Detail Ticket',
                    'description'   => $query->ticket,
                    'date'          => $date->toDateString()
                ], [
                    'time'          => $date->toTimeString()
                ]);     

                $material       = TicketListComponent::where('id_ticket', htmlspecialchars($id_ticket))->orderBy('status', 'asc')->get();
                $qty_checked    = TicketListComponent::where('id_ticket', htmlspecialchars($id_ticket))->where('status', 1)->count();
                $qty_all        = $material->count();
                $data       = array(
                    'apps'          => $query,
                    'material'      => $material,
                    'qty_checked'   => $qty_checked,
                    'qty_all'       => $qty_all,
                    'title'         => 'Detail Ticket',
                    'actionmenu'    => $this->PermissionActionMenu('ticket-component-ccr')
                );
                return view('completeness-component/ticket-component/detail_ticket')->with('data', $data);
            } else {
                return redirect()->back()->with('error', 'Access denied!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
    
    // Halaman Create Ticket dari menu planning PRO On Going
    public function CreateTicketPRO($pro)
    {
        try {
            if ($this->PermissionActionMenu('ticket-component-ccr')->c == 1) {
                $query      = VwProductionOrder::select('production_order', 'product_number', 'product_description', 'quantity')->where('production_order', htmlspecialchars($pro))->first();
                $sn_all     = VwProWithSN::select('production_order', 'serial_number')->whereIn('status', $this->status_ongoing)->where('production_order', htmlspecialchars($pro))->orderBy('sch_start_date', 'asc')->orderBy('production_order', 'asc')->orderBy('serial_number', 'asc')->get();
                $i          = 0;
                foreach ($sn_all as $ss) {
                    $dataSN = [
                        'production_order'     => $ss->production_order,
                        'serial_number'        => $ss->serial_number,
                        'sn_index'  => $i++
                    ];
                    $datall[] = $dataSN;
                }
    
                $material   = MaterialTemporary::select('id', 'BDMNG as requirement_quantity', 'ENMNG as good_issue', 'request_qty', 'MATNR as material_number', 'MAKTX as material_description', 'MTART as material_type', 'STOCK as stock', 'BDTER as requirement_date')->where('AUFNR', htmlspecialchars($pro))
                    ->WhereNotNull('MATNR')
                    ->where(DB::raw('BDMNG-ENMNG-request_qty'), '>', 0)
                    ->where('STOCK', '>', 0)
                    ->orderBy('BDTER', 'ASC')
                    ->get();
    
                $data       = array(
                    'apps'          => $query,
                    'sn_all'        => $datall,
                    'material'      => $material,
                    'title'         => 'Ticket Production Order',
                    'actionmenu'    => $this->PermissionActionMenu('ticket-component-ccr')
                );
                return view('completeness-component/ticket-component/create_ticket')->with('data', $data);
            } else {
                return redirect('completeness-component')->with('error', 'Access denied!');
            }
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    // Proses Create Ticket ( Halaman planning PRO On Going )
    public function prosesCreateTicket(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('ticket-component-ccr')->c == 1) {
                $id_check = $request->id_checked;
                if(!empty($id_check)){
                    // get all data id
                    $data = count($request->id_all) ;
                    for ($i = 0; $i < $data; $i++) {
                        $dataku[] = [
                            'id'            => $request->id_all[$i],
                            'request_quantity'   => $request->request_qty[$i]
                        ];
                    }
                    // get checked data
                    foreach ($dataku as $data_qty) {
                        foreach ($id_check as $data_check) {
                            if ($data_qty['id'] == $data_check) {
                                $data_fix[] = $data_qty;
                            }
                        }
                    }
                    // get data material dari tabel material temporary
                    $database = MaterialTemporary::
                        select('id', 'MATNR as material_number', 'MAKTX as material_description', 'MEINS as base_unit', 'MTART as material_type', 'BDTER as requirement_date', 'BDMNG as requirement_quantity', 'ENMNG as good_issue', 'STOCK as stock')
                        ->whereIn('id', $request->id_checked)
                        ->get()
                        ->toArray();

                    // get data yang di ceklist pada form 
                    foreach ($database as $db) {
                        foreach ($data_fix as $fix) {
                            if ($db['id'] == $fix['id']) {
                                $data_final[] = $db + $fix;
                            }
                        }
                    }
                    // hitung jumlah ticket yang sudah dibuat pada PRO tersebut
                    $count_tiket = TicketPRO::where('production_order', $request->production_order)->count();
                    $jml_tiket = $count_tiket+1;
                    $date = Carbon::now()->format('dmy');
                    // nomor ticket
                    $tiket = $request->production_order.'/'.$jml_tiket."/TICK".'/'.$date;
                    $data_ticket = [
                        'ticket'            => $tiket,
                        'production_order'  => $request->production_order,
                        'req_date'          => $request->request_date,
                        'created_by'        => Auth::user()->name
                    ];
                    // Memasukan nomor tiket ke database
                    $insertTicket = TicketPRO::create($data_ticket);
                    if ($insertTicket) {
                        $number = [
                            // get id ticket
                            'id_ticket'         => $insertTicket->id,
                            'production_order'  => $request->production_order
                        ];
                        foreach ($data_final as $key) {
                            unset($key['id']);
                            $data_material[] = $number + $key;
                        }
                        // memasukan data material ke database
                        $insertMaterialTicket = TicketListComponent::insert($data_material);
                        if ($insertMaterialTicket) {
                            $date = Carbon::now();
                            DB::connection('mysql7')->statement('CALL sp_update_ticket_pro');
                            LogHistory::updateOrCreate([
                                'user'          => Auth::user()->id,
                                'menu'          => 'Create Ticket',
                                'description'   => $tiket,
                                'date'          => $date->toDateString()
                            ], [
                                'time'          => $date->toTimeString()
                            ]);
                            return redirect(url('production-order-planning/'.$request->production_order))->with('success', 'Ticket created successfully!')->with('title', 'Success!');
                        }
                    }
                }else {
                    return redirect()->back()->with('error', 'No Checkbox selected!')->with('title', 'Error!');
                }
            } else {
                return redirect('completeness-component')->with('error', 'Access denied!');
            }            
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }

    // Proses Update Ticket ( Halaman Report Data Ticket )
    public function prosesUpdateTicket(Request $request)
    {
        try {
            if ($this->PermissionActionMenu('ticket-component-ccr')->u == 1) {
                if (!empty($request->id_checked)) {
                    // get all data id
                    $data = count($request->id_all) ;
                    for ($i = 0; $i < $data; $i++) {
                        $dataku[] = [
                            'id_component' => $request->id_all[$i],
                            'accepted_qty' => $request->acc_qty[$i]
                        ];
                    }
                    // get checked data
                    foreach ($dataku as $data_qty) {
                        foreach ($request->id_checked as $data_check) {
                            if ($data_qty['id_component'] == $data_check) {
                                $data_fix[] = $data_qty;
                            }
                        }
                    }
                    // Memasukan jumlah quantity yang di ACC ke database 
                    $insert = TicketAccQty::insert($data_fix);
                    if ($insert) {
                        foreach ($request->id_checked as $checked) {
                            // Menghitung total quantity yang sudah disetujui untuk komponen yang di ceklist
                            $sum = TicketAccQty::where('id_component', $checked)->sum('accepted_qty');
                            //  Proses update total accepted quantity pada tabel TicketListComponent
                            TicketListComponent::where('id', $checked)->update(['accepted_quantity' => $sum]);
                            //  Jika jumlah request quantity == accepted quantity, status berubah menjadi 1 
                            $data = TicketListComponent::select('request_quantity', 'accepted_quantity')->where('id', $checked)->first();
                            if ($data->accepted_quantity >= $data->request_quantity) {
                                TicketListComponent::where('id', $checked)->update(['status' => 1]);
                            }
                        }
                        
                    }
                    //  Update Status PRO
                    $total_item   = TicketListComponent::where('id_ticket', $request->id_ticket)->count();
                    $item_checked = TicketListComponent::where('id_ticket', $request->id_ticket)->where('status', 1)->count();
                    if ($total_item == $item_checked) {
                        // CLOSE
                        TicketPRO::where('id', $request->id_ticket)->update(['status' => 1]);
                    }elseif ($item_checked > 0 && $item_checked < $total_item) {
                        // PARTIAL
                        TicketPRO::where('id', $request->id_ticket)->update(['status' => 2]);
                    }
                    return redirect()->back()->with('success', 'Data saved successfully!')->with('title', 'Success!');
                }else {
                    return redirect()->back()->with('error', 'No checkbox selected!')->with('title', 'Error!');
                } 
            } else {
                return redirect('completeness-component')->with('error', 'Access denied!');
            } 
        } catch (Exception $e) {    
            $this->ErrorLog($e);
            return redirect()->back()->with('error', 'Error Request, Exception Error ');
        }
    }
}
