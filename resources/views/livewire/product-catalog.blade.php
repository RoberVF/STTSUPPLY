<div>
    <div x-data="{ showCart: false }"
        class="min-h-screen bg-neutral-950 text-neutral-100 font-sans relative overflow-x-hidden flex flex-col justify-between">

        <div
            class="absolute top-0 left-1/4 w-96 h-96 bg-[#6d5dfc]/10 rounded-full blur-[120px] pointer-events-none animate-pulse">
        </div>

        <div>
            <header class="border-b border-neutral-900 bg-neutral-900/50 backdrop-blur sticky top-0 z-40">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
                    <a href="/" class="flex items-center gap-3 cursor-pointer shrink-0">
                        <div class="w-10 h-6 bg-[#6d5dfc] rounded-sm shadow-[0_0_15px_rgba(109,93,252,0.5)]"></div>
                        <span class="text-2xl font-black tracking-tighter text-white uppercase">STT<span
                                class="text-neutral-400 font-light">SUPPLY</span></span>
                    </a>

                    <div @click="showCart = true"
                        class="relative cursor-pointer group flex items-center gap-2 bg-neutral-900 border border-neutral-800 px-4 py-2 rounded-lg hover:border-neutral-700 transition shrink-0">
                        <span
                            class="text-xs font-semibold tracking-wider uppercase group-hover:text-violet-400 transition">Ver
                            Cesta</span>
                        <span
                            class="bg-[#6d5dfc] text-white text-[10px] w-5 h-5 rounded-md flex items-center justify-center font-black shadow-[0_0_10px_rgba(109,93,252,0.4)]">{{ $this->cartCount }}</span>
                    </div>
                </div>
            </header>

            <section class="relative min-h-[25vh] flex flex-col justify-center items-center px-4 text-center py-8"
                style="margin-top:3rem">
                <h1 class="text-4xl sm:text-6xl font-black tracking-tighter uppercase select-none">CATÁLOGO <span
                        class="text-[#6d5dfc]">COMPLETO</span></h1>
                <p class="mt-2 text-xs text-neutral-400 max-w-md font-light">Explora toda nuestra mercancía barajada de
                    forma aleatoria por sesión.</p>
            </section>

            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" id="catalog">
                <section wire:key="catalog-filters-bar"
                    class="mb-12 bg-neutral-900 p-6 rounded-xl border border-neutral-800 shadow-xl">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <div class="relative">
                            <input wire:model.live.debounce.300ms="search" type="text"
                                placeholder="Buscar camiseta o equipo..."
                                class="w-full bg-neutral-950 border border-neutral-800 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#6d5dfc] focus:ring-1 focus:ring-[#6d5dfc] transition text-white placeholder-neutral-500">
                        </div>

                        <div class="relative w-full">
                            <select wire:model.live="selectedLeague"
                                class="w-full bg-neutral-950 border border-neutral-800 rounded-lg pl-4 pr-10 py-3 text-sm focus:outline-none focus:border-[#6d5dfc] text-neutral-300 appearance-none cursor-pointer transition">
                                <option value="">Todas las Ligas</option>
                                @foreach ($leagues as $league)
                                    <option value="{{ $league }}">{{ $league }}</option>
                                @endforeach
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-neutral-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <div class="relative w-full">
                            <select wire:model.live="selectedCategory"
                                class="w-full bg-neutral-950 border border-neutral-800 rounded-lg pl-4 pr-10 py-3 text-sm focus:outline-none focus:border-[#6d5dfc] text-neutral-300 appearance-none cursor-pointer transition">
                                <option value="">Cualquier tipo de prenda</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-neutral-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                    </div>
                </section>

                <div wire:loading
                    class="w-full text-center py-4 text-violet-400 font-medium tracking-widest uppercase animate-pulse text-xs">
                    Actualizando catálogo...</div>

                <section wire:loading.remove>
                    @if ($products->isEmpty())
                        <div class="text-center py-20 border border-dashed border-neutral-800 rounded-xl">
                            <p class="text-neutral-500">No hay prendas que coincidan con los filtros.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach ($products as $product)
                                <div wire:key="catalog-product-{{ $product->id }}"
                                    class="group bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden hover:border-neutral-700 transition-all duration-300 flex flex-col justify-between">
                                    <div
                                        class="aspect-square w-full bg-neutral-950 relative overflow-hidden flex items-center justify-center p-4">
                                        <img src="{{ $product->images[0] ?? '' }}"
                                            class="object-contain max-h-full w-full filter brightness-95 group-hover:scale-105 transition-transform duration-500">
                                        <span
                                            class="absolute top-3 left-3 bg-neutral-950/80 backdrop-blur text-neutral-400 text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded-md border border-neutral-800">{{ $product->category }}</span>
                                    </div>
                                    <div class="p-5 flex-1 flex flex-col justify-between">
                                        <div class="mb-4">
                                            <p
                                                class="text-xs font-semibold text-[#6d5dfc] uppercase tracking-wider mb-1">
                                                {{ $product->team ?? $product->league }}</p>
                                            <h3
                                                class="text-sm font-bold text-white line-clamp-2 tracking-tight group-hover:text-violet-300 transition">
                                                {{ $product->title }}</h3>
                                        </div>
                                        <div
                                            class="flex items-center justify-between pt-2 border-t border-neutral-800/60">
                                            <span
                                                class="text-lg font-black text-white">{{ number_format($product->selling_price, 2) }}€</span>
                                            <button wire:click="addToCart({{ $product->id }})"
                                                class="bg-neutral-950 hover:bg-[#6d5dfc] text-white text-xs font-bold tracking-wider uppercase px-4 py-2.5 rounded-lg border border-neutral-800 hover:border-[#6d5dfc] transition-all duration-300">+
                                                Añadir</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-12 opacity-80 border-t border-neutral-900 pt-6">{{ $products->links() }}</div>
                    @endif
                </section>
            </main>
        </div>

        <footer class="mt-20 border-t border-neutral-900 bg-neutral-950/60 backdrop-blur py-12 shrink-0">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8 text-[11px] text-neutral-500">
                <div class="space-y-3">
                    <span class="text-md font-black tracking-tighter text-white uppercase block">STT<span
                            class="text-neutral-500 font-light">SUPPLY</span></span>
                    <p class="font-light leading-relaxed max-w-xs">Distribución independiente de ropa de fútbol técnica
                        y ediciones históricas. Sólo para conocidos.</p>
                </div>
                <div class="space-y-2">
                    <h5 class="text-[10px] font-black tracking-widest uppercase text-neutral-400">Logística Local</h5>
                    <p class="text-neutral-300">📦 Envío directoizado a toda Canarias. Plazo: 25-30 días sin sorpresas
                        de aduanas.</p>
                </div>
                <div class="space-y-2">
                    <h5 class="text-[10px] font-black tracking-widest uppercase text-neutral-400">Soporte Directo</h5>
                    <p>Pedidos confirmados en vivo por WhatsApp y procesados de inmediato en nuestro panel.</p>
                </div>
            </div>
        </footer>

        @include('livewire.partials.cart-sidebar')
    </div>
</div>
