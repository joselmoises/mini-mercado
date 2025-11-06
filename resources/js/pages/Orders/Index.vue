<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';

interface OrderItem {
    id: number;
    quantity: number;
    price: number;
    product_name: string;
    product?: {
        id: number;
        name: string;
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
    orders: Order[];
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
    <Head title="Meus Pedidos" />

    <AppLayout>
        <div class="py-6 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-6 sm:mb-8">
                    <h1 class="text-2xl sm:text-3xl font-bold text-foreground">Meus Pedidos</h1>
                </div>

                <div v-if="orders.length === 0" class="text-center py-12">
                    <h3 class="mt-2 text-sm font-medium text-foreground">Nenhum pedido encontrado</h3>
                    <p class="mt-1 text-sm text-muted-foreground">Faça seu primeiro pedido!</p>
                    <div class="mt-6">
                        <Link href="/products">
                            <Button>Ir às Compras</Button>
                        </Link>
                    </div>
                </div>

                <div v-else class="space-y-3 sm:space-y-4">
                    <Card v-for="order in orders" :key="order.id">
                        <CardHeader class="pb-3 sm:pb-6">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                                <div class="flex-1 min-w-0">
                                    <CardTitle class="text-lg sm:text-xl">Pedido #{{ order.id }}</CardTitle>
                                    <p class="text-xs sm:text-sm text-muted-foreground mt-1">{{ formatDate(order.created_at) }}</p>
                                </div>
                                <Badge :variant="getStatusBadge(order.status)" class="self-start">
                                    {{ formatStatus(order.status) }}
                                </Badge>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3 sm:space-y-4">
                                <div>
                                    <h4 class="font-semibold text-xs sm:text-sm mb-2">Itens:</h4>
                                    <div class="space-y-1">
                                        <div v-for="item in order.items" :key="item.id" class="text-xs sm:text-sm text-muted-foreground">
                                            {{ item.product_name || item.product?.name }} x{{ item.quantity }} - {{ formatPrice(item.price * item.quantity) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 pt-3 sm:pt-4 border-t">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4">
                                        <span class="text-base sm:text-lg font-bold">Total: {{ formatPrice(order.total) }}</span>
                                        <span class="text-xs sm:text-sm text-muted-foreground">{{ formatPaymentMethod(order.payment_method) }}</span>
                                    </div>
                                    <Link :href="`/orders/${order.id}`">
                                        <Button variant="outline" size="sm" class="w-full sm:w-auto">Ver Detalhes</Button>
                                    </Link>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
