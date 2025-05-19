@props([
    'padding' => true
])

@php
$classes = get_card_classes();
if ($padding) {
    $classes .= ' p-6';
}
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div> 