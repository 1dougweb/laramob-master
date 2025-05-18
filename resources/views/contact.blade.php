<x-frontend-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Entre em Contato</h1>
                <p class="text-lg text-gray-700">
                    Estamos à disposição para atender suas necessidades e responder suas dúvidas.
                </p>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="md:flex">
                    <div class="md:w-1/3 bg-blue-700 p-8 text-white">
                        <h2 class="text-2xl font-bold mb-6">Informações de Contato</h2>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">Endereço</h3>
                            <p>Rua Exemplo, 123</p>
                            <p>São Paulo - SP</p>
                            <p>CEP: 01234-567</p>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">Telefone</h3>
                            <p>(11) 99999-9999</p>
                            <p>(11) 3333-3333</p>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">Email</h3>
                            <p>contato@laramob.com.br</p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Horário de Atendimento</h3>
                            <p>Segunda a Sexta: 9h às 18h</p>
                            <p>Sábado: 9h às 13h</p>
                        </div>
                    </div>
                    
                    <div class="md:w-2/3 p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Envie sua Mensagem</h2>
                        
                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 font-medium mb-2">Nome</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="phone" class="block text-gray-700 font-medium mb-2">Telefone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="message" class="block text-gray-700 font-medium mb-2">Mensagem</label>
                                <textarea name="message" id="message" rows="5" required
                                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                                    Enviar Mensagem
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-layout> 