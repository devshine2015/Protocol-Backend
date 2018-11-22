{{-- <div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel">@yield('modal-title')</h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div> --}}
<div class="modal-body">
@yield('form_start')
  @yield('modal-body')
@yield('form_end')
</div>
<div class="modal-footer">
  @yield('modal-footer')
</div>