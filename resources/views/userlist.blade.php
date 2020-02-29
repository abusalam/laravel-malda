@extends('layout.master')
@section('content')

<div class="row" id="row-content">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
				<h3 class="card-title">{{__('text.user_list')}}</h3>



                <div class="form-horizontal">
                    <div class="form-group row">
                        {{csrf_field()}}
                         
                        <div class="datatbl " style="width: 96%;margin: 20px;">
                            <table class="table table-striped table-bordered table-hover" id="tbl_user_list" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>              
                                        <th>Mobile Number</th> 
                                        <th>Designation</th>   
                                        <th>Action</th>
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
    </div> 
</div>



@endsection

@section('script')

<script src="{{asset('/app/js/user_list.js')}}"></script>


@endsection 