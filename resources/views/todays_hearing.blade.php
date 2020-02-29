@extends('layout.frontmaster')
@section('content')


<div class="row" id="row-content">
    <div class="col-12">                        
        <div class="card">
            <div class="card-body">
               
                    
                    <div class="push-right" style="margin-bottom: 20px;">
                        <a class="btn btn-info" href="search_case" >Search Case by Number</a>
                    </div>
                    <div class="push-left" style="margin-bottom: 20px;">
                        <h3 class="card-title">{{__('text.todays_hearing')}}</h3>
                    </div>
                    
                
                
                
                {{csrf_field()}}
                  <div class="datatbl  " style="width: 96%;margin-left: 20px;">
                    <table class="table table-striped table-bordered table-hover notice-types-table" id="tbl_case_list" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 15%;">Case Number</th>              
                                <th style="width: 20%;">Next Hearing Date</th> 
                                <th style="width: 45%;">Description</th>   
                                
                            </tr>

                        </thead>
                        <tbody></tbody>
                        <!-- Table Footer -->
                    </table>
                </div>

                
                <div id="tbl_t" style="padding-left: 10px;">
                </div> 
            </div> 
        </div> 
    </div> 
</div> 

@endsection

@section('script')
<script src="{{asset('/app/js/todays_hearing.js')}}"></script>
@endsection