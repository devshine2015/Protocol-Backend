@extends('layouts.app')
@section('content')
  
<div class="container">
    {!!Form::open(['route' => ['update-profile'],'method' => 'POST', 'id' => 'user_form', 'name' =>'user_form', 'files'=>'true','data-provide'=>"validation"]) !!}
    <div class="row m-y-2 jumbotron">
            {!! csrf_field() !!}
            <div class="col-lg-2 pull-lg-8 text-xs-center dashboard-photo">
                <div class="fileUpload" width=100px;>
                        <img src="{{ Auth::user()->avatar }}" id="user-profile-pic" class="m-x-auto img-fluid img-circle" alt="avatar">
                </div>
                <div class="ml-3">
                    <h4 class="text-xs-center">{{ Auth::user()->name }}</h4>
                    <h6 class="text-xs-center">Bridgit no: {{ Auth::user()->id }}</h6>
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
                        <a href="" data-target="#profile" data-toggle="tab" class="nav-link active">Bridgit Data</a>
                    </li>
                    <li class="nav-item">
                        <a href="" data-target="#edit" data-toggle="tab" class="nav-link edit-profile">Notification</a>
                    </li>
                </ul><div class="tab-content p-b-3">
                    <div class="tab-pane active" id="profile">
                        <div class="row">
                            <div class="col-md-12" id="profileData">
                                <h5 class="m-t-2 mt-2">{{ ucfirst(Auth::user()->name) }} 's  Bridgework</h5>
                                 @if(count($bridge)>0)
                                    @foreach($bridge as $key=>$bridges)
                                        <p>
                                            @if($bridges->comefromNote == 1)
                                                <img src="{{ asset('images/note_icon.png') }}" alt="logo" height="auto" width="20px;" class="img-fluid"/> {{$bridges->title}}
                                            @else
                                                <img src="{{ asset('images/bridge_icon.png') }}" alt="logo" height="auto" width="20px;" class="img-fluid"/><a href="{{$bridges->fromElement->url}}">{{strtoupper($bridges->fromUrl)}} </a> to <a href="{{strtoupper($bridges->toElement->url)}}">{{strtoupper($bridges->toUrl)}} </a>
                                            @endif
                                              @if($bridges->privacy==1)<img src="{{ asset('images/privacy.png') }}" height="auto" width="10px;" alt="logo" class="img-fluid"/>@endif @if(isset($bridges->relationData)){{$bridges->relationData->active_name}}@endif
                                        </p>
                                    @endforeach
                                @else
                                    <p>No Data found</p>
                                @endif
                            </div>
                        </div>
                        <div id="loadMore"><p id="loadData" class="hidden">Load more</p></div>
                        <!--/row-->
                    </div>
                    <div class="tab-pane" id="edit">
                        <h4 class="m-y-2 mt-2">Notification</h4>
                        <table class="table table-hover table-striped">
                            <tbody>
                               {{-- <span class="pull-xs-right font-weight-bold">3 hrs ago</span> Here is your a link to the latest summary report from the.. --}}
                                @if(count($notification)>0)
                                    @foreach($notification as $key=>$notificatios)
                                    <tr>
                                        <td>
                                            @if($notificatios->comefromNote == 1)
                                                <img src="{{ asset('images/note_icon.png') }}" alt="logo" height="auto" width="20px;" class="img-fluid"/> {{$notificatios->title}}
                                            @else
                                                <img src="{{ asset('images/bridge_icon.png') }}" alt="logo" height="auto" width="20px;" class="img-fluid"/><a href="{{$notificatios->fromElement->url}}">{{strtoupper($notificatios->fromUrl)}} </a> to <a href="{{strtoupper($notificatios->toElement->url)}}">{{strtoupper($notificatios->toUrl)}} </a>
                                            @endif
                                            @if(isset($notificatios->relationData)){{$notificatios->relationData->active_name}}@endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <p>No Data found</p>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
        {!! Form::close() !!}
</div>
@endsection
@section('pageScript')
<script src="{{ asset('js/custom/dashboard.js') }}"></script>
@endsection

