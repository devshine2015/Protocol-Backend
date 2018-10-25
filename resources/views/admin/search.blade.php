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
                    <!--<input id="all_result" type="checkbox" name="all_result" {{$all_bridgit}}>
                    <label for="checkbox1">
                        All Bridgit
                    </label>-->
                    <input id="page_based" type="checkbox" name="page_based" {{$page_result}}>
                    <label for="checkbox1">
                        Page-Based Results
                    </label>
                </div>

            </div>
            @endif
            </div>
            {!! Form::close() !!}
            <div class="row mt-2">
              @if(count($bridge)>0)
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
                            <p class="mb-0">
                                  @if($bridges['comefromNote'] ==0)
                                     <img src="{{ asset('images/bridge_icon.png') }}" alt="logo" height="auto" width="30px;" class="img-fluid"/> <a href="{{$bridges['from_element']['url']}}">{{strtoupper($bridges['fromUrl'])}} </a> to <a href="{{($bridges['to_element']['url'])}}">{{strtoupper($bridges['toUrl'])}} </a>
                                        @if($bridges['relation_data'])
                                          <span class= "table-text-color mr-0">{{$bridges['relation_data']['active_name']}}</span>
                                        @endif
                                  @else
                                    <img src="{{ asset('images/note_icon.png') }}" alt="logo" height="auto" width="30px;" class="img-fluid"/> <a href="{{$bridges['target_data']['url']}}" class="note_title">{{$bridges['title']}}</a>
                                    @if($bridges['relation_data'])
                                          <span class= "table-text-color mr-0">{{$bridges['relation_data']['name']}}</span>
                                        @endif
                                  @endif<span class="desc">
                                  @if($bridges['user']) @if(Auth::check())<a href="{{url(str_replace(' ','-',$bridges['user']['name']).'/profile/'.$bridges['user']['id'])}}">@endif{{ $bridges['user']['name'] }}</a> @endif
                                  @if(Auth::check())
                                    @if(Auth::user()->id != $bridges['user']['id'])
                                          <button type="button" class="follow btn-xs search-follow" data-id = "{{$bridges['user']['id']}}" data-follow = "{{$bridges['is_follow']}}">
                                            <span class="msg-follow allLink">Follow</span>
                                            <span class="msg-following allLink">Following</span>
                                            <span class="msg-unfollow allLink">Unfollow</span>
                                          </button></span>
                                    @endif
                                  @endif
                                  @if($bridges['privacy']==1)<img src="{{ asset('images/privacy.png') }}" height="auto" width="10px;" alt="logo" class="img-fluid"/>@endif<br/>
                                  <span class="ml-4">
                                      @foreach($bridges['tags'] as $key=>$tags)
                                        @if(count($bridges['tags']) > ($key+1))
                                          <span class="table-text-color">{{ $tags }}
                                          </span>
                                        @else
                                          <span class="table-text-color">{{ $tags }}
                                          </span>
                                        @endif
                                    @endforeach
                                  </span></br>
                                    <span class="ml-4 desc searchdesc">{{$bridges['desc'] }}</span>
                                  <br/>
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
              {{ $bridge->appends($searchUrl)->render("pagination::default") }}
            @endif
          @endif
        </div>
    </div>
  </div>
{{-- search data --}}
@endsection
@section('pageScript')
<script>
    var token = '{{ $token }}';
    var csrfToken = '{{ csrf_token() }}';
    var error = '{{ $errors->first('email') }}';
    var followUserUrl = '{!! route('followUser') !!}';
    var checkLogin = '{!! route('checkLogin') !!}';
    var userLogout = '{!! route('logoutWeb') !!}';
    var bridgitToken = localStorage.getItem('bridgit-token');
    // localStorage.setItem('bridgit-token-web', token);
    var authCheck = '{{Auth::check()}}';
    //test postmessage event
    // window.postMessage({
    // "type": "BRIDGIT-WEB",
    // "token": token}, '*');
    //postmessage event

</script>
<script src="{{ asset('js/custom/search.js') }}"></script>
@endsection

