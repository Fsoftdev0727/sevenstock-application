@extends('admin.layouts.app')
@section('title', '방문기록')
@section('css')
<link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<!-- DataTables -->
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" /> 
@endsection
@section('content')
<div class="container-fluid">
    <div class="row mt-3 mb-3">
        <div class="button-items">
            <a href="{{ url('admin/user-record/stocks') }}" class="btn btn-custom-light waves-effect waves-light">주식이체</a>
            <a href="{{ url('admin/user-record/file-transfers') }}" class="btn btn-custom-light waves-effect waves-light">파일전송</a>
            <a href="{{ url('admin/user-record/post-delivery') }}" class="btn btn-custom-light waves-effect waves-light">우편발송</a>
            <a href="{{ url('admin/user-record/visit-record') }}" class="btn btn-secondary waves-effect waves-light">방문기록</a>
            <a href="{{ url('admin/user-record/inquery') }}" class="btn btn-custom-light waves-effect waves-light">전체메모</a>
        </div>
    </div>

    <div class="row pt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="page-title-custom">
                                        <h4>방문기록</h4>
                                    </div>
                                </div>
                                <div class="mb-3 mt-1 col-md-3 d-flex">
                                    <select class="form-control search_filter" id="group_filter" required>
                                        <option value="">담당자</option>
                                        @foreach($groups as $group)
                                            <option value="{{$group->id}}">{{$group->groupName}}</option>
                                        @endforeach
                                    </select>
                                    
                                </div>
                                <div class="mb-3 mt-1 col-md-3 d-flex">
                                    <select class="form-control search_filter" id="company_filter" required>
                                        <option value="">기업 </option>
                                        @foreach($companies as $company)
                                            <option value="{{$company->id}}">{{$company->companyName}}</option>
                                        @endforeach
                                    </select>
                                    
                                </div>
                                <div class="mb-3 mt-1 col-md-3 d-flex">
                                    <select class="form-control search_filter" id="route_filter" required>
                                        <option value="">알게된 경로</option>
                                        @foreach($routeknowns as $routeknown)
                                            <option value="{{$routeknown->id}}">{{$routeknown->routeName}}</option>
                                        @endforeach
                                    </select>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3 mt-1 col-md-6">
                            <div class="justify-content-end d-flex">
                                <form method="POST" accept-charset="UTF-8" id="mass_delete_form" action="{{ url ('admin/user-record/visitdelete') }}">
                                    @csrf
                                    <input id="selected_rows" name="selected_rows" type="hidden">
                                    <input class="btn btn-secondary btn-sm me-2" id="delete-selected" type="submit" value="Delete" style="display:none;">
                                </form>
                                <a href="{{ url('admin/user-record/add_visit_record') }}" class="btn btn-primary btn-sm"><i class="mdi mdi-content-save-move"></i> Add</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive pt-1">
                        <table id="datatable_button" class="table table-bordered align-middle dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th class="align-middle">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" id='select-all-row'><span></span>
                                        </label>
                                    </th>
                                    <th>시작날짜</th>
                                    <th>시작시간</th>
                                    <th>이름</th>
                                    <th>제목</th>
                                    <th>마감날짜</th>
                                    <th>마감시간</th>
                                    <th>상태</th>
                                    <th>유형</th>
                                    <th>Note</th>
                                    <th>관리자</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--status modal content -->
