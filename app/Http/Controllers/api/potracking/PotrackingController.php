<?php

namespace App\Http\Controllers\api\potracking;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Models\View\PoTracking\VwViewTicket;
use App\Models\Table\PoTracking\DetailTicket;
use App\Models\Table\PoTracking\LogHistoryTicket;
use App\Models\Table\PoTracking\ParameterSla;
use Illuminate\Support\Carbon;
use DateTime;
use DateInterval;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Validator;

class PotrackingController extends BaseController
{
    /**
     * Scan api
     *
     * @return \Illuminate\Http\Response
     */


    public function securityscan(Request $request)
    {
        $user = User::where('token',$request->uuid)->first();
        if (empty($user)) {
            return $this->sendError('User Token Not Found',$user);
        }else{
            //==================  Untuk Scan saat di Security  =========================
            $getticketid    = strtok($request->scanid, '|');
            $gt             = DetailTicket::where('TicketID',$getticketid)->first();
            $logHS          = LogHistoryTicket::where('idticket',$getticketid)->where('description','Scan Security')->first();
            // $sla            = ParameterSla::select('ticket_hour')->first();

            if(!empty($gt)){
                $tglsekarang        = Carbon::now()->format('Y-m-d');
                $jamsekarang        = Carbon::now()->format('H:i:s');
                $tgldanjamsekarang  = Carbon::now()->format('Y-m-d H:i:s');

                //Deklarasi Variable
                $dt                 = Carbon::parse($gt->DeliveryDate);
                $deliverydate       = $dt->format('Y-m-d');
                $deliverydatefull   = $dt->format('d F Y');
                $deliverytime       = $dt->format('H:i:s');

                // $dt->add(new DateInterval('PT'.$sla->ticket_hour.'H'));
                // $deliverytime2      =  $dt->format('H:i:s');

                //data ticketnya
                $NoTicket       = $gt['TicketID'];
                $PONumber       = $gt['Number'];
                $DeliveryDate   = $gt['DeliveryDate'];

                if(!empty($logHS)){
                    $IncomingDate   = $tglsekarang;
                    $IncomingTime   = $jamsekarang;
                    $ScannedBy      = $user->name;
                    // $ScannedBy      = 'Testing Username';
                }else{
                    if($gt->status == 'S'){
                        $IncomingDate   = $logHS['datelog'];
                        $IncomingTime   = $logHS['timelog'];
                        $ScannedBy      = $logHS['name'];
                    }
                    elseif($gt->status == 'D'){
                        $IncomingDate   = $tglsekarang;
                        $IncomingTime   = $jamsekarang;
                        $ScannedBy      = $user->name;
                        // $ScannedBy      = 'Testing Username';
                    }
                    else{
                        $IncomingDate   = NULL;
                        $IncomingTime   = NULL;
                        $ScannedBy      = NULL;
                    }
                }

                $datagt = [
                    'NoTicket'              => $NoTicket,
                    'PONumber'              => $PONumber,
                    'RequestDeliveryDate'   => $DeliveryDate,
                    'IncomingDate'          => $IncomingDate.' '.$IncomingTime,
                    'ScannedBy'             => $ScannedBy
                ];

                //data security yg scan
                $datalogscansecurity =  [
                    'iduser'        => $user->id,
                    // 'iduser'        => 9999,
                    'name'          => $user->name,
                    // 'name'          => 'Testing Username',
                    'idticket'      => $NoTicket,
                    'description'   => "Scan Security",
                    'datelog'       => $tglsekarang,
                    'timelog'       => $jamsekarang
                ];

                //Kondisi kedatangan produk
                if($tglsekarang == $deliverydate){
                    // if(($jamsekarang >= $deliverytime) && ($jamsekarang <= $deliverytime2)){
                        if($gt->status == 'S'){
                            return $this->sendResponse(array($datagt), 'Ticket has been scanned');
                        }
                        elseif($gt->status == 'D'){
                            //Insert ke DB PO tracking table log_history_security
                            LogHistoryTicket::insert($datalogscansecurity);

                            //Update ticket status menjadi "S"
                            DetailTicket::where('TicketID',$getticketid)->where('status', 'D')->update(['status' => 'S', 'SecurityDate' => $tgldanjamsekarang, 'LastModifiedBy' => $ScannedBy]);
                            unset($getticketid);
                            return $this->sendResponse(array($datagt), 'Approve. Ticket is valid.');
                        }
                        elseif($gt->status == 'W'){
                            return $this->sendResponse(array($datagt), 'Ticket has been scanned by Warehouse');
                        }
                        else{
                            // DetailTicket::where('TicketID',$getticketid)->where('status', 'D')->update(['status' => 'C']);
                            return $this->sendError('Disapprove. Please check your ticket');
                        }
                    // }
                    // elseif($jamsekarang < $deliverytime){
                    //     if($gt->status == 'S'){
                    //         return $this->sendResponse(array($datagt), 'Ticket has been scanned.');
                    //     }
                    //     elseif($gt->status == 'D'){
                    //         return $this->sendError('Disapprove. Please wait until '.$deliverytime);
                    //     }
                    //     elseif($gt->status == 'W'){
                    //         return $this->sendResponse(array($datagt), 'Ticket has been scanned by Warehouse');
                    //     }
                    //     else{
                    //         DetailTicket::where('TicketID',$getticketid)->where('status', 'D')->update(['status' => 'C']);
                    //         return $this->sendError('Disapprove. Please create new ticket');
                    //     }
                    // }
                    // else{
                    //     if($gt->status == 'S'){
                    //         return $this->sendResponse(array($datagt), 'Ticket has been scanned.');
                    //     }
                    //     elseif($gt->status == 'W'){
                    //         return $this->sendResponse(array($datagt), 'Ticket has been scanned by Warehouse');
                    //     }
                    //     else{
                    //         DetailTicket::where('TicketID',$getticketid)->where('status', 'D')->update(['status' => 'C']);
                    //         return $this->sendError('Disapprove. Please create new ticket');
                    //     }
                    // }
                }
                elseif($tglsekarang > $deliverydate){
                    if($gt->status == 'S'){
                        return $this->sendResponse(array($datagt), 'Ticket has been scanned.');
                    }
                    elseif($gt->status == 'W'){
                        return $this->sendResponse(array($datagt), 'Ticket has been scanned by Warehouse');
                    }
                    elseif($gt->status == 'E'){
                        return $this->sendError('Disapprove. Ticket Expired');
                    }
                    else{
                        DetailTicket::where('TicketID',$getticketid)->where('status', 'D')->update(['status' => 'E', 'LastModifiedBy' => 'Scan Ticket QR']);
                        unset($getticketid);
                        return $this->sendError('Disapprove. Date expired, please create new ticket');
                    }
                }
                else{
                    if($gt->status == 'S'){
                        return $this->sendResponse(array($datagt), 'Ticket has been scanned.');
                    }
                    elseif($gt->status == 'W'){
                        return $this->sendResponse(array($datagt), 'Ticket has been scanned by Warehouse');
                    }
                    else{
                        return $this->sendError('Disapprove.Please comeback at '.$deliverydatefull);
                    }
                }
            }
            else{
                return $this->sendError('Ticket not Available. Please create new ticket');
            }
        }
    }

