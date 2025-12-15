<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center">
    <!-- overlay -->
    <div class="absolute inset-0 bg-black/40"></div>

    <!-- dialog -->
    <div class="relative bg-white rounded-xl shadow-xl w-full max-w-sm p-5">
      <h3 class="text-lg font-semibold text-gray-800">
        {{ title }}
      </h3>

      <p class="text-sm text-gray-600 mt-2">
        {{ message }}
      </p>

      <div class="flex justify-end gap-2 mt-5">
        <button
          @click="$emit('cancel')"
          class="px-4 py-2 text-sm rounded-md bg-gray-200 hover:bg-gray-300"
        >
          Cancel
        </button>

        <button
          @click="$emit('confirm')"
          class="px-4 py-2 text-sm rounded-md bg-red-600 hover:bg-red-700 text-white"
        >
          {{ confirm }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    show: Boolean,
    title: String,
    message: String,
    confirm: String
  }
}
</script>
