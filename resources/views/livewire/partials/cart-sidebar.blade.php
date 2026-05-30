<div x-show="showCart" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog"
    aria-modal="true" x-cloak>
    <div x-show="showCart" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showCart = false"
        class="absolute inset-0 bg-neutral-950/80 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-y-0 right-0 flex max-w-full">
        <div x-show="showCart" x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="w-full sm:max-w-md bg-neutral-900 border-l border-neutral-800 flex flex-col justify-between shadow-2xl h-full">

            <div class="p-6 border-b border-neutral-800 flex items-center justify-between shrink-0">
                <h2 class="text-lg font-black uppercase tracking-tight text-white flex items-center gap-2">
                    Tu Material <span class="text-xs font-normal text-neutral-500">({{ $this->cartCount }}
                        prendas)</span>
                </h2>
                <button @click="showCart = false"
                    class="text-neutral-500 hover:text-white uppercase font-black text-xs tracking-widest transition">Cerrar
                    ×</button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-4">
                @if ($this->cartItems->isEmpty())
                    <div class="text-center py-20 text-neutral-500 text-sm font-light">La cesta está vacía.</div>
                @else
                    @foreach ($this->cartItems as $productId => $item)
                        <div wire:key="sidebar-item-{{ $productId }}"
                            class="flex gap-4 bg-neutral-950 p-3 rounded-xl border border-neutral-800/60 items-center justify-between">
                            <img src="{{ $item['image'] }}"
                                class="w-12 h-12 object-contain bg-neutral-900 rounded-lg p-1">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-white truncate">{{ $item['title'] }}</h4>
                                <p class="text-[10px] text-[#6d5dfc] font-bold uppercase tracking-wider mt-0.5">
                                    {{ $item['team'] }}</p>
                                <span
                                    class="text-xs font-black text-white block mt-1">{{ number_format($item['price'], 2) }}€</span>
                            </div>
                            <div
                                class="flex items-center bg-neutral-900 border border-neutral-800 rounded-lg p-1 gap-2 shrink-0">
                                <button wire:click="updateQuantity({{ $productId }}, -1)"
                                    class="text-neutral-500 hover:text-white font-black text-xs px-1.5">-</button>
                                <span
                                    class="text-xs font-bold text-white min-w-[12px] text-center">{{ $item['quantity'] }}</span>
                                <button wire:click="updateQuantity({{ $productId }}, 1)"
                                    class="text-neutral-500 hover:text-white font-black text-xs px-1.5">+</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            @if (!$this->cartItems->isEmpty())
                <div class="p-4 sm:p-6 bg-neutral-950 border-t border-neutral-800 space-y-3 shrink-0">
                    @if ($this->currentGift)
                        <div
                            class="bg-gradient-to-r {{ $this->currentGift['color'] }} p-2 rounded-lg text-center shadow-[0_0_15px_rgba(109,93,252,0.2)]">
                            <span class="text-[9px] uppercase font-black text-white/80 block">¡Premio
                                desbloqueado!</span>
                            <span
                                class="text-xs font-black uppercase text-white">{{ $this->currentGift['name'] }}</span>
                        </div>
                    @else
                        <div
                            class="bg-neutral-900 p-2 rounded-lg border border-neutral-800 text-center text-neutral-500 text-[10px]">
                            Pide <span class="text-[#6d5dfc] font-bold">{{ 5 - $this->cartCount }}</span> más para una
                            <span class="text-white font-bold">Camiseta Gratis</span>.
                        </div>
                    @endif

                    <div class="space-y-2" >
                        <input type="text" wire:model="customerName" placeholder="Tu Nombre y Apellidos" style="margin-bottom:1rem"
                            class="w-full bg-neutral-900 border border-neutral-800 rounded-lg px-3 py-2 text-xs text-white placeholder-neutral-600 focus:border-[#6d5dfc] outline-none">
                        @error('customerName')
                            <span class="text-red-500 text-[10px]">{{ $message }}</span>
                        @enderror

                        <input type="text" wire:model="customerAddress"
                            placeholder="Dirección completa (Calle, Nº, Isla)" style="margin-bottom:1rem"
                            class="w-full bg-neutral-900 border border-neutral-800 rounded-lg px-3 py-2 text-xs text-white placeholder-neutral-600 focus:border-[#6d5dfc] outline-none">
                        @error('customerAddress')
                            <span class="text-red-500 text-[10px]">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-between items-center py-2 border-t border-neutral-800">
                        <span class="text-[10px] font-bold uppercase text-neutral-400">Total Material</span>
                        <span class="text-lg font-black text-white">{{ number_format($this->cartTotal, 2) }}€</span>
                    </div>

                    <button wire:click="checkout"
                        class="w-full bg-[#6d5dfc] hover:bg-violet-500 text-white text-xs font-black tracking-widest uppercase py-4 rounded-xl shadow-[0_0_20px_rgba(109,93,252,0.3)] transition duration-300 flex items-center justify-center gap-2">
                        <span>Confirmar Pedido</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
