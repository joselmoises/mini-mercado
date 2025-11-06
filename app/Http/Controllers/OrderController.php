<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(): Response
    {
        $orders = auth()->user()->orders()
            ->with('items.product')
            ->latest()
            ->get();

        return Inertia::render('Orders/Index', [
            'orders' => $orders
        ]);
    }

    public function show(Order $order): Response
    {
        $this->authorize('view', $order);

        $order->load('items.product');

        return Inertia::render('Orders/Show', [
            'order' => $order
        ]);
    }

    public function checkout(): Response
    {
        $cartItems = auth()->user()->cartItems()
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index')
                ->with('error', 'Carrinho vazio');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return Inertia::render('Checkout/Index', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|in:card,mpesa'
        ]);

        $cartItems = auth()->user()->cartItems()
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->withErrors(['error' => 'Carrinho vazio']);
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        try {
            DB::beginTransaction();

            // Lock products and validate stock atomically
            foreach ($cartItems as $item) {
                $product = DB::table('products')
                    ->where('id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$product || $product->stock < $item->quantity) {
                    DB::rollBack();
                    return back()->withErrors([
                        'error' => "Stock insuficiente para {$item->product->name}"
                    ]);
                }
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $total,
                'status' => 'confirmed',
                'payment_method' => $validated['payment_method']
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_image' => $item->product->image,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            auth()->user()->cartItems()->delete();

            DB::commit();

            $order->load('items.product');
            Mail::to(auth()->user()->email)->send(new OrderConfirmation($order));

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pedido realizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erro ao processar pedido']);
        }
    }
}
