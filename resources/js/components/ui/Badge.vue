<script setup lang="ts">
import { computed } from 'vue'
import { badgeVariants, type BadgeVariants } from './badge'
import { cn } from '@/lib/utils'

const props = withDefaults(
  defineProps<{
    variant?: BadgeVariants['variant']
    class?: string
  }>(),
  {
    variant: 'default',
  },
)

const badgeClass = computed(() => {
  return cn(badgeVariants({ variant: props.variant }), props.class)
})
</script>

<template>
  <div :class="badgeClass">
    <slot />
  </div>
</template>
