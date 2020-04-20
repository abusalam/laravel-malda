@extends('layout.master')
@section('content')


<div class="row" id="row-content">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">{{__('text.log_details_table_heading')}}</h3>


                <div class="datatbl  ">
                    <table class="table table-striped table-bordered table-hover notice-types-table" id="tbl_log_details" >
                        <thead>
                            <tr>
                                <th width="5%">Sl No</th>
                                <th width="15%">Name</th>
                                {{-- <th width="20%">Session Id</th>  --}}
                                <th width="10%">User Ip</th>
                                <th width="15%">Visited Page</th>
                                <th width="15%">Browser</th>
                                <th width="20%">Created at</th>
                                <th width="20%">Action</th>
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
<div class="modal" style="top:50px"id="log" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Log Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="view_log_details"></div>
        </div>

      </div>
    </div>
  </div>
  {{csrf_field()}}


@endsection

@section('script')

<script src="{{asset('/app/js/log_details.js')}}"></script>



@endsection
