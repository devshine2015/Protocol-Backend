@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
        <div class="jumbotron">
        </div>
    </div>
  </div>
@endsection
@section('pageScript')
<script>
    var csrfToken = '{{ csrf_token() }}';
</script>
<script src="{{ asset('js/custom/search.js') }}"></script>
@endsection

