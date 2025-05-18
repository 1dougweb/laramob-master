@props(['disabled' => false])

<input 
    type="file"
    @disabled($disabled)
    {{ $attributes->merge(['class' => 'w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100']) }}
> 