@extends('layouts.app')
@section('content')
  
<div class="container"> 
  <div class="row">
    <div class="col-md-12">
        <div class="jumbotron">
            <div class="row">
              <div class="col-lg-8 mx-auto">
              </div>
            </div>
            {!!Form::open(['route' => ['search'],'method' => 'POST', 'id' => 'search_form', 'name' =>'service_form', 'files'=>'true','data-provide'=>"validation"]) !!}
            {!! csrf_field() !!}
            <div class="main">
             <div class="form-group has-feedback has-search">
              <span class="fa fa-search form-control-feedback"></span>
              @if(isset($search))
                <input type="text" name="search" class="form-control" placeholder="Search" value="{{ $search }}">
              @else
                <input type="text" name="search" class="form-control" placeholder="Search">
              @endif
            </div>
            @if(Auth::check())
            <div class="form-group has-feedback has-search text-center">
              <?php $checked = ''; $allchecked='';?>
              @if(isset($my_result))
                <?php if($my_result==1){ $checked='checked';}else{$allchecked = 'checked';}?>
              @endif
                <label class="radio-inline">
                  <input type="radio" name="my_result" value="1" class="text-center search-text-color" {{$checked}}>My results
                </label>
                <label class="radio-inline">
                  <input type="radio" name="my_result" value="0" class="text-center search-text-color" {{$allchecked}}>All bridgit
              </label>
            </div>
            @endif
            </div>
            {!! Form::close() !!}
            @if(count($bridge)>0)
            <div class="row mt-2">
                <div class="col-lg-12">
                    @if(isset($count))
                    <p class="search-text-color">{{$count}} Results found</p>
                    @endif
                    <div class="mt-1">
                        <table id="example" class="table table-striped" style="width:100%">
                          <thead>
                              <tr>
                                  <th width="10%">Title</th>
                                  <th width="10%">Realation</th>
                                  <th width="5%">Creator</th>
                                  <th width="15%">Description</th>
                                  <th width="10%">Tags</th>
                                  <th width="10%">Creation date</th>
                              </tr>
                          </thead>
                          <tbody>
                           @if(!empty($bridge))
                                 <?php $t=1; ?>
                                    @foreach($bridge as $bridges)
                                        <tr>
                                            <td>@if($bridges->comefromNote ==0) <a href="{{$bridges->fromElement->url}}">{{strtoupper($bridges->fromUrl)}} </a> to <a href="{{strtoupper($bridges->toElement->url)}}">{{strtoupper($bridges->toUrl)}} </a> @else {{$bridges->title}} @endif</td>
                                            <td>@if($bridges->relationData) <span class="table-text-color">{{$bridges->relationData->active_name}}</span> @endif</td>
                                            <td> @if($bridges->user) <a href="{{url(str_replace(' ','-',$bridges->user->name).'/profile/'.$bridges->user->id)}}">{{ $bridges->user->name }}</a> @endif</td>
                                            <td>{{$bridges->desc }}</td>
                                             <td>@foreach($bridges->tags as $key=>$tags)
                                                    @if(count($bridges->tags) > ($key+1))
                                                      <span class="table-text-color">{{ $tags }}
                                                      </span>
                                                    @else
                                                      <span class="table-text-color">{{ $tags }}
                                                      </span>
                                                    @endif
                                                  @endforeach
                                            </td>
                                            <td>{{$bridges->created_at->format('Y-m-d')}}</td>
                                        </tr>
                                        <?php $t++ ?>
                                    @endforeach
                                @endif
                          </tbody>
                        </table>
                    </div>
                </div>
              </div>
          </div>
          @else
          <p class="text-center">No Data Found. </p>
          @endif
        </div>
    </div>
  </div>
{{-- search data --}}
@endsection
@section('pageScript')
<script>

  $(document).ready(function() {
    $('#example').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
      'info'        : false,
      'autoWidth'   : true,
      "dom": '<"top"f>rt<"bottom"lp><"clear">'
    });
} );
</script>
@endsection

