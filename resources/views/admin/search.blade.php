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
              <?php $checked = ''; $all_bridgit = '';$page_result= '';?>
              @if(isset($my_result))
                <?php if($my_result==1){ $checked='checked';}?>
              @endif
              @if(isset($all_result))
                <?php if($all_result==1){ $all_bridgit='checked';}?>
              @endif
              @if(isset($page_based))
                <?php if($page_based==1){ $page_result='checked';}?>
              @endif
                <div class="checkbox">
                    <input id="my_result" type="checkbox" name="my_result" {{$checked}}>
                    <label for="checkbox1">
                        My Bridgework
                    </label>
                    <input id="all_result" type="checkbox" name="all_result" {{$all_bridgit}}>
                    <label for="checkbox1">
                        All Bridgit
                    </label>
                    <input id="page_based" type="checkbox" name="page_based" {{$page_result}}>
                    <label for="checkbox1">
                        Page-Based Results
                    </label>
                </div>

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
                    @if(isset($page_based) && $page_based ==1)
                      <div class="mt-1">
                    @else
                      <div class="mt-1" id="profileData">
                    @endif
                       @if(!empty($bridge))
                         <?php $t=1; if(isset($page_based) && $page_based ==1 ) {$bridgeData = $bridge;}else{$bridgeData = $bridge->toArray();} ?>
                            
                            @foreach($bridgeData as $bridges)
                                <p>
                                    @if($bridges['comefromNote'] ==0)
                                       <img src="{{ asset('images/bridge_icon.png') }}" alt="logo" height="auto" width="20px;" class="img-fluid"/> <a href="{{$bridges['from_element']['url']}}">{{strtoupper($bridges['fromUrl'])}} </a> to <a href="{{strtoupper($bridges['to_element']['url'])}}">{{strtoupper($bridges['toUrl'])}} </a>
                                    @else
                                      <img src="{{ asset('images/note_icon.png') }}" alt="logo" height="auto" width="20px;" class="img-fluid"/> {{$bridges['title']}}
                                    @endif
                                    @if($bridges['privacy']==1)<img src="{{ asset('images/privacy.png') }}" height="auto" width="10px;" alt="logo" class="img-fluid"/>@endif
                                    <br/>@if($bridges['relation_data'])
                                      <span class="table-text-color ml-4">{{$bridges['relation_data']['active_name']}}</span>
                                    @endif
                                    @if($bridges['comefromNote'] ==1)
                                      <span class="ml-4">{{$bridges['desc'] }}</span>
                                    @else
                                      <span>{{$bridges['desc'] }}</span>
                                    @endif
                                </p>
                                <?php $t++ ?>
                            @endforeach
                        @endif
                    </div>
                </div>
              </div>
              <div id="loadMore"><p id="loadData" class="hidden">Load more</p></div>
          @else
          <p class="text-center">No Data Found. </p>
          @endif
          </div>
          @if(isset($page_based ) && $page_based ==1)
            <?php $searchUrl['page_based'] = 1;
              if(($my_result)==1){$searchUrl['my_result'] =1;}
              if(($all_result)==1){$searchUrl['all_result'] =1;}
              if(($search)){$searchUrl['search'] = $search;}
              ?>
            @if($page_based ==1)
              {{ $bridge->appends($searchUrl)->render() }}
            @endif
          @endif
        </div>
    </div>
  </div>
{{-- search data --}}
@endsection
@section('pageScript')
<script src="{{ asset('js/custom/search.js') }}"></script>
@endsection

