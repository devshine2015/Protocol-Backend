@extends('layouts.app')
@section('content')

<div class="container">
  <div class="row m-y-2 jumbotron">
      {!! csrf_field() !!}
    <div class="col-lg-10 push-lg-4">
        <ul class="nav nav-tabs">
            <li class="nav-item notificationData">
                <a href="" data-target="notification" data-toggle="tab" class="nav-link edit-profile"> Notifications</a>
            </li>
            <li class="nav-item">
                <a href="" data-target="#messages" data-toggle="tab" class="nav-link active">Messaging</a>
            </li>
        </ul>
        <div>
          <div class="tab-content mt-2">
              <div class="tab-pane active" id="messages">
                <div class="row">
                  <div class="col-lg-12 mb-2">
                    <a class="btn btn-sm btn-add-message" data-toggle="modal" data-target="#modal-right"   href="{{ route('messages.create') }}">Add Message</a>
                  </div>
                  <div class="col-lg-12" id="messageData">
                       <table class="table messgeData" id="message-table">
                          <tbody>
                          </tbody>
                      </table>
                  </div>
                </div>
                 
              </div>
              <div class="tab-pane" id="notification">
              <!-- notification data -->
              </div>
          </div>
        </div>
    </div>
  </div>
</div> 
@endsection
@section('pageScript')

<script>
var csrfToken = '{{ csrf_token() }}';
var getMessageListURL = '{!! route('message.data') !!}';
</script>
<script src="{{ asset('js/custom/messageList.js') }}"></script>
@endsection

