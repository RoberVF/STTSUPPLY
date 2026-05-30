<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class ProductCatalog extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $selectedLeague = '';

    #[Url(history: true)]
    public $selectedCategory = '';

    // Estructura en memoria: [product_id => ['title' => ..., 'price' => ..., 'quantity' => ...]]
    public $cart = [];

    private array $leagueMapping = [
        'laliga'      => 'La Liga',
        'seriea'      => 'Serie A',
        'premier'     => 'Premier League',
        'ligue1'      => 'Ligue 1',
        'bundesliga'  => 'Bundesliga',
        'retro'       => 'Retro',
    ];

    // Datos del cliente para el envío directo a Canarias
    public $customerName = '';
    public $customerAddress = '';

    /**
     * Valida los datos y genera el enlace de redirección a WhatsApp con el pedido estructurado
     */
    public function checkout()
    {
        $this->validate([
            'customerName'    => 'required|min:3',
            'customerAddress' => 'required|min:10',
        ]);

        // 🛡️ Envoltura en Transacción SQL
        $order = DB::transaction(function () {
            // 1. Creamos la cabecera del pedido
            $newOrder = Order::create([
                'customer_name'    => $this->customerName,
                'customer_address' => $this->customerAddress,
                'total_amount'     => $this->cartTotal,
                'status'           => 'pendiente',
                'gift_name'        => $this->currentGift ? $this->currentGift['name'] : null,
            ]);

            // 2. Registramos cada prenda vinculada a la sesión
            foreach ($this->cartItems() as $productId => $item) {
                $newOrder->items()->create([
                    'product_id' => $productId,
                    'title'      => $item['title'],
                    'team'       => $item['team'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
            }

            return $newOrder;
        });

        // Generación del mensaje (Incluimos el ID de pedido para control en el WhatsApp)
        $message = "🔥 *NUEVO PEDIDO #{$order->id} - STTSUPPLY* 🔥\n\n";
        $message .= "👤 *Cliente:* {$this->customerName}\n";
        $message .= "📍 *Dirección:* {$this->customerAddress}\n";
        $message .= "───────────────────────\n\n";

        foreach ($this->cartItems() as $item) {
            $message .= "👕 *{$item['title']}*\n";
            $message .= "   └ Cantidad: {$item['quantity']}x | Precio: " . number_format($item['price'], 2) . "€\n\n";
        }

        $message .= "───────────────────────\n";
        if ($this->currentGift) {
            $message .= "🎁 *REGALO:* {$this->currentGift['name']}\n";
            $message .= "───────────────────────\n";
        }
        $message .= "💰 *TOTAL MATERIAL:* " . number_format($this->cartTotal, 2) . "€\n\n";
        $message .= "⚠️ _Link de gestión interna: " . route('filament.admin.resources.orders.edit', $order->id) . "_";

        // Limpieza de estados y redirección segura
        session()->forget('cart');
        $this->cart = [];

        return redirect()->to("https://wa.me/" . config('services.whatsapp.phone') . "?text=" . urlencode($message));
    }

    public function mount($league = null)
    {
        if ($league) {
            $slug = strtolower($league);
            if (array_key_exists($slug, $this->leagueMapping)) {
                $this->selectedLeague = $this->leagueMapping[$slug];
            } else {
                return redirect()->route('home');
            }
        }

        $savedCart = session()->get('cart', []);

        foreach ($savedCart as $key => $value) {
            // Si el valor no es un array (es decir, es un int antiguo), destruimos la sesión corrupta
            if (!is_array($value)) {
                session()->forget('cart');
                $savedCart = [];
                break;
            }
        }

        $this->cart = $savedCart;
    }

    /**
     * Añade un producto al carrito guardando sus datos en caché dentro de la sesión
     */
    public function addToCart($productId)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
        } else {
            $product = Product::find($productId);
            if (!$product) return;

            $this->cart[$productId] = [
                'title'    => $product->title,
                'price'    => (float) $product->selling_price,
                'image'    => $product->images[0] ?? null,
                'team'     => $product->team ?? $product->league,
                'quantity' => 1
            ];
        }

        session()->put('cart', $this->cart);
    }

    /**
     * Modifica la cantidad al instante trabajando sobre la memoria de la sesión
     */
    public function updateQuantity($productId, $amount)
    {
        if (!isset($this->cart[$productId])) return;

        $this->cart[$productId]['quantity'] += $amount;

        if ($this->cart[$productId]['quantity'] <= 0) {
            unset($this->cart[$productId]);
        }

        session()->put('cart', $this->cart);
    }

    /**
     * Propiedad Computada: Devuelve los elementos del carrito sin hacer consultas SQL
     */
    #[Computed]
    public function cartItems()
    {
        return collect($this->cart);
    }

    /**
     * Propiedad Computada: Cuenta las prendas rápido desde el array
     */
    #[Computed]
    public function cartCount()
    {
        return $this->cartItems->sum('quantity');
    }

    /**
     * Propiedad Computada: Calcula el precio total desde el array
     */
    #[Computed]
    public function cartTotal()
    {
        return $this->cartItems->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        });
    }

    /**
     * Propiedad Computada: Determina los regalos desbloqueados según el volumen de prendas
     */
    #[Computed]
    public function currentGift()
    {
        $totalItems = $this->cartCount;

        if ($totalItems >= 15) {
            return ['tier' => 15, 'name' => 'Chándal Entero Gratis', 'color' => 'from-emerald-500 to-teal-400'];
        }
        if ($totalItems >= 12) {
            return ['tier' => 12, 'name' => 'Chaqueta Técnica Gratis', 'color' => 'from-cyan-500 to-blue-500'];
        }
        if ($totalItems >= 8) {
            return ['tier' => 8, 'name' => 'Camiseta Retro Gratis', 'color' => 'from-amber-500 to-orange-500'];
        }
        if ($totalItems >= 5) {
            return ['tier' => 5, 'name' => 'Camiseta Normal Gratis', 'color' => 'from-indigo-500 to-purple-500'];
        }

        return null;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingSelectedLeague()
    {
        $this->resetPage();
    }
    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::where('is_active', true);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('team', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->selectedLeague)) {
            $query->where('league', $this->selectedLeague);
        }

        if (!empty($this->selectedCategory)) {
            $query->where('category', $this->selectedCategory);
        }

        return view('livewire.product-catalog', [
            'products'   => $query->latest()->paginate(12),
            'leagues'    => Product::where('is_active', true)->distinct()->pluck('league'),
            'categories' => Product::where('is_active', true)->distinct()->pluck('category'),
        ])->layout('components.layouts.app');
    }
}
