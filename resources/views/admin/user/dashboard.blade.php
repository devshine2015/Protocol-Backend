@extends('layouts.app')
@section('content')
  
<div class="container">
    {!!Form::open(['route' => ['update-profile'],'method' => 'POST', 'id' => 'user_form', 'name' =>'user_form', 'files'=>'true','data-provide'=>"validation"]) !!}
    <div class="row m-y-2 jumbotron">
            {!! csrf_field() !!}
            <div class="col-lg-2 pull-lg-8 text-xs-center dashboard-photo">
                <div class="fileUpload" width=100px;>
                @if(Auth::check())
                        <form id="Ajaxform">
                            <input type="file" name="avatar" class="hidden" data-message-position="bottom" id="featured_image" accept="image/*" pattern="/^.+\.(jpg|png|jpeg|gif)$/i" data-invalid-message="Please choose valid image"/>
                            <button type="button" class="btn btn-light btn-xs uploadIcon" onclick="$('#featured_image').trigger('click');"><a href="#"><i class="fa fa-edit"></i></a></button>
                        </form>
                    @endif
                        <img src="{{ Auth::user()->avatar }}" id="user-profile-pic" class="m-x-auto img-fluid img-circle" alt="avatar">
                </div>
                <div class="userDetail">
                   <h5 class="text-xs-center user_header"><span class="username userName">{{ Auth::user()->name }}</span><input type="text" class="edit-input userName col-lg-12" id="user_name" name="name" value="" /><span class="edit-icon">
                    @if(Auth::check())
                        <a href="#" id="edit" class="btn edit_name"><i class="fa fa-edit"></i>
                        </a>
                        <div class="clearfix"></div>
                    @endif</span>
                   </h5>
                    <h6 class="text-xs-center">Bridgit <span class="log-detail">#{{ Auth::user()->id }}</span></h6>
                    @if(isset($userPoint))
                    <h6 class="text-xs-center">Rewards Point <span class="log-detail">#{{ $userPoint }}</span></h6>
                    @endif
                    @if(Auth::user()->referral_code)
                    <h6 class="text-xs-center">Referral Link: <span class="log-detail username"><a href="{{ url('/')}}/register/?ref={{ Auth::user()->referral_code }}">{{ url('/')}}/register/?ref={{ Auth::user()->referral_code }}</a></span></h6>
                    @endif
                </div>
                @if(!Auth::check())
                    <div class=" pull-lg-6 text-xs-center">
                        <button class="follow">
                          <span class="msg-follow">Follow</span>
                          <span class="msg-following">Following</span>
                          <span class="msg-unfollow">Unfollow</span>
                        </button>
                    </div>
                @endif
            </div>
            <div class="col-lg-8 push-lg-4">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="" data-target="#profile" data-toggle="tab" class="nav-link active">Bridgework</a>
                    </li>
                    <li class="nav-item notificationData">
                    @if($notification_count>0)
                        <span class="notification_count">{{$notification_count}}</span>
                    @endif
                        <a href="" data-target="#notification" data-toggle="tab" class="nav-link edit-profile"> Notifications&nbsp&nbsp </a>
                    </li>
                </ul>
                <div>
                <div class="tab-content p-b-3 mt-2">
                    <div class="tab-pane active" id="profile">
                        <div class="row">
                            <div class="col-md-12" id="profileData">
                                 @if(count($bridge)>0)
                                    @foreach($bridge as $key=>$bridges)
                                        <p>

                                            @if($bridges->comefromNote == 1)
                                                <img src="{{ asset('images/note_icon.png') }}" alt="logo" height="auto" width="20px;" class="img-fluid"/><a href="{{$bridges['targetData']['url']}}" class="note_title"> {{$bridges->title}}</a>@if($bridges->privacy==1)<img src="{{ asset('images/privacy.png') }}" height="auto" width="10px;" alt="logo" class="img-fluid"/>@endif
                                                 
                                                 @if(isset($bridges->relationData))<span class="table-text-color">{{$bridges->relationData->name}}</span>@endif
                                            @else
                                                <img src="{{ asset('images/bridge_icon.png') }}" alt="logo" height="auto" width="20px;" class="img-fluid"/><a class="allLink" href="{{$bridges->fromElement->url}}">{{strtoupper($bridges->fromUrl)}} </a> to <a href="{{($bridges->toElement->url)}}">{{strtoupper($bridges->toUrl)}} </a>
                                                @if($bridges->privacy==1)<img src="{{ asset('images/privacy.png') }}" height="auto" width="10px;" alt="logo" class="img-fluid"/>@endif @if(isset($bridges->relationData))<span class="table-text-color">{{$bridges->relationData->active_name}}</span>@endif
                                            @endif
                                              
                                        </p>
                                    @endforeach
                                @else
                                    <p>No Data found</p>
                                @endif
                            </div>
                        </div>
                        <div id="loadMore"><p id="loadData" class="hidden">Load more</p></div>
                       
                    </div>
                    <div class="tab-pane" id="notification">
                        <h4 class="mt-2 m-y-2 notify">Notifications</h4>
                        <table class="table table-hover" id="notificationData">
                            <tbody>
                               {{-- <span class="pull-xs-right font-weight-bold">3 hrs ago</span> Here is your a link to the latest summary report from the.. --}}
                                @if(count($notification)>0)
                                    @foreach($notification as $key=>$notificatios)
                                    <tr class="test_tr" data-read = "{{$notificatios['is_read']}}" data-id = "{{$notificatios['id']}}" data-type = "@if(isset($notificatios['title'])) 1 @else 0 @endif">
                                        <td width="50px;" class="pr-0"><span class="notification_time">
                                            @if($notificatios->created_at->isToday())
                                                {{$notificatios->created_at->diffForHumans('', true, false, 1)}}
                                            @else
                                                {{$notificatios->created_at->format('M d')}}
                                            @endif
                                            </span></td>
                                        <td width="350px;" class="pr-0">
                                            
                                            @if($notificatios->comefromNote == 0)
                                                <img src="{{ asset('images/note_icon.png') }}" alt="logo" height="auto" width="20px;" class="img-fluid mr-1"/><a href="{{$notificatios['targetData']['url']}}" class="note_title"> {{$notificatios->title}}</a>
                                            @if(isset($notificatios->relationData))<span class="table-text-color">{{$notificatios->relationData->name}}</span>@endif
                                            @elseif($notificatios->comefrombridge == 0)
                                                <img src="{{ asset('images/bridge_icon.png') }}" alt="logo" height="auto" width="20px;" class="img-fluid mr-1"/><a href="{{$notificatios->fromElement->url}}">{{strtoupper($notificatios->fromUrl)}} </a> to <a href="{{($notificatios->toElement->url)}}">{{strtoupper($notificatios->toUrl)}} </a>
                                             @if(($notificatios->relationData))<span class="table-text-color">{{$notificatios->relationData->active_name}}</span>@endif
                                            @elseif($notificatios->comefrombridge == 2)
                                                <img src="{{ asset('images/element.png') }}" alt="logo" height="auto" width="20px;" class="img-fluid mr-1"/><a href="{{$notificatios->fromElement->url}}">{{strtoupper($notificatios->fromUrl)}} </a> to <a href="{{($notificatios->toElement->url)}}">{{strtoupper($notificatios->toUrl)}} </a>
                                             @if(isset($notificatios->relationData))<span class="table-text-color">{{$notificatios->relationData->active_name}}</span>@endif
                                            @elseif($notificatios->comefromNote == 2)
                                                <img src="{{ asset('images/element.png') }}" alt="logo" height="auto" width="20px;" class="img-fluid mr-1"/><a href="{{$notificatios['targetData']['url']}}" class="note_title"> {{$notificatios->title}}</a>
                                             @if(isset($notificatios->relationData))<span class="table-text-color">{{$notificatios->relationData->name}}</span>@endif
                                            @endif
                                           
                                            <td width="20px;" class="pr-0">
                                                @if($notificatios['user']) @if(Auth::check())<a href="{{url(str_replace(' ','-',$notificatios['user']['name']).'/profile/'.$notificatios['user']['id'])}}">@endif{{ $notificatios['user']['name'] }}</a> @endif
                                            </td>
                                            <td width="100px;" class="pr-0 pl-0">
                                                <span>@if(Auth::check())
                                                    @if(Auth::user()->id != $notificatios['user']['id'])
                                                          <button type="button" class="follow btn-xs search-follow" data-id = "{{$notificatios['user']['id']}}" data-follow = "{{$notificatios['is_follow']}}">
                                                            <span class="msg-follow allLink">Follow</span>
                                                            <span class="msg-following allLink">Following</span>
                                                            <span class="msg-unfollow allLink">Unfollow</span>
                                                          </button></span>
                                                    @endif
                                                @endif
                                            </td>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <p>No Data found</p>
                                @endif
                            </tbody>
                        </table>
                        <div id="loadnotify"><p id="loadNotification" class="hidden">Load more</p></div>
                    </div>
                </div>
            </div>
    </div>
        {!! Form::close() !!}
        {{-- check content element --}}

    @if(count($elementData)>0)
        <div class="container-fluid mt-5">
            <h4 class="ml-3">My Board</h4>
            <div id="carouselExample" class="carousel slide my-board-slider " data-ride="carousel" data-interval="9000">
                <div class="carousel-inner row w-100 mx-auto" role="listbox">
                     @foreach($elementData as $key=>$elements)
                      @if($key == 0)
                        <div class="carousel-item col-md-3  active slider-content">
                      @else
                         <div class="carousel-item col-md-3 slider-content">
                      @endif
                       <div class="panel panel-default">
                        <?php $id = ''; $findId = preg_split('/"/', $elements->start_locator, NULL, PREG_SPLIT_NO_EMPTY); if(isset($findId[1])) $id = '#'.$findId[1]; ?>
                        <a href={{$elements->url}}{{$id}} title="image 1" target="_blank">
                            <div class="panel-thumbnail">
                                <div class="profile-pic">
                                    <img class="img-fluid mx-auto d-block" src="{{Storage::url($elements->image)}}" alt="slide {{$key}}">
                                    <div class="edit"><a href= {{ route('elements.destroy', ['id' => encrypt($elements->id)]) }}   name= "Element from board"  data-error-callback="elementsSaveError" data-success-callback="elementsDeleteSuccess" class="edit_name confirm-delete"><i class="fa fa-trash fa-lg"></i></a></div>
                                 </div>
                            </div>
                        </a>
                        </div>
                    </div>
                     @endforeach
                </div>
                @if(count($elementData)>4)
                <a class="carousel-control-prev control-dir" href="#carouselExample" role="button" data-slide="prev">
                    <span class="fa fa-chevron-left fa-lg text-muted"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next text-faded control-dir" href="#carouselExample" role="button" data-slide="next">
                    <span class="fa fa-chevron-right fa-lg text-muted" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
                @endif
            </div>
        </div>
    @endif

</div>
@endsection
@section('pageScript')
<script>
var csrfToken = '{{ csrf_token() }}';
var error = '{{ $errors->first('email') }}';
var followUserUrl = '{!! route('followUser') !!}';
var updatenotify = '{!! route('updatenotify') !!}';
 var updateUserUrl = '{{route('update-profile')}}';
</script>
<script src="{{ asset('js/custom/dashboard.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
@endsection

