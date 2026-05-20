<template>
  <component :is="layout" />
</template>

<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue'
import DesktopLayout from '@/components/dashboard/DesktopLayout.vue'
import TabletLayout from '@/components/dashboard/TabletLayout.vue'
import MobileLayout from '@/components/dashboard/MobileLayout.vue'

const width = ref(window.innerWidth)

function onResize() {
  width.value = window.innerWidth
}

onMounted(() => window.addEventListener('resize', onResize))
onUnmounted(() => window.removeEventListener('resize', onResize))

const layout = computed(() => {
  if (width.value < 640) return MobileLayout
  if (width.value < 1024) return TabletLayout
  return DesktopLayout
})
</script>
