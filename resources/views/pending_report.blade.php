@extends('layout.master')
@section('content')
<div class="row" id="row-content">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Pending Grievance</h3>
                {{csrf_field()}}
                <div class="datatbl  ">
                    <table class="table table-striped table-bordered table-hover notice-types-table" id="tbl_grievance_list" >
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="40%">User</th>
                                <th width="40%">Designation</th>
                                <th width="40%">No of Grievance</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('/app/js/pending_report.js')}}"></script>
@endsection
