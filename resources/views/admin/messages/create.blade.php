@extends('layouts.modal')
@section('form_start')
    {!!Form::open(['route' => ['messages.store'],'method' => 'POST', 'id' => 'message_form', 'name' =>'message_form', 'files'=>'true','class'=>"ajax-form-submit",'data-extra-validation'=>"validateMedia",'files'=>true,'novalidate'=>'novalidate','data-provide'=>"validation"]) !!}

    {!! csrf_field() !!}
@endsection
@section('modal-body')
    <div class="row">
        <div class="col-md-12">
            @include('admin.messages.form')
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-12">
        <button type="submit" class="btn btn-w-md btn-pink mr-2 save"> Save </button>
        <button type="button" class="btn btn-w-md btn-gray" data-dismiss="modal">Cancel</button>
    </div>
    
    <script src="{{asset('js/custom/messagesForm.js')}}"></script>
    <script type="text/javascript">
        var csrfToken = '{{ csrf_token() }}';
        var checkDate = '{!! route('checkDate') !!}';
    </script>
@endsection
@section('form_end') {!! Form::close() !!} @endsection