@extends('layout.master')
@section('content')
<div class="row" id="row-content">
    <div class="col-12">
        <div class="card">

            <div class="card-body">
                <h3 class="card-title">{{__('text.recieved_table_heading')}}</h3>
                {!! csrf_field() !!}
                <div class="datatbl ">
                    <table class="table table-striped table-bordered table-hover notice-types-table" id="tbl_grievance_list">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%">ID</th>
                                <th width="25%">Name</th>
                                <th width="25%">Mobile No</th>
                                <th width="25%">Email</th>

                                <th width="10%">Action</th>
                                </tr>

                        </thead>
                        <tbody></tbody>
                        <!-- Table Footer -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('/app/js/grievance_list.js')}}"></script>

@endsection
