<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { ShoppingCart, Sparkles, Search, X } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { toast } from 'vue-sonner';

interface Product {
    id: number;
    name: string;
    description: string;
    price: number;
    image: string;
    stock: number;
    is_available: boolean;
}

interface Props {
    products: Product[];
}

const props = defineProps<Props>();

const searchQuery = ref('');

// Validar entrada de pesquisa (apenas letras, números e espaços)
const sanitizeSearchInput = (event: Event) => {
    const input = event.target as HTMLInputElement;
    const sanitized = input.value.replace(/[^a-zA-ZÀ-ÿ0-9\s]/g, '');
    searchQuery.value = sanitized;
    input.value = sanitized;
};

// Filtrar produtos baseado na pesquisa
const filteredProducts = computed(() => {
    if (!searchQuery.value) {
        return props.products;
    }

    const query = searchQuery.value.toLowerCase();
    return props.products.filter(product =>
        product.name.toLowerCase().includes(query) ||
        product.description.toLowerCase().includes(query)
    );
});

const clearSearch = () => {
    searchQuery.value = '';
};

const addToCart = (productId: number) => {
    const product = props.products.find(p => p.id === productId);

    router.post('/cart', {
        product_id: productId,
        quantity: 1
    }, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Produto adicionado!', {
                description: `${product?.name} foi adicionado ao carrinho`
            });
        },
        onError: () => {
            toast.error('Erro ao adicionar', {
                description: 'Não foi possível adicionar o produto ao carrinho'
            });
        }
    });
};

const formatPrice = (price: number | string) => {
    return Number(price).toFixed(2);
};

// Define cores alternadas e badges
const getCardStyle = (index: number) => {
    const styles = [
        { bg: 'from-blue-400 to-blue-500', badge: index % 7 === 0 ? 'NOVO' : null },
        { bg: 'from-cyan-400 to-cyan-500', badge: null },
        { bg: 'from-amber-400 to-amber-500', badge: index % 5 === 0 ? 'OFERTA' : null },
        { bg: 'from-purple-400 to-purple-500', badge: null },
        { bg: 'from-pink-400 to-pink-500', badge: index % 6 === 0 ? 'DESTAQUE' : null },
        { bg: 'from-green-400 to-green-500', badge: null },
        { bg: 'from-orange-400 to-orange-500', badge: index % 4 === 0 ? 'POPULAR' : null },
        { bg: 'from-teal-400 to-teal-500', badge: null },
    ];
    return styles[index % styles.length];
};
</script>

