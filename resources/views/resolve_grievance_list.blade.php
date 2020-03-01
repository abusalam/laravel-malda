@extends('layout.master')
@section('content')
<div class="row" id="row-content">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title"> {{__('text.resolved_table_heading')}}</h3>
                {{csrf_field()}}
                <div class="datatbl  " >
                    <table class="table table-striped table-bordered table-hover notice-types-table" id="tbl_grievance_list">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%"> ID</th>
                                <th width="15%">Grievance Date</th>
                                <th width="20%">Name</th>
                                <th width="20%">Mobile No</th>

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
<input name="session_data" type="hidden" value="<?php echo session()->get('user_type');?>" id="session_data">
@endsection
@section('script')
<script src="{{asset('/app/js/resolve_grievance_list.js')}}"></script>

@endsection
