@component('mail::message')
#Mom QFD

Hi, {{ $data['name'] }}.<br>

@component('mail::table')
| Material Number        | : {{ $data['matnum'] }}       |  
| :--- | :--------- |
| Material Description        | : {{ $data['matdesc'] }}       |  
| SO Number        | : {{ $data['noso'] }}       |  
| Customer        | : {{ $data['cust'] }}       |  
| Qty        | : {{ $data['qty'] }}       |  
| Req Delive Date       | : {{ $data['req_deliv_date'] }}       |  
| Note        | : {{ $data['note'] }}       |  
@endcomponent
@component('mail::table')
| Process       | From       | To         | Diff         | Pic         | Remark         |
| :------------ | :--------- | :------------- | :------------- | :------------- | :------------- |
@foreach ($data['message']  as $row)
| {{ $row->id_proses}} | {{ $row->from}} | {{ $row->to}} | {{ $row->diff." Days" }} | {{ $row->pic }} | {{ $row->remark }} |
@endforeach
@endcomponent
@component('mail::table')
| Material       | Material Desc       | Item         | Component         | Component Desc         | QTY         |
| :------------ | :--------- | :------------- | :------------- | :------------- | :------------- |
@foreach ($data['bom']  as $rows)
| {{ $rows->material_qfd }} | {{ $rows->material_desc}} | {{ $rows->item}} | {{ $rows->component }} | {{ $rows->component_desc }} | {{ $rows->qty. " ".$rows->oum }} |
@endforeach
@endcomponent
Thanks,

{{ config('app.name') }}
@endcomponent