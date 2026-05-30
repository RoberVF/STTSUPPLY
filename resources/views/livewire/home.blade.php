<div>
    <div x-data="{ showCart: false }" class="min-h-screen bg-neutral-950 text-neutral-100 font-sans relative overflow-x-hidden flex flex-col justify-between">
        
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-[#6d5dfc]/10 rounded-full blur-[120px] pointer-events-none animate-pulse"></div>

        <div>
            <header class="border-b border-neutral-900 bg-neutral-900/50 backdrop-blur sticky top-0 z-40">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
                    <a href="/" class="flex items-center gap-3 cursor-pointer shrink-0">
                        <div class="w-10 h-6 bg-[#6d5dfc] rounded-sm shadow-[0_0_15px_rgba(109,93,252,0.5)]"></div>
                        <span class="text-2xl font-black tracking-tighter text-white uppercase">STT<span class="text-neutral-400 font-light">SUPPLY</span></span>
                    </a>
                    <nav class="hidden md:flex items-center gap-6 text-[11px] font-black tracking-widest uppercase text-neutral-400">
                        <a href="/catalog/laliga" class="hover:text-white pb-1 transition-all">La Liga</a>
                        <a href="/catalog/premier" class="hover:text-white pb-1 transition-all">Premier</a>
                        <a href="/catalog/seriea" class="hover:text-white pb-1 transition-all">Serie A</a>
                        <a href="/catalog/retro" class="text-[#6d5dfc] hover:text-violet-400 transition-all">⚡ Retro Selection</a>
                    </nav>
                    <div @click="showCart = true" class="relative cursor-pointer group flex items-center gap-2 bg-neutral-900 border border-neutral-800 px-4 py-2 rounded-lg hover:border-neutral-700 transition shrink-0">
                        <span class="text-xs font-semibold tracking-wider uppercase group-hover:text-violet-400 transition">Ver Cesta</span>
                        <span class="bg-[#6d5dfc] text-white text-[10px] w-5 h-5 rounded-md flex items-center justify-center font-black shadow-[0_0_10px_rgba(109,93,252,0.4)]">{{ $this->cartCount }}</span>
                    </div>
                </div>
            </header>

            <section class="relative min-h-[60vh] flex flex-col justify-center items-center px-4 text-center border-b border-neutral-900 py-12">
                <div class="inline-flex items-center gap-2 bg-neutral-900 border border-neutral-800 px-4 py-1.5 rounded-full text-[10px] tracking-widest uppercase font-black text-neutral-400 mb-6">
                    <span class="w-2 h-2 bg-[#6d5dfc] rounded-full animate-ping"></span> Colección Exclusiva // Drops Destacados
                </div>
                <h1 class="text-5xl sm:text-7xl md:text-8xl font-black tracking-tighter uppercase leading-none select-none">
                    STTSUPPLY<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-violet-400 to-[#6d5dfc]">REPLICAS</span><br>AAA+
                </h1>
                <p class="mt-6 text-xs md:text-sm text-neutral-400 max-w-md font-light tracking-wide">
                    Camisetas actuales, ropa técnica, rarezas retro y las ediciones más buscadas seleccionadas a mano.
                </p>
            </section>

            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        <div wire:key="home-product-{{ $product->id }}" class="group bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden hover:border-neutral-700 transition-all duration-300 flex flex-col justify-between">
                            <div class="aspect-square w-full bg-neutral-950 relative overflow-hidden flex items-center justify-center p-4">
                                <img src="{{ $product->images[0] ?? '' }}" class="object-contain max-h-full w-full filter brightness-95 group-hover:scale-105 transition-transform duration-500">
                                <span class="absolute top-3 left-3 bg-neutral-950/80 backdrop-blur text-neutral-400 text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded-md border border-neutral-800">{{ $product->category }}</span>
                            </div>
                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div class="mb-4">
                                    <p class="text-xs font-semibold text-[#6d5dfc] uppercase tracking-wider mb-1">{{ $product->team ?? $product->league }}</p>
                                    <h3 class="text-sm font-bold text-white line-clamp-2 tracking-tight group-hover:text-violet-300 transition">{{ $product->title }}</h3>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-neutral-800/60">
                                    <span class="text-lg font-black text-white">{{ number_format($product->selling_price, 2) }}€</span>
                                    <button wire:click="addToCart({{ $product->id }})" class="bg-neutral-950 hover:bg-[#6d5dfc] text-white text-xs font-bold tracking-wider uppercase px-4 py-2.5 rounded-lg border border-neutral-800 hover:border-[#6d5dfc] transition-all duration-300">+ Añadir</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 text-center" style="margin-top:3rem">
                    <a href="/catalog" class="inline-flex items-center gap-3 bg-[#6d5dfc] hover:bg-violet-500 text-white text-xs font-black tracking-widest uppercase rounded-xl shadow-[0_0_25px_rgba(109,93,252,0.4)] transition duration-300">
                        <span class="px-4 py-4">Explorar</span>
                    </a>
                </div>
            </main>
        </div>
        @include('livewire.partials.cart-sidebar')
    </div>
</div>