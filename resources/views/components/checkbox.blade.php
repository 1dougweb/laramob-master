@props(['disabled' => false, 'checked' => false])

<input 
    type="checkbox"
    @checked($checked)
    @disabled($disabled)
    {{ $attributes->merge(['class' => 'rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 focus:border-indigo-500']) }}
> 