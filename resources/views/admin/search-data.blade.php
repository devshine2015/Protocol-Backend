@extends('layouts.app')
@section('content')
  
<div class="container"> 
  <div class="row">
    <div class="col-lg-12">
        <div class="jumbotron">
            <h2>Find your bridge detail here</h2>
            <p><small class="text-muted">Click Search input</small></p>
            <table id="example" class="table table-striped" style="width:100%">
              <thead>
                  <tr>
                      <th>Title</th>
                      <th>Relationship Type</th>
                      <th>Creator</th>
                      <th>Description</th>
                      <th>Tags</th>
                      <th>Creation date</th>
                  </tr>
              </thead>
              <tbody>
               @if(!empty($bridge))
                     <?php $t=1; ?>
                        @foreach($bridge as $bridges)
                            <tr>
                                <td>@if($bridges->fromElement) <a href="{{ $bridges->fromElement->url }}">{{ $bridges->fromElement->url }}</a> @endif to @if($bridges->toElement) {{ $bridges->toElement->url }} @endif </td>
                                <td>@if($bridges->relationData->type==1) Active @else Inactive @endif</td>
                                <td> @if($bridges->user) {{ $bridges->user->name }} @endif</td>
                                <td>{{$bridges->desc }}</td>
                                <td>{{ $bridges->tags }}</td>
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

