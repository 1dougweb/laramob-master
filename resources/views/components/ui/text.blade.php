@props([
    'type' => 'body',
    'size' => 'md',
    'as' => null
])

@php
$classes = get_text_classes($type, $size);

$tag = match($type) {
    'heading' => 'h2',
    'body' => 'p',
    'link' => 'a',
    default => 'span'
};

if ($as) {
    $tag = $as;
}
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $tag }}> 