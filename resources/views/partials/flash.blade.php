@if (session('success'))
    <div class="flash-message flash-success" role="status"><span>✓</span>{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="flash-message flash-error" role="alert"><span>!</span>{{ session('error') }}</div>
@endif
