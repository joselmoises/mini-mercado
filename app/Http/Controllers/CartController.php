<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(): Response
    {
        $cartItems = auth()->user()->cartItems()
            ->with('product')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return Inertia::render('Cart/Index', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    public function store(StoreCartItemRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $product = Product::findOrFail($validated['product_id']);

        if ($product->stock < ($validated['quantity'] ?? 1)) {
            return back()->withErrors(['error' => 'Stock insuficiente']);
        }

        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + ($validated['quantity'] ?? 1);

            if ($product->stock < $newQuantity) {
                return back()->withErrors(['error' => 'Stock insuficiente']);
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'] ?? 1
            ]);
        }

        return back()->with('success', 'Produto adicionado ao carrinho');
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorize('update', $cartItem);

        $validated = $request->validated();

        if ($cartItem->product->stock < $validated['quantity']) {
            return back()->withErrors(['error' => 'Stock insuficiente']);
        }

        $cartItem->update($validated);

        return back()->with('success', 'Carrinho atualizado');
    }

    public function destroy(CartItem $cartItem): RedirectResponse
    {
        $this->authorize('delete', $cartItem);

        $cartItem->delete();

        return back()->with('success', 'Produto removido do carrinho');
    }
}
