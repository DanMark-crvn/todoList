<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <div class="flex flex-col h-full space-y-8">
          <h2 class="text-xl font-bold mb-4 text-center">{{todo.title}}</h2>
        
          <!-- FOR IMAGE -->
          <div class="">
            <div class="relative mx-4 -mt-6 h-40 overflow-hidden rounded-xl bg-blue-gray-500 bg-clip-border text-white shadow-lg shadow-blue-gray-500/40 bg-gradient-to-r from-blue-500 to-blue-600">
              <img :src="todo.url" alt="Image" class="h-full w-full object-cover" />
            </div>
          </div>
          <!-- END FOR IMAGE -->

          <div class="mb-6">
            <p class="block font-sans text-base font-light leading-relaxed text-inherit antialiased">
              {{ todo.label }}
            </p>
          </div>
        </div>

        <div class="flex justify-end">
          <button @click="$emit('close')" class="mr-2 px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';

  const props = defineProps({
    isOpen: Boolean,
    todo: Object, // This will be bound with v-model in the MyTodo.vue component
  });

  const emits = defineEmits(['close']);
  
  </script>
  
  <style scoped>
  </style>