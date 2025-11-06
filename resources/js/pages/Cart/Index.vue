<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Trash2, ShoppingBag } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

interface Product {
    id: number;
    name: string;
    price: number;
    image: string;
}

interface CartItem {
    id: number;
    product: Product;
    quantity: number;
}

interface Props {
    cartItems: CartItem[];
    total: number;
}

const props = defineProps<Props>();

const quantities = ref<Record<number, number>>(
    Object.fromEntries(props.cartItems.map(item => [item.id, item.quantity]))
);

// Validar quantidade (apenas números positivos)
const sanitizeQuantity = (itemId: number, event: Event) => {
    const input = event.target as HTMLInputElement;
    let value = parseInt(input.value) || 1;

    // Garantir que seja um número positivo maior que 0
    if (value < 1) value = 1;
    if (value > 999) value = 999; // Limite máximo razoável

    quantities.value[itemId] = value;
    input.value = value.toString();
};

const updateQuantity = (itemId: number) => {
    // Validar novamente antes de enviar
    const quantity = quantities.value[itemId];
    if (quantity < 1 || quantity > 999 || !Number.isInteger(quantity)) {
        toast.error('Quantidade inválida', {
            description: 'A quantidade deve ser um número entre 1 e 999'
        });
        return;
    }

    router.patch(`/cart/${itemId}`, {
        quantity: quantity
    }, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Quantidade atualizada!');
        }
    });
};

const removeItem = (itemId: number) => {
    const item = props.cartItems.find(i => i.id === itemId);

    router.delete(`/cart/${itemId}`, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Item removido', {
                description: `${item?.product.name} foi removido do carrinho`
            });
        }
    });
};

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('pt-MZ', {
        style: 'currency',
        currency: 'MZN'
    }).format(price);
};
</script>

<template>
    <Head title="Carrinho de Compras" />

    <AppLayout>
        <div class="py-6 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-6 sm:mb-8">
                    <h1 class="text-2xl sm:text-3xl font-bold text-foreground">Carrinho de Compras</h1>
                </div>

                <div v-if="cartItems.length === 0" class="text-center py-12">
                    <ShoppingBag class="mx-auto h-12 w-12 text-muted-foreground" />
                    <h3 class="mt-2 text-sm font-medium text-foreground">Carrinho vazio</h3>
                    <p class="mt-1 text-sm text-muted-foreground">Adicione produtos ao carrinho para continuar</p>
                    <div class="mt-6">
                        <Link href="/products">
                            <Button>Ir às Compras</Button>
                        </Link>
                    </div>
                </div>

                <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                    <div class="lg:col-span-2 space-y-3 sm:space-y-4">
                        <Card v-for="item in cartItems" :key="item.id">
                            <CardContent class="p-3 sm:p-4">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                                    <img
                                        :src="item.product.image"
                                        :alt="item.product.name"
                                        class="w-full sm:w-24 h-32 sm:h-24 object-cover rounded"
                                    />
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base sm:text-lg font-semibold text-foreground truncate">{{ item.product.name }}</h3>
                                        <p class="text-sm sm:text-base text-muted-foreground">{{ formatPrice(item.product.price) }}</p>

                                        <!-- Mobile: Quantidade e Total -->
                                        <div class="flex items-center justify-between mt-2 sm:hidden">
                                            <div class="flex items-center gap-2">
                                                <Input
                                                    v-model.number="quantities[item.id]"
                                                    type="number"
                                                    min="1"
                                                    max="999"
                                                    @input="(e: Event) => sanitizeQuantity(item.id, e)"
                                                    @change="updateQuantity(item.id)"
                                                    class="w-16 h-9"
                                                />
                                            </div>
                                            <p class="text-base font-bold">
                                                {{ formatPrice(item.product.price * item.quantity) }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Desktop: Quantidade -->
                                    <div class="hidden sm:flex items-center space-x-2">
                                        <Input
                                            v-model.number="quantities[item.id]"
                                            type="number"
                                            min="1"
                                            max="999"
                                            @input="(e: Event) => sanitizeQuantity(item.id, e)"
                                            @change="updateQuantity(item.id)"
                                            class="w-20"
                                        />
                                    </div>

                                    <!-- Desktop: Total -->
                                    <div class="hidden sm:block text-right">
                                        <p class="text-lg font-bold">
                                            {{ formatPrice(item.product.price * item.quantity) }}
                                        </p>
                                    </div>

                                    <!-- Botão Remover -->
                                    <Button
                                        variant="destructive"
                                        size="icon"
                                        @click="removeItem(item.id)"
                                        class="self-end sm:self-center"
                                    >
                                        <Trash2 class="w-4 h-4" />
                                    </Button>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    <div>
                        <Card>
                            <CardHeader>
                                <CardTitle>Resumo do Pedido</CardTitle>
                            </CardHeader>
                            <CardContent class="space-y-4">
                                <div class="flex justify-between text-lg">
                                    <span>Subtotal</span>
                                    <span>{{ formatPrice(total) }}</span>
                                </div>
                                <div class="border-t pt-4">
                                    <div class="flex justify-between text-xl font-bold">
                                        <span>Total</span>
                                        <span class="text-green-600">{{ formatPrice(total) }}</span>
                                    </div>
                                </div>
                                <Link href="/checkout" class="block">
                                    <Button class="w-full cursor-pointer" size="lg">
                                        Finalizar Compra
                                    </Button>
                                </Link>
                                <Link href="/products" class="block">
                                    <Button variant="outline" class="w-full cursor-pointer">
                                        Continuar Comprando
                                    </Button>
                                </Link>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
