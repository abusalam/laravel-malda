@extends('layout.master')
@section('content')


<div class="row" id="row-content">
    <div class="col-12">                        
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">{{__('text.log_details_table_heading')}}</h3>
                

                <div class="datatbl  " style="width: 96%;margin-left: 20px;">
                    <table class="table table-striped table-bordered table-hover notice-types-table" id="tbl_log_details" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width: 5%;">Sl No</th>
                                <th style="width: 15%;">Name</th>              
                                {{-- <th style="width: 20%;">Session Id</th>  --}}
                                <th style="width: 10%;">User Ip</th>   
                                <th style="width: 15%;">Visited Page</th>
                                {{-- <th style="width: 15%;">Description</th> --}}
                                <th style="width: 20%;">Created at</th>
                                <th style="width: 20%;">Action</th>
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