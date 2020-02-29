@extends('layout.master')
@section('content')
<div class="row" id="row-content">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title"> {{__('text.closed_table_heading')}}</h3>
                {{csrf_field()}}
                <div class="datatbl  " style="width: 96%;margin-left: 20px;">
                    <table class="table table-striped table-bordered table-hover notice-types-table" id="tbl_grievance_list" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 5%;"> ID</th>
                                <th style="width: 15%;">Grievance Date</th>
                                <th style="width: 10%;">Name</th>
                                <th style="width: 10%;">Mobile No</th>
                                <th style="width: 15%;">Closed Date</th>
                                <th style="width: 15%;">Action</th>
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
<script src="{{asset('/app/js/close_grievance_list.js')}}"></script>
@endsection
