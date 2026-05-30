<div>
    <div x-data="{ showCart: false }"
        class="min-h-screen bg-neutral-950 text-neutral-100 font-sans selection:bg-violet-500 selection:text-white relative overflow-x-hidden">

        <div
            class="absolute top-0 left-1/4 w-96 h-96 bg-[#6d5dfc]/10 rounded-full blur-[120px] pointer-events-none animate-pulse">
        </div>
        <div
            class="absolute top-1/3 right-1/4 w-[500px] h-[500px] bg-violet-600/5 rounded-full blur-[150px] pointer-events-none">
        </div>

        <header class="border-b border-neutral-900 bg-neutral-900/50 backdrop-blur sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-6 bg-[#6d5dfc] rounded-sm shadow-[0_0_15px_rgba(109,93,252,0.5)]"></div>
                    <span class="text-2xl font-black tracking-tighter text-white uppercase">
                        STT<span class="text-neutral-400 font-light">SUPPLY</span>
                    </span>
                </div>
                <div @click="showCart = true"
                    class="relative cursor-pointer group flex items-center gap-2 bg-neutral-900 border border-neutral-800 px-4 py-2 rounded-lg hover:border-neutral-700 transition">
                    <span class="text-xs font-semibold tracking-wider uppercase group-hover:text-violet-400 transition">
                        Ver Cesta
                    </span>
                    <span
                        class="bg-[#6d5dfc] text-white text-[10px] w-5 h-5 rounded-md flex items-center justify-center font-black shadow-[0_0_10px_rgba(109,93,252,0.4)]">
                        {{ $this->cartCount }}
                    </span>
                </div>
            </div>
        </header>

        @if (blank($search) && blank($selectedLeague) && blank($selectedCategory))
            <section
                class="relative min-h-[75vh] flex flex-col justify-center items-center px-4 text-center border-b border-neutral-900">
                <div
                    class="inline-flex items-center gap-2 bg-neutral-900 border border-neutral-800 px-4 py-1.5 rounded-full text-[10px] tracking-widest uppercase font-black text-neutral-400 mb-6">
                    <span class="w-2 h-2 bg-[#6d5dfc] rounded-full animate-ping"></span>
                    Catálogo Activo // Calidad AAA+
                </div>

                <h1
                    class="text-5xl sm:text-7xl md:text-8xl font-black tracking-tighter uppercase leading-none select-none">
                    CURATED <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-violet-400 to-[#6d5dfc]">
                        FOOTBALL
                    </span> <br>
                    GEAR
                </h1>

                <p class="mt-6 text-xs md:text-sm text-neutral-400 max-w-md font-light tracking-wide">
                    STT SUPPLY. Ropa técnica, ediciones especiales y clásicos históricos. Sin intermediarios, puro boca
                    a boca.
                </p>

                <div
                    class="absolute bottom-8 animate-bounce text-neutral-600 text-[10px] tracking-widest uppercase font-bold">
                    ↓ Desliza para ver el material
                </div>
            </section>

            <div
                class="bg-neutral-900 border-b border-neutral-800 py-3 overflow-hidden select-none flex whitespace-nowrap">
                <div
                    class="animate-marquee flex items-center gap-12 pr-12 text-[10px] font-black tracking-widest uppercase text-neutral-400">
                    <span>CALIDAD AAA+</span> <span class="text-[#6d5dfc]">●</span>
                    <span>ENVÍOS A CANARIAS EN 30 DÍAS</span> <span class="text-[#6d5dfc]">●</span>
                    <span>SÓLO PARA CONOCIDOS</span> <span class="text-[#6d5dfc]">●</span>
                    <span>EDICIONES RETRO DISPONIBLES</span> <span class="text-[#6d5dfc]">●</span>
                </div>
                <div class="animate-marquee flex items-center gap-12 pr-12 text-[10px] font-black tracking-widest uppercase text-neutral-400"
                    aria-hidden="true">
                    <span>CALIDAD AAA+</span> <span class="text-[#6d5dfc]">●</span>
                    <span>ENVÍOS A CANARIAS EN 30 DÍAS</span> <span class="text-[#6d5dfc]">●</span>
                    <span>SÓLO PARA CONOCIDOS</span> <span class="text-[#6d5dfc]">●</span>
                    <span>EDICIONES RETRO DISPONIBLES</span> <span class="text-[#6d5dfc]">●</span>
                </div>
            </div>
        @endif

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" id="catalog">

            <section class="mb-12 bg-neutral-900 p-6 rounded-xl border border-neutral-800 shadow-xl">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="relative">
                        <input wire:model.live.debounce.300ms="search" type="text"
                            placeholder="Buscar camiseta o equipo..."
                            class="w-full bg-neutral-950 border border-neutral-800 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#6d5dfc] focus:ring-1 focus:ring-[#6d5dfc] transition text-white placeholder-neutral-500">
                    </div>
                    <div>
                        <select wire:model.live="selectedLeague"
                            class="w-full bg-neutral-950 border border-neutral-800 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#6d5dfc] text-neutral-300">
                            <option value="">Todas las Ligas</option>
                            @foreach ($leagues as $league)
                                <option value="{{ $league }}">{{ $league }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select wire:model.live="selectedCategory"
                            class="w-full bg-neutral-950 border border-neutral-800 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#6d5dfc] text-neutral-300">
                            <option value="">Cualquier tipo de prenda</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </section>

            <div wire:loading
                class="w-full text-center py-4 text-violet-400 font-medium tracking-widest uppercase animate-pulse text-xs">
                Actualizando catálogo...
            </div>

            <section wire:loading.remove>
                @if ($products->isEmpty())
                    <div class="text-center py-20 border border-dashed border-neutral-800 rounded-xl">
                        <p class="text-neutral-500">No hay prendas que coincidan con los filtros.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($products as $product)
                            <div wire:key="product-card-{{ $product->id }}"
                                class="group bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden hover:border-neutral-700 transition-all duration-300 flex flex-col justify-between">
                                <div
                                    class="aspect-square w-full bg-neutral-950 relative overflow-hidden flex items-center justify-center p-4">
                                    @if ($product->images && isset($product->images[0]))
                                        <img src="{{ $product->images[0] }}" alt="{{ $product->title }}"
                                            class="object-contain max-h-full w-full filter brightness-95 group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="text-neutral-700 text-xs">Sin foto</div>
                                    @endif
                                    <span
                                        class="absolute top-3 left-3 bg-neutral-950/80 backdrop-blur text-neutral-400 text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded-md border border-neutral-800">
                                        {{ $product->category }}
                                    </span>
                                </div>
                                <div class="p-5 flex-1 flex flex-col justify-between">
                                    <div class="mb-4">
                                        <p class="text-xs font-semibold text-[#6d5dfc] uppercase tracking-wider mb-1">
                                            {{ $product->team ?? $product->league }}
                                        </p>
                                        <h3
                                            class="text-sm font-bold text-white line-clamp-2 tracking-tight group-hover:text-violet-300 transition">
                                            {{ $product->title }}
                                        </h3>
                                    </div>
                                    <div class="flex items-center justify-between pt-2 border-t border-neutral-800/60">
                                        <span class="text-lg font-black text-white">
                                            {{ number_format($product->selling_price, 2) }}€
                                        </span>
                                        <button wire:click="addToCart({{ $product->id }})"
                                            class="bg-neutral-950 hover:bg-[#6d5dfc] text-white text-xs font-bold tracking-wider uppercase px-4 py-2.5 rounded-lg border border-neutral-800 hover:border-[#6d5dfc] transition-all duration-300">
                                            + Añadir
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-12 opacity-80 border-t border-neutral-900 pt-6">
                        {{ $products->links() }}
                    </div>
                @endif
            </section>
        </main>

        <div x-show="showCart" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title"
            role="dialog" aria-modal="true" x-cloak>

            <div x-show="showCart" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showCart = false"
                class="absolute inset-0 bg-neutral-950/80 backdrop-blur-sm transition-opacity"></div>

            <div class="absolute inset-y-0 right-0 max-w-full flex pl-10">
                <div x-show="showCart"
                    x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500"
                    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                    class="w-screen max-w-md bg-neutral-900 border-l border-neutral-800 flex flex-col justify-between shadow-2xl">

                    <div class="p-6 border-b border-neutral-800 flex items-center justify-between">
                        <h2 class="text-lg font-black uppercase tracking-tight text-white flex items-center gap-2">
                            Tu Material <span class="text-xs font-normal text-neutral-500">({{ $this->cartCount }}
                                prendas)</span>
                        </h2>
                        <button @click="showCart = false"
                            class="text-neutral-500 hover:text-white uppercase font-bold text-xs tracking-widest transition">
                            Cerrar ×
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-6 space-y-4">
                        @if ($this->cartItems->isEmpty())
                            <div class="text-center py-20 text-neutral-500 text-sm font-light">
                                La cesta está vacía. Añade alguna prenda del catálogo para empezar.
                            </div>
                        @else
                            @foreach ($this->cartItems as $productId => $item)
                                <div wire:key="cart-item-{{ $productId }}"
                                    class="flex gap-4 bg-neutral-950 p-3 rounded-xl border border-neutral-800/60 items-center justify-between">
                                    <img src="{{ $item['image'] }}"
                                        class="w-12 h-12 object-contain bg-neutral-900 rounded-lg p-1">

                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-xs font-bold text-white truncate">{{ $item['title'] }}</h4>
                                        <p
                                            class="text-[10px] text-[#6d5dfc] font-bold uppercase tracking-wider mt-0.5">
                                            {{ $item['team'] }}</p>
                                        <span
                                            class="text-xs font-black text-white block mt-1">{{ number_format($item['price'], 2) }}€</span>
                                    </div>

                                    <div
                                        class="flex items-center bg-neutral-900 border border-neutral-800 rounded-lg p-1 gap-2">
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
                        <div class="p-6 bg-neutral-950 border-t border-neutral-800 space-y-4">

                            @if ($this->currentGift)
                                <div
                                    class="bg-gradient-to-r {{ $this->currentGift['color'] }} p-3 rounded-xl text-center shadow-[0_0_20px_rgba(109,93,252,0.2)] animate-pulse">
                                    <span
                                        class="text-[9px] uppercase font-black tracking-widest text-white/80 block">¡Premio
                                        por volumen desbloqueado!</span>
                                    <span
                                        class="text-sm font-black uppercase text-white tracking-tight">{{ $this->currentGift['name'] }}</span>
                                </div>
                            @else
                                <div
                                    class="bg-neutral-900 p-3 rounded-xl border border-neutral-800/80 text-center text-neutral-500 text-[10px] tracking-wide font-medium">
                                    Pide <span class="text-[#6d5dfc] font-bold">{{ 5 - $this->cartCount }}</span>
                                    prendas más para desbloquear una <span class="text-white font-bold">Camiseta
                                        Gratis</span>.
                                </div>
                            @endif

                            <div class="space-y-3 pt-2">
                                <div>
                                    <input type="text" wire:model="customerName"
                                        placeholder="Tu Nombre y Apellidos"
                                        class="w-full bg-neutral-900 border border-neutral-800 rounded-lg px-3 py-2 text-xs text-white placeholder-neutral-600 focus:outline-none focus:border-[#6d5dfc]">
                                    @error('customerName')
                                        <span
                                            class="text-red-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <input type="text" wire:model="customerAddress"
                                        placeholder="Dirección de entrega completa (Calle, Nº, Localidad, Isla)"
                                        class="w-full bg-neutral-900 border border-neutral-800 rounded-lg px-3 py-2 text-xs text-white placeholder-neutral-600 focus:outline-none focus:border-[#6d5dfc]">
                                    @error('customerAddress')
                                        <span
                                            class="text-red-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-between items-center pt-2 border-t border-neutral-800/60">
                                <span class="text-xs font-bold uppercase tracking-wider text-neutral-400">Total
                                    Material</span>
                                <span
                                    class="text-xl font-black text-white">{{ number_format($this->cartTotal, 2) }}€</span>
                            </div>

                            <button wire:click="checkout"
                                class="w-full bg-[#6d5dfc] hover:bg-violet-500 text-white text-xs font-black tracking-widest uppercase py-4 rounded-xl shadow-[0_0_20px_rgba(109,93,252,0.3)] hover:shadow-[0_0_30px_rgba(109,93,252,0.5)] transition duration-300 flex items-center justify-center gap-2">
                                <span>Confirmar Pedido</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