<div id="statusmodal" class="modal fade" role="dialog" aria-labelledby="methodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">상태 선택</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body" id="manage_info">
                <form class="needs-validation" id="StatusForm" novalidate>
                    <meta name="csrf-token" content="{{ csrf_token() }}" />              
                    <div class="mb-3 row">
                        <label class="form-label" for="mstatus">상태 <span class="text-danger">*</span></label>
                        <select class="form-control select_status" id="mstatus" name="mstatus" required>
                            <option value="Active">완료</option>
                            <option value="Pending">진행중</option>
                            <option value="Canceled">취소</option>
                        </select>
                    </div>
                </form>
                <input type="hidden" id="visitid" />
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary waves-effect waves-light btn-sm btn_visit_change">Add</button>
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<style>
.form-check-input, .form-check-input1{
    height: 22px;
    width: 22px;
}
</style>
@stop
@section('javascript')
<script src="{{ asset('assets/libs/select2/js/select2.full.min.js') }}"></script>
<!-- Required datatable js -->
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<!-- Responsive examples -->
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
<!-- Sweet Alerts js -->
<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    $("#delete-selected").hide();
    col_targets = [0, 1, 5];
    var visit_table = $('#datatable_button').DataTable({
        aaSorting: [[ 1, "desc" ]],
        lengthChange:!1,
        processing: true,
        serverSide: true,
        searching: false, 
        paging: false, 
        info: false,
        "ajax": {
            "url": "/admin/user-record/visit-record",
            "data": function ( d ) {
                d.group_filter = $('select#group_filter').val();
                d.company_filter = $('select#company_filter').val();
                d.route_filter = $('select#route_filter').val();
                d.desksearch        = $("#desksearchbox").val();
                d.mobilesearch      = $("#mobilesearchbox").val();
            }
        },
        "language": {
            "sEmptyTable":     "등록된 자료가 없습니다",
            "sInfo":           "_START_ - _END_ / _TOTAL_",
            "sInfoEmpty":      "0 - 0 / 0",
            "sInfoFiltered":   "(총 _MAX_ 개)",
            "sInfoPostFix":    "",
            "sInfoThousands":  ",",
            "sLengthMenu":     "페이지당 줄수 _MENU_",
            "sLoadingRecords": "읽는중...",
            "sProcessing":     "처리중...",
            "sSearch":         "검색:",
            "sZeroRecords":    "검색 결과가 없습니다",
            "oPaginate": {
                "sFirst":    "처음",
                "sLast":     "마지막",
                "sNext":     "다음",
                "sPrevious": "이전"
            },
            "oAria": {
                "sSortAscending":  ": 오름차순 정렬",
                "sSortDescending": ": 내림차순 정렬"
            }
        },
        "dom": 'Bfrtip',
        columnDefs: [ {
            "targets": col_targets,
            "orderable": false,
            "searchable": false
        } ],
        "columns":[
            {"data":"mass_delete"},
            {"data":"startdate"},
            {"data":"starttime"},
            {"data":"name"},
            {"data":"title"},
            {"data":"enddate"},
            {"data":"endtime"},
            {"data":"status"},
            {"data":"type"},
            {"data":"note"},
            {"data":"admin"},
            {"data":"action"}
        ],
        "createdRow": function( row, data, dataIndex ) {
            $( row ).find('td:eq(3)').attr('class', 'bg-light text-primary fw-bold');
        },
    });
    $(".select2").select2({width: '100%'});
    $(".search_filter").select2({width: '100%'});
    $(".select_status").select2({width: '100%',dropdownParent: $("#manage_info")});
    $('select#group_filter, select#company_filter, select#route_filter').on('change', function(){
        visit_table.ajax.reload();
    });
    $("#btn_desksearch").on("click", function(e){
        if($("#desksearchbox").val() != "") {
            visit_table.ajax.reload();
        }
    });
    $("#btn_mobilesearch").on("click", function(e){
        if($("#mobilesearchbox").val() != "") {
            visit_table.ajax.reload();
        }
    });
    $('#desksearchbox').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            visit_table.ajax.reload();
        }
    });
    $('#mobilesearchbox').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            if($("#mobilesearchbox").val() != "") {
                visit_table.ajax.reload();
            }
        }
    });
    $(document).on('click', '#delete-selected', function(e){
        e.preventDefault();
        var selected_rows = [];
        var i = 0;
        $('.form-check-input:checked').each(function () {
            selected_rows[i++] = $(this).val();
        }); 
        
        if(selected_rows.length > 0){
            $('input#selected_rows').val(selected_rows);
            Swal.fire({
                title: "Are you Sure?",
                icon: "warning",
                showCancelButton:!0,
                confirmButtonColor:"#7a6fbe",
                cancelButtonColor:"#f46a6a",
            }).then((willDelete) => {
                if (willDelete.isConfirmed) {
                    $('form#mass_delete_form').submit();
                }
            });
        }  
    });
    $(document).on('click', "a.btn_status", function() {
        $("#mstatus").val($(this).data('status'));
        $('#mstatus').trigger('change');
        $("#visitid").val($(this).data('id'));
        $("#statusmodal").modal('show');
    });
    $(".btn_visit_change").on('click', function(e){
        var form = $("#StatusForm");
        if (form[0].checkValidity() === false) {
            event.preventDefault()
            event.stopPropagation()
            form.addClass('was-validated');
            return;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: `/admin/visit-status`,
            data: {
                status: $("#mstatus").val(),
                visitid: $("#visitid").val()
            },
            type: 'POST',
            success: function(data) {
                if (data.status == "Success") {
                    location.reload();
                } 
            },
            error: function(data){
                console.log(data);
            }
       });
        
    });
</script>
@endsection