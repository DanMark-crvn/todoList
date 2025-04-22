<script setup>
import { ref, onMounted } from 'vue';
import axiosClient from '../axios.js';
import axios from 'axios';
import EditModal from '../components/EditModal.vue';
import ReadModal from '../components/ReadModal.vue';

  const todos = ref([]);
  const isModalOpen = ref(false);
  const isReadModalOpen = ref(false); 
  const selectedTodo = ref({ id: null, title: '', label: '', url: '' });

  function viewModal(todo) {
    selectedTodo.value = { ...todo }; // Clone the selected todo
    isReadModalOpen.value = true;
  }

  function openEditModal(todo) {
    selectedTodo.value = { ...todo }; // Clone the selected todo
    isModalOpen.value = true;
  }

  function closeModal() {
    isModalOpen.value = false;
    isReadModalOpen.value = false;
  }

  function saveTodo(updatedTodo) {
    const formData = new FormData();
    formData.append('title', updatedTodo.title);
    formData.append('label', updatedTodo.label);

    if (updatedTodo.newImage) {
      formData.append('image', updatedTodo.newImage); // Add the new image file if provided
    } else {
      formData.append('path', updatedTodo.path); // Add the existing image URL if no new image is uploaded
    }

    axiosClient
      .post(`/api/todo/${updatedTodo.id}?_method=PUT`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      })
      .then((response) => {
        const index = todos.value.findIndex((todo) => todo.id === updatedTodo.id);
        if (index !== -1) {
          todos.value[index] = response.data;
        }
        closeModal();
      });
  }
  
  function deleteImage(id){
    if (!confirm('Are you sure you want to delete this todo?')) {
      return;
    }
    axiosClient.delete(`/api/todo/${id}`)
      .then( response => {
        todos.value = todos.value.filter(todo => todo.id !== id);
      })
  }

  onMounted(() => {
    axiosClient.get('api/todo')
      .then((response) => {
        // console.log(response.data);
        todos.value = response.data;
      })
  })
</script>

<template>
  <header class="bg-gray-700 shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-100">All Task</h1>
    </div>
  </header>


  <main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 justify-items-center items-center">
        <div v-for="todo in todos" :key="todo.id" class="">
          <div class="relative flex w-80 flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md mt-6">
            <!-- FOR IMAGE -->
            <div class="relative mx-4 -mt-6 h-40 overflow-hidden rounded-xl bg-blue-gray-500 bg-clip-border text-white shadow-lg shadow-blue-gray-500/40 bg-gradient-to-r from-blue-500 to-blue-600">
              <img :src="todo.url" alt="Image" class="h-full w-full object-cover" />
            </div>
            <!-- END FOR IMAGE -->
    
            <div class="p-6">
              <h5 class="mb-2 block font-sans text-xl font-semibold leading-snug tracking-normal text-blue-gray-900 antialiased">
                {{ todo.title }}
              </h5>
              <p class="block font-sans text-base font-light leading-relaxed text-inherit antialiased">
                {{ todo.label }}
              </p>
            </div>
            <div class="p-6 pt-0 flex justify-between items-center">
              <button @click="viewModal(todo)" data-ripple-light="true" type="button" class="select-none rounded-lg bg-green-500 py-3 px-6 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
              </button>
              <button @click="openEditModal(todo)" data-ripple-light="true" type="button" class="select-none rounded-lg bg-blue-500 py-3 px-6 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
              </button>
              <button @click="deleteImage(todo.id)" data-ripple-light="true" type="submit" class="select-none rounded-lg bg-red-500 py-3 px-6 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <ReadModal
    :isOpen="isReadModalOpen"
    v-model:todo="selectedTodo"
    @close="closeModal"
  />
  <EditModal
    :isOpen="isModalOpen"
    v-model:todo="selectedTodo"
    @close="closeModal"
    @save="saveTodo"
  />
</template>

<style scoped>

</style>
