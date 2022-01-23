@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success alert-auto-close']) }}>
        {{ $status }}
    </div>
@endif
