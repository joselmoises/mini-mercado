<script setup lang="ts">
import { Label as LabelPrimitive, type LabelProps } from 'reka-ui'
import { computed, type HTMLAttributes } from 'vue'
import { labelVariants } from './label'
import { cn } from '@/lib/utils'

const props = defineProps<LabelProps & { class?: HTMLAttributes['class'] }>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props
  return delegated
})
</script>

<template>
  <LabelPrimitive
    v-bind="delegatedProps"
    :class="cn(labelVariants(), props.class)"
  >
    <slot />
  </LabelPrimitive>
</template>
