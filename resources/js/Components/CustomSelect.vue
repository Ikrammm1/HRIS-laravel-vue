<template>
  <div class="relative">
    <label v-if="label" class="block font-medium mb-1">
      {{ label }}
    </label>

    <!-- Control -->
    <div
      ref="control"
      class="relative flex items-center rounded border bg-white pr-3 h-10 cursor-text"
      :class="open
        ? 'border-blue-500 ring-2 ring-blue-200'
        : 'border-gray-300'"
      @click="open = true"
    >
      <input
        type="text"
        v-model="search"
        @focus="open = true"
        :placeholder="placeholder"
        class="w-full bg-transparent text-sm
                border-0 outline-none ring-0
                focus:outline-none focus:ring-0 focus:shadow-none"
        />

      <!-- Chevron -->
      <svg
        class="w-4 h-4 text-gray-400 transition-transform ml-2"
        :class="{ 'rotate-180': open }"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M19 9l-7 7-7-7"
        />
      </svg>
    </div>

    <!-- Dropdown -->
    <ul
      v-if="open"
      class="absolute z-20 w-full bg-white border border-gray-300 border-t-0 rounded-b shadow max-h-56 overflow-auto"
    >
      <li
        v-for="item in filteredOptions"
        :key="item[valueKey]"
        @click="select(item)"
        class="px-3 py-2 text-sm cursor-pointer hover:bg-gray-100"
      >
        {{ item[labelKey] }}
      </li>

      <li
        v-if="filteredOptions.length === 0"
        class="px-3 py-2 text-sm text-gray-400"
      >
        Data tidak ditemukan
      </li>
    </ul>
  </div>
</template>

<script>
    export default {
  name: "BaseSearchSelect",

  props: {
    modelValue: [String, Number, null],
    options: {
      type: Array,
      required: true
    },
    label: String,
    placeholder: {
      type: String,
      default: "Select"
    },
    labelKey: {
      type: String,
      default: "label"
    },
    valueKey: {
      type: String,
      default: "value"
    }
  },

  emits: ["update:modelValue"],

  data() {
    return {
      open: false,
      search: ""
    }
  },

  computed: {
    filteredOptions() {
      // 🔥 kalau belum ngetik → tampilkan semua
      if (!this.search) return this.options

      return this.options.filter(item =>
        String(item[this.labelKey])
          .toLowerCase()
          .includes(this.search.toLowerCase())
      )
    }
  },

  methods: {
    select(item) {
      this.search = item[this.labelKey]
      this.open = false
      this.$emit("update:modelValue", item[this.valueKey])
    }
  },

  mounted() {
    const selected = this.options.find(
      o => o[this.valueKey] === this.modelValue
    )
    if (selected) this.search = selected[this.labelKey]

    // klik di luar → close
    document.addEventListener("click", this.handleClickOutside)
  },

  beforeUnmount() {
    document.removeEventListener("click", this.handleClickOutside)
  },

  methods: {
    select(item) {
      this.search = item[this.labelKey]
      this.open = false
      this.$emit("update:modelValue", item[this.valueKey])
    },

    handleClickOutside(e) {
      if (!this.$el.contains(e.target)) {
        this.open = false
      }
    }
  }
}

</script>