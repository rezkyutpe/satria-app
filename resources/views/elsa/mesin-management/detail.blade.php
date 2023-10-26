@extends('fe-layouts.master')

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="card card-flush">
                <a href="{{ url('mesin-management') }}"
                    class="btn btn-color-gray-600 btn-active-color-primary text-start ms-3 mt-5"><span
                        class="svg-icon svg-icon-5"><i class="fa fa-angle-left fs-2"></i></span>Back to Mesin Management</a>

                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <h2 class="text-end pe-0">Detail Mesin</h2>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-md-6">
                            <table>
                                <tr style="line-height: 25px;">
                                    <td class="fs-6">Nama</td>
                                    <td class="fs-6"> : {{ $Mesin->desc_mesin }}</td>
                                </tr>
                                <tr style="line-height: 25px;">
                                    <td class="fs-6">Equipment Number</td>
                                    <td class="fs-6"> : {{ $Mesin->equipment_number }}</td>
                                </tr>
                                <tr style="line-height: 25px;">
                                    <td class="fs-6">Dimension</td>
                                    <td class="fs-6"> : {{ $Mesin->dimension }}</td>
                                </tr>
                                <tr style="line-height: 25px;">
                                    <td class="fs-6">Location</td>
                                    <td class="fs-6"> : {{ $Mesin->location }}</td>
                                </tr>
                                <tr style="line-height: 25px;">
                                    <td class="fs-6">Room</td>
                                    <td class="fs-6"> : {{ $Mesin->room }}</td>
                                </tr>
                                <tr style="line-height: 25px;">
                                    <td class="fs-6">Manufacture Asset</td>
                                    <td class="fs-6"> : {{ $Mesin->manufacture_asset }}</td>
                                </tr>
                                <tr style="line-height: 25px;">
                                    <td class="fs-6">Equipment Category</td>
                                    <td class="fs-6"> : {{ $Mesin->equipment_category }}</td>
                                </tr>
                                <tr style="line-height: 25px;">
                                    <td class="fs-6">Planner Group</td>
                                    <td class="fs-6"> : {{ $Mesin->planner_group }}</td>
                                </tr>
                                <tr style="line-height: 25px;">
                                    <td class="fs-6">Date Valid From</td>
                                    <td class="fs-6"> : {{ $Mesin->date_valid_from }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table> 
                                <tr style="line-height: 25px;">
                                    <td class="fs-6">Maintenance Plant</td>
                                    <td class="fs-6"> : {{ $Mesin->maintenance_plant }}</td>
                                </tr>  
                                @if (!empty($DetailMesin))
                                    <tr style="line-height: 25px;">
                                        <td class="fs-6">Category</td>
                                        <td class="fs-6"> : {{ $DetailMesin->category }}</td>
                                    </tr>
                                    <tr style="line-height: 25px;">
                                        <td class="fs-6">Function</td>
                                        <td class="fs-6"> : {{ $DetailMesin->function }}</td>
                                    </tr>
                                    <tr style="line-height: 25px;">
                                        <td class="fs-6">Material</td>
                                        <td class="fs-6"> : {{ $DetailMesin->material }}</td>
                                    </tr>
                                    <tr style="line-height: 25px;">
                                        <td class="fs-6">Length</td>
                                        <td class="fs-6"> : {{ $DetailMesin->length_mesin }}</td>
                                    </tr>
                                    <tr style="line-height: 25px;">
                                        <td class="fs-6">Width</td>
                                        <td class="fs-6"> : {{ $DetailMesin->width }}</td>
                                    </tr>
                                    <tr style="line-height: 25px;">
                                        <td class="fs-6">Thickness</td>
                                        <td class="fs-6"> : {{ $DetailMesin->thickness }}</td>
                                    </tr>
                                    <tr style="line-height: 25px;">
                                        <td class="fs-6">Tools</td>
                                        <td class="fs-6"> : {{ $DetailMesin->tools }}</td>
                                    </tr>
                                    <tr style="line-height: 25px;">
                                        <td class="fs-6">Remarks</td>
                                        <td class="fs-6"> : {{ $DetailMesin->remarks }}</td>
                                    </tr>
                                @else
                                    <tr style="line-height: 25px;">
                                        <td class="fs-6">Category</td>
                                        <td class="fs-6"> : </td>
                                    </tr>
                                    <tr style="line-height: 25px;">
                                        <td class="fs-6">Function</td>
                                        <td class="fs-6"> : </td>
                                    </tr>
                                    <tr style="line-height: 25px;">
                                        <td class="fs-6">Material</td>
                                        <td class="fs-6"> : </td>
                                    </tr>
                                    <tr style="line-height: 25px;">
                                        <td class="fs-6">Length</td>
                                        <td class="fs-6"> : </td>
                                    </tr>
                                    <tr style="line-height: 25px;">
                                        <td class="fs-6">Width</td>
                                        <td class="fs-6"> : </td>
                                    </tr>
                                    <tr style="line-height: 25px;">
                                        <td class="fs-6">Thickness</td>
                                        <td class="fs-6"> : </td>
                                    </tr>
                                    <tr style="line-height: 25px;">
                                        <td class="fs-6">Tools</td>
                                        <td class="fs-6"> : </td>
                                    </tr>
                                    <tr style="line-height: 25px;">
                                        <td class="fs-6">Remarks</td>
                                        <td class="fs-6"> : </td>
                                    </tr>
                                @endif

                            </table>
                        </div>
                    </div>
                    <h2 class="mt-12">Spek Mesin</h2>
                    <table>
                        <tr>
                            <th style="min-width:175px;">Description</th>
                            <th style="min-width:175px;">Unit</th>
                            <th style="min-width:175px;">Value</th>
                        </tr>
                        @if (count($SpekMesin) > 0)
                            @foreach ($SpekMesin as $sm)
                                <tr>
                                    <td>{{ $sm->description }}</td>
                                    <td>{{ $sm->unit }}</td>
                                    <td>{{ $sm->value }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>No data available</td>
                                <td>No data available</td>
                                <td>No data available</td>
                            </tr>
                        @endif
                    </table>
                    <h2 class="mt-12">Mesin User Manual File</h2>
                    @if (count($FileMesin) > 0)
                        <iframe class="mb-1" src="{{ url('public') }}/mesinfile/{{ $FileMesin[0]->file_name }}"
                            width="20%" height="250px">
                        </iframe>
                        <br>
                        <a class="btn btn-primary" id="viewFileMesin"
                            onclick="location.href = '{{ url('view-file') }}/{{ $FileMesin[0]->file_name }}'">View
                            File</a>
                    @else
                        <P>No data available</P>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
