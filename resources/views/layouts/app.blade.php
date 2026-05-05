@include('layouts.partials.head')

<div class="container-scroller">
    {{-- Navbar --}}
    @include('layouts.partials.navbar')

    <div class="container-fluid page-body-wrapper">
        {{-- Sidebar --}}
        @include('layouts.partials.sidebar')

        <div class="main-panel">
            <div class="content-wrapper">
                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Page content --}}
                @yield('content')
            </div>

            @include('layouts.partials.footer')
        </div>
    </div>
</div>

{{-- Page-specific scripts --}}
@yield('js-page')
</body>
</html>
