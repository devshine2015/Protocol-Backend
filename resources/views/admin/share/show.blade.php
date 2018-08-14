@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row offset-1">
    <div class="col-md-12">
        <div class="jumbotron">
          @if($shareData->type ==0)
            <div class="col-md-12">
              <div class="admin-message">
              <span>{!! $shareData->adminMessage !!}</span>
              </div>
            </div>
            <div class="col-md-12 wrap mt-4">
              <div class="col-md-3  share-left">
                <img src="{{Storage::url($shareData->fromElement->image)}}" height="auto" width="100%;" class="img-fluid">
              </div>
              <div class="col-md-2 share-center">
                Supports
              </div>
              <div class="col-md-3 share-right">
                <img src="{{Storage::url($shareData->toElement->image)}}" height="auto" width="100%;" class="img-fluid">
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12 mt-4">
              <div class="admin-message">
                {{$shareData->desc}}
              </div>
            </div>
            <div class="container-fluid mt-4">
              <div class="row">
              <span class="mt-2">
                <svg xmlns="http://www.w3.org/2000/svg"  id="Capa_1" x="0px" y="0px" width="32" height="35" viewBox="0 0 486.733 486.733" style="enable-background:new 0 0 486.733 486.733;" xml:space="preserve">
                <g>
                  <path d="M403.88,196.563h-9.484v-44.388c0-82.099-65.151-150.681-146.582-152.145c-2.225-0.04-6.671-0.04-8.895,0   C157.486,1.494,92.336,70.076,92.336,152.175v44.388h-9.485c-14.616,0-26.538,15.082-26.538,33.709v222.632   c0,18.606,11.922,33.829,26.539,33.829h321.028c14.616,0,26.539-15.223,26.539-33.829V230.272   C430.419,211.646,418.497,196.563,403.88,196.563z M273.442,341.362v67.271c0,7.703-6.449,14.222-14.158,14.222H227.45   c-7.71,0-14.159-6.519-14.159-14.222v-67.271c-7.477-7.36-11.83-17.537-11.83-28.795c0-21.334,16.491-39.666,37.459-40.513   c2.222-0.09,6.673-0.09,8.895,0c20.968,0.847,37.459,19.179,37.459,40.513C285.272,323.825,280.919,334.002,273.442,341.362z    M331.886,196.563h-84.072h-8.895h-84.072v-44.388c0-48.905,39.744-89.342,88.519-89.342c48.775,0,88.521,40.437,88.521,89.342   V196.563z" fill="#f075a0"/>
                </g>
              </span>
                <?php $tags = explode(',', $shareData->tags)?>
                @foreach($tags as $tag)
                <div class="col-sm-2 share-tags">{{$tag}}</div>
                @endforeach
              </div>
            </div>
          @elseif($shareData->type == 1)
             <div class="col-md-11">
              <div class="admin-message">
                  <span>{!! $shareData->adminMessage !!}</span>
              </div>
            </div>
            <div class="col-md-12 wrap mt-4">
              <div class="col-md-6  note-title">
                <span>{{$shareData->title}}</span>
              </div>  
              <div class="col-md-4 note-support">
               <span>{{$shareData->relationData->name}}</span>
              </div>
            </div>
            <div class="col-md-12 wrap mt-4">
              <div class="col-md-4  note-content">
                <img src="{{Storage::url($shareData->targetData->image)}}" height="auto" width="100px;" class="img-fluid">
              </div>
              <div class="col-md-6 note-text mt-2">
                {{$shareData->desc}}
              </div>
            </div>
            <div class="container-fluid mt-4">
              <div class="row">
              <span class="mt-2">
                <svg xmlns="http://www.w3.org/2000/svg"  id="Capa_1" x="0px" y="0px" width="32" height="35" viewBox="0 0 486.733 486.733" style="enable-background:new 0 0 486.733 486.733;" xml:space="preserve">
                <g>
                  <path d="M403.88,196.563h-9.484v-44.388c0-82.099-65.151-150.681-146.582-152.145c-2.225-0.04-6.671-0.04-8.895,0   C157.486,1.494,92.336,70.076,92.336,152.175v44.388h-9.485c-14.616,0-26.538,15.082-26.538,33.709v222.632   c0,18.606,11.922,33.829,26.539,33.829h321.028c14.616,0,26.539-15.223,26.539-33.829V230.272   C430.419,211.646,418.497,196.563,403.88,196.563z M273.442,341.362v67.271c0,7.703-6.449,14.222-14.158,14.222H227.45   c-7.71,0-14.159-6.519-14.159-14.222v-67.271c-7.477-7.36-11.83-17.537-11.83-28.795c0-21.334,16.491-39.666,37.459-40.513   c2.222-0.09,6.673-0.09,8.895,0c20.968,0.847,37.459,19.179,37.459,40.513C285.272,323.825,280.919,334.002,273.442,341.362z    M331.886,196.563h-84.072h-8.895h-84.072v-44.388c0-48.905,39.744-89.342,88.519-89.342c48.775,0,88.521,40.437,88.521,89.342   V196.563z" fill="#f075a0"/>
                </g>
              </span>
                 <?php $tags = explode(',', $shareData->tags)?>
                @foreach($tags as $tag)
                <div class="col-sm-2 share-tags">{{$tag}}</div>
                @endforeach
              </div>
            </div>
          @elseif($shareData->type == 2)
             <div class="col-md-11">
              <div class="admin-message">
                  <span>{!! $shareData->adminMessage !!}</span>
              </div>
            </div>
            <div class="col-md-12 wrap mt-4">
              <div class="col-md-6  note-title">
                <span>{{$shareData->name}}</span>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12 wrap mt-4">
              <div class="col-md-4  element-content">
                <img src="{{Storage::url($shareData->image)}}" height="auto" width="100px;" class="img-fluid">
              </div>
              <div class="col-md-6 element-desc mt-2">
                {{$shareData->text}}
              </div>
            </div>
          @endif
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