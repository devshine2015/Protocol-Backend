<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

@include('email.header')
<body class="theme-default">

<section class="page-content">
    <div class="page-content-inner">
        <!-- Mail Templates -->
        <section class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-12">
                        <div class="example-emails margin-bottom-50">
                            <!-- Start Letter -->
                        @yield('content')
                        <!-- End Start Letter -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Mail Templates -->
    </div>
</section>
</body>

</html>
