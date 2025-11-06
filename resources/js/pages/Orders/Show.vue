<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { CheckCircle } from 'lucide-vue-next';

interface OrderItem {
    id: number;
    quantity: number;
    price: number;
    product_name: string;
    product_image: string;
    product?: {
        id: number;
        name: string;
        image: string;
    };
}

interface Order {
    id: number;
    total: number;
    status: string;
    payment_method: string;
    created_at: string;
    items: OrderItem[];
}

interface Props {
    order: Order;
}

const props = defineProps<Props>();

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('pt-MZ', {
        style: 'currency',
        currency: 'MZN'
    }).format(price);
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('pt-MZ', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const getStatusBadge = (status: string) => {
    const badges: Record<string, string> = {
        pending: 'secondary',
        confirmed: 'default',
        processing: 'secondary',
        completed: 'default',
        cancelled: 'destructive'
    };
    return badges[status] || 'secondary';
};

const formatPaymentMethod = (method: string) => {
    const methods: Record<string, string> = {
        card: 'Cartão',
        mpesa: 'M-Pesa'
    };
    return methods[method] || method;
};

const formatStatus = (status: string) => {
    const statuses: Record<string, string> = {
        pending: 'Pendente',
        confirmed: 'Confirmado',
        processing: 'Processando',
        completed: 'Concluído',
        cancelled: 'Cancelado'
    };
    return statuses[status] || status;
};
</script>

<template>
    <Head :title="`Pedido #${order.id}`" />

    <AppLayout>
        <div class="py-6 sm:py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-6 sm:mb-8">
                    <Link href="/orders" class="text-primary hover:underline mb-3 sm:mb-4 inline-block text-sm sm:text-base">
                        ← Voltar aos pedidos
                    </Link>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-foreground">Pedido #{{ order.id }}</h1>
                        <Badge :variant="getStatusBadge(order.status)" class="self-start">
                            {{ formatStatus(order.status) }}
                        </Badge>
                    </div>
                    <p class="text-sm sm:text-base text-muted-foreground mt-2">{{ formatDate(order.created_at) }}</p>
                </div>

                <div class="space-y-4 sm:space-y-6">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl p-5 sm:p-6 shadow-lg">
                        <div class="flex items-center justify-center mb-2 sm:mb-3">
                            <CheckCircle class="w-10 h-10 sm:w-12 sm:h-12 text-white" />
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-white text-center mb-1 sm:mb-2">
                            Pedido Confirmado!
                        </h2>
                        <p class="text-white/90 text-center text-sm sm:text-base">
                            O seu pedido foi confirmado com sucesso e um e-mail de confirmação foi enviado.
                        </p>
                    </div>

                    <Card>
                        <CardHeader>
                            <CardTitle>Itens do Pedido</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3 sm:space-y-4">
                                <div v-for="item in order.items" :key="item.id" class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 pb-3 sm:pb-4 border-b last:border-b-0">
                                    <img
                                        :src="item.product_image || item.product?.image"
                                        :alt="item.product_name || item.product?.name"
                                        class="w-full sm:w-20 h-32 sm:h-20 object-cover rounded"
                                    />
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-foreground text-sm sm:text-base">{{ item.product_name || item.product?.name }}</h3>
                                        <p class="text-xs sm:text-sm text-muted-foreground">Quantidade: {{ item.quantity }}</p>
                                        <p class="text-xs sm:text-sm text-muted-foreground">{{ formatPrice(item.price) }} cada</p>
                                    </div>
                                    <div class="text-left sm:text-right">
                                        <p class="font-bold text-base sm:text-lg">{{ formatPrice(item.price * item.quantity) }}</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Resumo do Pagamento</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-2">
                            <div class="flex justify-between text-sm sm:text-base">
                                <span>Subtotal</span>
                                <span>{{ formatPrice(order.total) }}</span>
                            </div>
                            <div class="flex justify-between pt-3 sm:pt-4 border-t">
                                <span class="font-bold text-base sm:text-lg">Total</span>
                                <span class="font-bold text-base sm:text-lg text-green-600">{{ formatPrice(order.total) }}</span>
                            </div>
                            <div class="pt-3 sm:pt-4 border-t">
                                <p class="text-xs sm:text-sm text-muted-foreground">
                                    <strong>Método de Pagamento:</strong> {{ formatPaymentMethod(order.payment_method) }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>

                    <div class="flex justify-center">
                        <Link href="/products" class="w-full sm:w-auto">
                            <Button size="lg" class="w-full sm:w-auto">Continuar Comprando</Button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
