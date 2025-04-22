<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-bold mb-4">Edit - {{todo.title}}</h2>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Title</label>
          <input 
            v-model="todo.title"
            type="text" 
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Label</label>
          <input 
            v-model="todo.label" 
            type="text" 
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Image</label>
          <input @change="handleFileChange" type="file" class="mt-1 block w-full" />
        </div>
        <div class="flex justify-end">
          <button @click="$emit('close')" class="mr-2 px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
          <button @click="save" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Save</button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';

  const props = defineProps({
    isOpen: Boolean,
    todo: Object, // This will be bound with v-model in the parent
  });

  const emit = defineEmits(['close', 'save']);

  const newImage = ref(null);

  function handleFileChange(event) {
    const file = event.target.files[0];
    if (file) {
      newImage.value = file;
    } else {
      newImage.value = null;
    }
  }

  function save() {
    emit('save', { ...props.todo, newImage: newImage.value });
  }
  </script>
  
  <style scoped>
  </style>