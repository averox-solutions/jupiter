@if (request()->session()->get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ request()->session()->get('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('Close') }}">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (request()->session()->get('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ request()->session()->get('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('Close') }}">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
