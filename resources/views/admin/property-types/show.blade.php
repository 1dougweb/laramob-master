<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Property Type Details') }}: {{ $propertyType->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.property-types.edit', $propertyType) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('admin.property-types.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Property Type Information') }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('ID') }}</p>
                                <p class="mt-1">{{ $propertyType->id }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Name') }}</p>
                                <p class="mt-1">{{ $propertyType->name }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Slug') }}</p>
                                <p class="mt-1">{{ $propertyType->slug }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Status') }}</p>
                                <p class="mt-1">
                                    <span class="px-2 py-1 rounded text-xs {{ $propertyType->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $propertyType->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="md:col-span-2">
                                <p class="text-sm font-medium text-gray-500">{{ __('Description') }}</p>
                                <p class="mt-1">{{ $propertyType->description ?: 'No description provided.' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Created At') }}</p>
                                <p class="mt-1">{{ $propertyType->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Last Updated') }}</p>
                                <p class="mt-1">{{ $propertyType->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Related Properties') }}</h3>
                        
                        @if($propertyType->properties->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-gray-100 text-gray-700">
                                        <tr>
                                            <th class="py-3 px-4 text-left">ID</th>
                                            <th class="py-3 px-4 text-left">Title</th>
                                            <th class="py-3 px-4 text-left">Status</th>
                                            <th class="py-3 px-4 text-left">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600">
                                        @foreach($propertyType->properties as $property)
                                            <tr class="border-b hover:bg-gray-50">
                                                <td class="py-3 px-4">{{ $property->id }}</td>
                                                <td class="py-3 px-4">{{ $property->title }}</td>
                                                <td class="py-3 px-4">
                                                    <span class="px-2 py-1 rounded text-xs {{ $property->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $property->status }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <a href="{{ route('admin.properties.show', $property) }}" class="text-blue-500 hover:text-blue-700" title="View">
                                                        {{ __('View') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-600">{{ __('No properties associated with this property type.') }}</p>
                        @endif
                    </div>

                    <div class="flex justify-end mt-6">
                        <form action="{{ route('admin.property-types.destroy', $propertyType) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this property type?') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white font-bold py-2 px-4 rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" {{ $propertyType->properties->count() > 0 ? 'disabled' : '' }}>
                                {{ __('Delete Property Type') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 