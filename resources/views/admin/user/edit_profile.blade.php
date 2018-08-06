@extends('layouts.app')
@section('content')
  
<div class="container">
    {!!Form::open(['route' => ['update-profile'],'method' => 'POST', 'id' => 'user_form', 'name' =>'user_form', 'files'=>'true','data-provide'=>"validation"]) !!}
    <div class="row m-y-2 jumbotron">
            {!! csrf_field() !!}
            <div class="col-lg-2 pull-lg-8 text-xs-center dashboard-photo">
                <div class="fileUpload" width=100px;>
                   @if(Auth::user()->id == $userId)
                        <form id="Ajaxform">
                            <input type="file" name="avatar" class="hidden" data-message-position="bottom" id="featured_image" accept="image/*" pattern="/^.+\.(jpg|png|jpeg|gif)$/i" data-invalid-message="Please choose valid image"/>
                            <button type="button" class="btn btn-light btn-xs uploadIcon" onclick="$('#featured_image').trigger('click');"><a href="#"><i class="fa fa-edit"></i></a></button>
                        </form>
                    @endif
                        <img src="{{$userData->avatar}}" id="user-profile-pic" class="m-x-auto img-fluid img-circle" alt="avatar">
                </div>
                <div class="userDetail">
                   <h5 class="text-xs-center user_header"><span class="username userName">{{$userData->name}}</span><input type="text" class="edit-input userName col-lg-12" id="user_name" name="name" value="" /><span class="edit-icon">
                    @if(Auth::user()->id == $userId)
                        <a href="#" id="edit" class="btn edit_name"><i class="fa fa-edit"></i>
                        </a>
                        <div class="clearfix"></div>
                    @endif</span>
                   </h5>
                   
                @if(Auth::user()->id != $userId)
                    <div class=" pull-lg-6 text-xs-center">
                        <button type="button" class="follow btn-default" data-id = "{{$userId}}" data-follow = "{{$is_follow}}">
                          <span class="msg-follow">Follow</span>
                          <span class="msg-following">Following</span>
                          <span class="msg-unfollow">Unfollow</span>
                        </button>
                    </div>
                @endif
                <h6 class="text-xs-center mt-2">Bridgit <span class="log-detail">#{{$userData->id}}</span></h6>
                </div>
            </div>
            <div class="col-lg-8 push-lg-4">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="" data-target="#profile" data-toggle="tab" class="nav-link active">Bridgework</a>
                    </li>
                    {{-- @if(Auth::user()->id == $userId)
                        <li class="nav-item">
                            <a href="" data-target="#edit" data-toggle="tab" class="nav-link edit-profile">Edit</a>
                        </li>
                    @endif --}}
                </ul><div class="tab-content p-b-3">
                    <div class="tab-pane active" id="profile">
                        <div class="row">
                            <div class="col-md-12 mt-2" id="bridgeList">
                                 @if(count($bridge)>0)
                                    @foreach($bridge as $key=>$bridges)
                                        <p>
                                            @if($bridges->comefromNote == 1)
                                                <img src="{{ asset('images/note_icon.png') }}" alt="logo" height="auto" width="20px;" class="img-fluid"/><a href="{{$bridges['targetData']['url']}}" class="note_title"> {{$bridges->title}}</a>
                                                     @if($bridges->privacy==1)<img src="{{ asset('images/privacy.png') }}" height="auto" width="10px;" alt="logo" class="img-fluid"/>@endif
                                                    @if(isset($bridges->relationData))<span class="table-text-color">{{$bridges->relationData->name}}</span>@endif
                                            @else
                                                <img src="{{ asset('images/bridge_icon.png') }}" alt="logo" height="auto" width="20px;" class="img-fluid"/> <a href="{{$bridges->fromElement->url}}">{{strtoupper($bridges->fromUrl)}} </a> to <a href="{{($bridges->toElement->url)}}">{{strtoupper($bridges->toUrl)}} </a>
                                              @if($bridges->privacy==1)<img src="{{ asset('images/privacy.png') }}" height="auto" width="10px;" alt="logo" class="img-fluid"/>@endif @if(isset($bridges->relationData))<span class="table-text-color">{{$bridges->relationData->active_name}}</span>@endif
                                            @endif
                                        </p>
                                    @endforeach
                                @else
                                    <p>No Data found</p>
                                @endif
                            </div>
                            {{-- <div id="showLess">Show less</div> --}}
                        </div>
                        <div id="loadMore"><p id="loadData" class="hidden">Load more</p></div>
                        <!--/row-->
                    </div>
                    <div class="tab-pane" id="edit">
                        <h4 class="m-y-2 mt-2">Edit Profile</h4>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Name</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" name="name" required="required">
                                </div>
                            </div>
                            <div>
                                <input type="submit" class="btn btn-success">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
        {!! Form::close() !!}
</div>
@endsection
@section('pageScript')
<script>
    var csrfToken = '{{ csrf_token() }}';
    var followUserUrl = '{!! route('followUser') !!}';
    var updateUserUrl = '{{route('update-profile')}}';
     
</script>
<script src="{{ asset('js/custom/edit_profile.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
@endsection

