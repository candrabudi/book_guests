@extends('layouts.app')
@section('title', 'Pengaturan Website')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Pengaturan Website</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('website_settings.storeOrUpdate') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="website_name" class="form-label">Nama Website</label>
                        <input type="text" id="website_name" name="website_name" class="form-control" value="{{ $websiteSettings->website_name ?? '' }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="website_logo" class="form-label">Logo Website</label>
                        <input type="file" id="website_logo" name="website_logo" class="form-control mb-2">
                        @if($websiteSettings && $websiteSettings->website_logo)
                            <img src="{{ asset('storage/' . $websiteSettings->website_logo) }}" alt="Logo" width="100">
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="website_favicon" class="form-label">Favicon Website</label>
                        <input type="file" id="website_favicon" name="website_favicon" class="form-control mb-2">
                        @if($websiteSettings && $websiteSettings->website_favicon)
                            <img src="{{ asset('storage/' . $websiteSettings->website_favicon) }}" alt="Favicon" width="50">
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="website_copyright" class="form-label">Copyright</label>
                        <input type="text" id="website_copyright" name="website_copyright" class="form-control" value="{{ $websiteSettings->website_copyright ?? '' }}" required>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
