@props([
    'name' => 'items',
    'items' => [],
    'defaultItem' => '{}',
    'removeConfirmMessage' => 'Tem certeza que deseja remover este item?'
])

<div {{ $attributes }}
     x-data="{
        items: {{ json_encode($items ?: [json_decode($defaultItem, true) ?: []]) }},
        defaultItem: {{ $defaultItem }},
        addItem() {
            this.items.push(JSON.parse(JSON.stringify(this.defaultItem)));
        },
        removeItem(index) {
            if (this.items.length > 1) {
                if (confirm('{{ $removeConfirmMessage }}')) {
                    this.items.splice(index, 1);
                }
            }
        }
     }"
>
    <div class="space-y-3">
        <template x-for="(item, index) in items" :key="index">
            <div>
                {{ $item }}
            </div>
        </template>
    </div>
    
    {{ $actions }}
</div> 