    public function getTicketList(Request $request)
    {
        $user = User::where('token',$request->uuid)->first();
        if (empty($user)) {
            return $this->sendError('User Token Not Found',$user);
        }else{
                $getListTicket = DetailTicket::select('detailticketingdelivery.*','log_history_ticket.datelog','log_history_ticket.timelog','log_history_ticket.description')->leftJoin('log_history_ticket', 'detailticketingdelivery.TicketID', '=', 'log_history_ticket.idticket')
                                                ->where('iduser',$user->id)
                                                ->groupBy('TicketID')
                                                ->orderBy('datelog','desc')
                                                ->limit(10)
                                                ->get()
                                                ->toArray();

                if(!empty($getListTicket)){
                    return $this->sendResponse($getListTicket, 'Berhasil Menampilkan Data.');
                }
                else{
                    return $this->sendError('List Data tidak ditemukan');
                }
        }
    }

    public function getTicketDetail(Request $request)
    {
        $user = User::where('token',$request->uuid)->first();
        if (empty($user)) {
            return $this->sendError('User Token Not Found',$user);
        }else{
                $getDetailTicket = DetailTicket::where('detailticketingdelivery.TicketID', $request->ticketid)
                    ->leftJoin('purchasingdocumentitem', 'detailticketingdelivery.PDIID', '=', 'purchasingdocumentitem.ID')
                    ->leftJoin('po', 'po.ID', '=', 'purchasingdocumentitem.POID')
                    ->leftJoin('uservendors', 'uservendors.VendorCode', '=', 'po.VendorCode')
                    ->selectRaw('uservendors.Name AS Vendor, IFNULL(`po`.`PurchaseOrderCreator`,`po`.`CreatedBy`) AS PurchaseOrderCreator, 
                    detailticketingdelivery.ItemNumber,detailticketingdelivery.Material,detailticketingdelivery.Description,detailticketingdelivery.Quantity,detailticketingdelivery.TicketID,detailticketingdelivery.status')
                    ->where('detailticketingdelivery.status','!=','C')
                    ->get()
                    ->toArray();

                if(!empty($getDetailTicket)){
                    return $this->sendResponse($getDetailTicket, 'Berhasil Menampilkan Data.');
                }
                else{
                    return $this->sendError('Detail Data tidak ditemukan');
                }
        }
    }

    public function getIncomingTicket(Request $request)
    {
        $user = User::where('token',$request->uuid)->first();
        if (empty($user)) {
            return $this->sendError('User Token Not Found',$user);
        }else{
                $tgl_now = Carbon::now()->format('Y-m-d');
                // $tgl_now = Carbon::parse('2023-01-12')->format('Y-m-d');
                $getListTicket = DetailTicket::where('DeliveryDate', 'like', $tgl_now.'%')
                    ->where('status', 'D')
                    ->selectRaw('TicketID,CreatedBy,
                        DATE_FORMAT(DATE(DeliveryDate), "%d-%m-%Y") AS datelog,TIME(DeliveryDate) AS timelog')
                    ->groupBy('TicketID')
                    ->orderBy('CreatedBy','asc')
                    ->get()
                    ->toArray();
                if(!empty($getListTicket)){
                    return $this->sendResponse($getListTicket, 'Berhasil Menampilkan Data.');
                }
                else{
                    return $this->sendError('List Data tidak ditemukan');
                }
        }
    }

    public function getHistoryScanTicket(Request $request)
    {
        $user = User::where('token',$request->uuid)->first();
        if (empty($user)) {
            return $this->sendError('User Token Not Found',$user);
        }else{
            $getListTicket = LogHistoryTicket::
                leftJoin('detailticketingdelivery', 'detailticketingdelivery.TicketID', '=', 'log_history_ticket.idticket')
                ->selectRaw('detailticketingdelivery.TicketID AS ticketid,
                    log_history_ticket.name AS scannedby,
                    DATE_FORMAT(DATE(log_history_ticket.datelog), "%d-%m-%Y") AS datelog,
                    log_history_ticket.timelog AS timelog')
                ->groupBy('detailticketingdelivery.TicketID')
                ->orderBy('log_history_ticket.datelog','desc')
                ->limit(10)
                ->get()
                ->toArray();

            if(!empty($getListTicket)){
                return $this->sendResponse($getListTicket, 'Berhasil Menampilkan Data.');
            }
            else{
                return $this->sendError('List Data tidak ditemukan');
            }
        }
    }
}
