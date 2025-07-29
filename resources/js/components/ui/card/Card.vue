<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <div data-slot="card" :class="cn(
    'bg-white text-gray-950 flex flex-col gap-6 rounded-xl py-6 shadow-md dark:bg-[#1d1d1f] dark:text-gray-50 hover:shadow-lg transition duration-200 hover:cursor-pointer',
    props.class,
  )
    ">
    <slot />
  </div>
</template>
