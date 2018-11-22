@extends('layouts.modal')

@section('form_start')
    {!! Form::model($category,['route' => ['subCategories.update',encrypt($category->id)],'method' => 'PUT', 'id' => 'category_form', 'name' =>'category_form', 'files'=>'true','data-success-callback'=>"categoriesSaveSuccess" ,'data-error-callback'=>"categoriesSaveError", 'class'=>"ajax-form-submit",'data-extra-validation'=>"validateMedia",'files'=>true,'novalidate'=>'novalidate','data-provide'=>"validation"]) !!}

    {!! csrf_field() !!}
@endsection
@section('modal-body')
    <div class="row">
        <div class="col-md-12">
            @include('admin.subcategory.form')
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-12">
        <button class="btn btn-w-md btn-pink mr-2 save"" type="submit"> Save </button>
        <button type="button" class="btn btn-w-md btn-gray" data-dismiss="modal"> Cancel</button>
    </div>
    <script src="{{asset('js/custom/categoryForm.js')}}"></script>
    <script type="text/javascript">
        var csrfToken = '{{ csrf_token() }}';
    </script>
@endsection
@section('form_end') {!! Form::close() !!} @endsection