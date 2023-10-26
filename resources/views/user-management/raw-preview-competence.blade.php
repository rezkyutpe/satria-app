@extends('fe-layouts.master')

@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    
    <div class="content flex-row-fluid" id="kt_content">
    <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <div class="card card-flush">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <h2 class="text-end pe-0">Raw Preview User Competence</h2>
                    </div>
                    <form method="post" action="{{url('insert-user-competence')}}">
                    {{csrf_field()}}
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                <button type="submit" class="btn btn-primary">
                                                <span class="svg-icon svg-icon-2">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
                                                    </svg>
                                                </span>
                                Submit</button>
                            </div>
                        </div>
                    <div class="card-body pt-0">
                    <div class="table-responsive">
                            <table class="table table-hover table-rounded table-striped border gy-7 gs-7">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                        <th class="min-w-300px">LETTERNO</th>
                                        <th class="min-w-50px">NRP</th>
                                        <th class="min-w-200px">NAME</th>
                                        <th class="max-w-20px">PRFRMNCE</th>
                                        <th>AMOUNT</th>
                                        <th>KEY</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
								    @for($i=0;$i < count($data['pis']);$i++)
									    <tr>
                                            <td>
                                                <input type="text" class="form-control" name="letterno[]" value="{{ 'SK/GOL/'.($data['lastrows']+$i+1).'/'.date_format(date_create(str_replace('/','-',$data['pis'][$i][0])),'m/Y') }}">
                                            </td>
                                            <td>
                                                <input type="hidden" class="form-control" name="datesigned[]" value="{{ date_format(date_create(str_replace('/','-',$data['pis'][$i][0])),'Y-m-d') }}">
                                                <input type="hidden" class="form-control" name="locationsigned[]" value="{{ $data['pis'][$i][1] }}">
                                                <input type="text" class="form-control" name="nrp[]" value="{{ $data['pis'][$i][2] }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="name[]" value="{{ $data['pis'][$i][3] }}">
                                            </td>
                                            <td>
                                                <input type="hidden" class="form-control" name="worklocation[]" value="{{ $data['pis'][$i][4] }}">
                                                <input type="text" class="form-control" name="performance[]" value="{{ str_replace(',','.',$data['pis'][$i][5]) }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="amount[]" value="{{ $data['pis'][$i][6] }}">
                                                <input type="hidden" class="form-control" name="president[]" value="{{ $data['pis'][$i][7] }}">
                                                <input type="hidden" class="form-control" name="manager[]" value="{{ $data['pis'][$i][8] }}">
                                                <input type="hidden" class="form-control" name="company[]" value="{{ $data['pis'][$i][9] }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="key[]" value="{{ $data['pis'][$i][10] }}">
                                                <input type="hidden" class="form-control" name="gender[]" value="{{ $data['pis'][$i][11] }}">
                                            </td>
                                        </tr>
                                    @endfor
                                    </tbody>
                            </table>
                            </div>
                        </form>
                    </div>
                </div>
    
            </div>
        </div>
    </div>

</div>

@endsection

@section('myscript')
@endsection