<template>
    <Head title="Catálogo de Frutas" />

    <AppLayout>
        <div class="py-6 sm:py-12 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6 sm:mb-8 text-center">
                    <div class="inline-flex items-center gap-2 mb-3 sm:mb-4 px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-full shadow-lg">
                        <ShoppingCart class="w-5 h-5 sm:w-6 sm:h-6" />
                        <h1 class="text-xl sm:text-2xl font-bold">MINI MERCADO</h1>
                    </div>
                    <p class="text-base sm:text-xl font-semibold text-muted-foreground px-4">Frutas Frescas • Qualidade Premium</p>
                </div>

                <!-- Search Bar -->
                <div class="mb-6 sm:mb-8 max-w-2xl mx-auto px-4 sm:px-0">
                    <div class="relative">
                        <Search class="absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4 sm:w-5 sm:h-5" />
                        <Input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Pesquisar frutas..."
                            @input="sanitizeSearchInput"
                            class="pl-10 sm:pl-12 pr-10 sm:pr-12 py-4 sm:py-6 text-sm sm:text-base rounded-xl sm:rounded-2xl border-2 border-gray-200 focus:border-blue-400 shadow-md w-full"
                        />
                        <button
                            v-if="searchQuery"
                            @click="clearSearch"
                            class="absolute right-3 sm:right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <X class="w-4 h-4 sm:w-5 sm:h-5" />
                        </button>
                    </div>
                    <p v-if="searchQuery" class="mt-2 sm:mt-3 text-xs sm:text-sm text-center text-muted-foreground">
                        <span class="font-semibold">{{ filteredProducts.length }}</span>
                        {{ filteredProducts.length === 1 ? 'produto encontrado' : 'produtos encontrados' }}
                    </p>
                </div>

                <!-- No Results Message -->
                <div v-if="filteredProducts.length === 0" class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-muted rounded-full mb-4">
                        <Search class="w-10 h-10 text-muted-foreground" />
                    </div>
                    <h3 class="text-xl font-semibold text-foreground mb-2">Nenhum produto encontrado</h3>
                    <p class="text-muted-foreground mb-6">Tente pesquisar com outros termos</p>
                    <Button @click="clearSearch" variant="outline">
                        Limpar Pesquisa
                    </Button>
                </div>

                <!-- Products Grid -->
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 px-4 sm:px-0">
                    <div
                        v-for="(product, index) in filteredProducts"
                        :key="product.id"
                        class="relative bg-card rounded-2xl sm:rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden group"
                    >
                        <!-- Badge -->
                        <div v-if="getCardStyle(index).badge" class="absolute top-3 right-3 sm:top-4 sm:right-4 z-10">
                            <Badge class="bg-gradient-to-r from-pink-500 to-red-500 text-white px-2 py-0.5 sm:px-3 sm:py-1 text-[10px] sm:text-xs font-bold shadow-lg rotate-3 hover:rotate-0 transition-transform">
                                <Sparkles class="w-2.5 h-2.5 sm:w-3 sm:h-3 mr-0.5 sm:mr-1 inline" />
                                {{ getCardStyle(index).badge }}
                            </Badge>
                        </div>

                        <!-- Colored Header Background -->
                        <div :class="`bg-gradient-to-br ${getCardStyle(index).bg} p-4 sm:p-8 relative`">
                            <div class="absolute inset-0 opacity-10" style="background: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M0 0h20v20H0z\' fill=\'none\'/%3E%3Cpath d=\'M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0z\' fill=\'%23fff\'/%3E%3C/svg%3E')"></div>

                            <!-- Product Image -->
                            <img
                                :src="product.image"
                                :alt="product.name"
                                class="w-full h-40 sm:h-56 object-cover rounded-xl sm:rounded-2xl shadow-xl transform group-hover:scale-105 transition-transform duration-300"
                            />
                        </div>

                        <!-- Product Info -->
                        <div class="p-4 sm:p-6">
                            <h3 class="text-lg sm:text-xl font-bold text-foreground mb-1 sm:mb-2">{{ product.name }}</h3>
                            <p class="text-xs sm:text-sm text-muted-foreground mb-3 sm:mb-4 line-clamp-2">{{ product.description }}</p>

                            <!-- Price and Stock -->
                            <div class="flex items-end justify-between mb-3 sm:mb-4">
                                <div>
                                    <p class="text-[10px] sm:text-xs text-muted-foreground mb-0.5 sm:mb-1">Preço</p>
                                    <p class="text-2xl sm:text-3xl font-bold text-foreground">
                                        {{ formatPrice(product.price) }}
                                        <span class="text-sm sm:text-lg text-muted-foreground">MT</span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] sm:text-xs text-muted-foreground">Stock</p>
                                    <p class="text-xs sm:text-sm font-semibold" :class="product.stock > 20 ? 'text-green-600' : 'text-orange-600'">
                                        {{ product.stock }} unid.
                                    </p>
                                </div>
                            </div>

                            <!-- Add to Cart Button -->
                            <Button
                                @click="addToCart(product.id)"
                                :disabled="product.stock === 0"
                                class="w-full cursor-pointer py-4 sm:py-6 text-sm sm:text-base font-semibold rounded-lg sm:rounded-xl shadow-md hover:shadow-xl transition-all"
                                :class="product.stock === 0 ? 'bg-gray-300' : 'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700'"
                            >
                                <ShoppingCart class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" />
                                <span class="hidden sm:inline">{{ product.stock === 0 ? 'Esgotado' : 'Adicionar ao Carrinho' }}</span>
                                <span class="inline sm:hidden">{{ product.stock === 0 ? 'Esgotado' : 'Adicionar' }}</span>
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
