
@include('Frontend.includes.topnavigation')
@include('Frontend.includes.header')
<div class="row" style="margin:8px;">
    <div class="container-fluid">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            @if(Session('error'))
                <div class="alert alert-danger" role="alert">
                     {{ Session('error') }}
                      </div>
                      @endif

                      @if(Session('success'))
                      <div class="alert alert-success" role="alert">
                            {{ Session('success') }}
                          </div>
                      @endif

                      @if(Session('info'))
                      <div class="alert alert-info" role="alert">
                            {{ Session('info') }}
                          </div>
                      @endif
        </div>
    </div>
</div>

@yield('content')

@include('Frontend.includes.footer')


