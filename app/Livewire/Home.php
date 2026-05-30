<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class Home extends Component
{
    public $cart = [];
    public $customerName = '';
    public $customerAddress = '';

    public function checkout()
    {
        $this->validate([
            'customerName'    => 'required|min:3',
            'customerAddress' => 'required|min:10',
        ]);

        $order = DB::transaction(function () {
            $newOrder = Order::create([
                'customer_name'    => $this->customerName,
                'customer_address' => $this->customerAddress,
                'total_amount'     => $this->cartTotal,
                'status'           => 'pendiente',
                'gift_name'        => $this->currentGift ? $this->currentGift['name'] : null,
            ]);

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

        session()->forget('cart');
        $this->cart = [];

        return redirect()->to("https://wa.me/" . config('services.whatsapp.phone') . "?text=" . urlencode($message));
    }

    public function mount()
    {
        $this->cart = session()->get('cart', []);
    }

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

    public function updateQuantity($productId, $amount)
    {
        if (!isset($this->cart[$productId])) return;
        $this->cart[$productId]['quantity'] += $amount;
        if ($this->cart[$productId]['quantity'] <= 0) {
            unset($this->cart[$productId]);
        }
        session()->put('cart', $this->cart);
    }

    #[Computed]
    public function cartItems()
    {
        return collect($this->cart);
    }

    #[Computed]
    public function cartCount()
    {
        return $this->cartItems->sum('quantity');
    }

    #[Computed]
    public function cartTotal()
    {
        return $this->cartItems->sum(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));
    }

    #[Computed]
    public function currentGift()
    {
        $totalItems = $this->cartCount;
        if ($totalItems >= 15) return ['tier' => 15, 'name' => 'Chándal Entero Gratis', 'color' => 'from-emerald-500 to-teal-400'];
        if ($totalItems >= 12) return ['tier' => 12, 'name' => 'Chaqueta Técnica Gratis', 'color' => 'from-cyan-500 to-blue-500'];
        if ($totalItems >= 8)  return ['tier' => 8,  'name' => 'Camiseta Retro Gratis', 'color' => 'from-amber-500 to-orange-500'];
        if ($totalItems >= 5)  return ['tier' => 5,  'name' => 'Camiseta Normal Gratis', 'color' => 'from-indigo-500 to-purple-500'];
        return null;
    }

    public function render()
    {
        // Traemos solo lo que hayas marcado como destacado en Filament
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->inRandomOrder()
            ->take(16)
            ->get();

        return view('livewire.home', [
            'products' => $featuredProducts
        ])->layout('components.layouts.app');
    }
}
