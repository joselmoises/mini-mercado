<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { toast } from 'vue-sonner';

interface Product {
    id: number;
    name: string;
    price: number;
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

const form = useForm({
    payment_method: 'card'
});

const submit = () => {
    form.post('/orders', {
        onSuccess: () => {
            toast.success('Compra realizada!', {
                description: 'A sua compra foi confirmada com sucesso. Receberá um email de confirmação.'
            });
        },
        onError: () => {
            toast.error('Erro ao processar compra', {
                description: 'Não foi possível processar a sua compra. Tente novamente.'
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
    <Head title="Checkout" />

    <AppLayout>
        <div class="py-6 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-6 sm:mb-8">
                    <h1 class="text-2xl sm:text-3xl font-bold text-foreground">Finalizar Compra</h1>
                </div>

                <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                    <div class="lg:col-span-2">
                        <Card>
                            <CardHeader>
                                <CardTitle>Método de Pagamento</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <RadioGroup v-model="form.payment_method">
                                    <div class="space-y-4">
                                        <div class="flex items-center space-x-2 border rounded-lg p-4 cursor-pointer hover:bg-muted/50">
                                            <RadioGroupItem value="card" id="card" />
                                            <Label for="card" class="cursor-pointer flex-1">
                                                <div class="font-semibold">Cartão</div>
                                                <div class="text-sm text-muted-foreground">Pagamento seguro via cartão de crédito ou débito</div>
                                            </Label>
                                        </div>
                                        <div class="flex items-center space-x-2 border rounded-lg p-4 cursor-pointer hover:bg-muted/50">
                                            <RadioGroupItem value="mpesa" id="mpesa" />
                                            <Label for="mpesa" class="cursor-pointer flex-1">
                                                <div class="font-semibold">M-Pesa</div>
                                                <div class="text-sm text-muted-foreground">Pagamento via carteira móvel M-Pesa</div>
                                            </Label>
                                        </div>
                                    </div>
                                </RadioGroup>
                            </CardContent>
                        </Card>
                    </div>

                    <div>
                        <Card>
                            <CardHeader>
                                <CardTitle>Resumo do Pedido</CardTitle>
                            </CardHeader>
                            <CardContent class="space-y-3 sm:space-y-4">
                                <div class="space-y-2">
                                    <div v-for="item in cartItems" :key="item.id" class="flex justify-between text-xs sm:text-sm gap-2">
                                        <span class="truncate">{{ item.product.name }} x{{ item.quantity }}</span>
                                        <span class="whitespace-nowrap">{{ formatPrice(item.product.price * item.quantity) }}</span>
                                    </div>
                                </div>
                                <div class="border-t pt-3 sm:pt-4">
                                    <div class="flex justify-between text-lg sm:text-xl font-bold">
                                        <span>Total</span>
                                        <span class="text-green-600">{{ formatPrice(total) }}</span>
                                    </div>
                                </div>
                                <Button
                                    type="submit"
                                    class="w-full cursor-pointer"
                                    size="lg"
                                    :disabled="form.processing"
                                >
                                    <span class="hidden sm:inline">{{ form.processing ? 'Processando...' : 'Confirmar Pagamento' }}</span>
                                    <span class="inline sm:hidden">{{ form.processing ? 'Processando...' : 'Confirmar' }}</span>
                                </Button>
                            </CardContent>
                        </Card>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
