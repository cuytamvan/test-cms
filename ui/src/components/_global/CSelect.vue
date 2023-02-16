<template>
  <div ref="elOption" class="relative">
    <input
      v-model="search"
      :type="type"
      :class="[
        'border w-full rounded-md transition-all focus:border-primary-200 focus:ring ring-primary-300',
        searchable ? 'cursor-text' : 'cursor-pointer',
        size === 'md' && 'py-2 px-4',
        size === 'sm' && 'py-1 px-2 text-sm',
      ]"
      :readonly="!searchable"
      @keydown="eventKeydown"
      @focus="showOption = true"
      @blur="isFilter = false"
      @click="showOption = true"
    />

    <div
      :class="[
        'max-h-52 absolute top-[101%] left-0 right-0 z-20 rounded-md bg-white border overflow-y-auto',
        'transition-all duration-200 custom-scroll',
        !showOption && 'opacity-0 pointer-events-none -translate-y-7',
      ]"
    >
      <span v-if="loading" class="block py-3 px-6 text-center text-xs italic"> Loading... </span>

      <template v-else-if="filtered.length">
        <span
          v-for="(optionItem, iOptionItem) in filtered"
          :key="iOptionItem"
          :class="[
            'block py-3 px-6 cursor-pointer text-xs',
            checkIsActive(optionItem) ? 'bg-primary-50 hover:bg-primary-100 text-primary-500' : 'hover:bg-gray-50',
          ]"
          @click="chooseOption(optionItem)"
        >
          {{ formatLabel(optionItem) }}
        </span>
      </template>

      <span v-else class="block py-3 px-6 text-center text-xs italic"> No Option </span>
    </div>
  </div>
</template>

<script lang="ts">
  import { computed, defineComponent, onMounted, onUnmounted, ref, toRaw, watch } from 'vue';

  export default defineComponent({
    props: {
      modelValue: {
        type: [String, Number, Object],
        default: null,
      },
      type: {
        type: String,
        default: 'text',
      },
      size: {
        type: String,
        default: 'md',
      },
      debounce: {
        type: Number,
        default: 0,
      },
      options: {
        type: Array,
        required: true,
      },
      optionLabel: {
        type: [String, Function],
        default: null,
      },
      optionValue: {
        type: String,
        default: null,
      },
      loading: {
        type: Boolean,
        default: false,
      },
      searchable: {
        type: Boolean,
        default: false,
      },
      defaultOption: {
        type: Object,
        default: null,
      },
    },
    emits: ['update:modelValue', 'search'],
    setup(props, ctx) {
      const value = ref(props.modelValue);
      const search = ref('');
      const showOption = ref(false);
      const isFilter = ref(false);
      const elOption = ref<HTMLElement | null>(null);

      const filtered = computed(() => {
        let options = toRaw(props.options);

        if (props.defaultOption) {
          if (
            props.optionValue &&
            props.options.findIndex((r: any) => r[props.optionValue] === props.defaultOption[props.optionValue]) === -1
          ) {
            options = [{ ...props.defaultOption }, ...options];
          }
        }

        if (!isFilter.value) return options;

        return options.filter((r: any) => {
          const label = formatLabel(r);
          return label.toUpperCase().indexOf(search.value.toUpperCase()) > -1;
        });
      });

      const listener = (e: Event) => {
        if (e.target === elOption.value || e.composedPath().includes(elOption.value as EventTarget)) {
          return;
        }
        showOption.value = false;
        isFilter.value = false;
      };

      const chooseOption = (item: any) => {
        const val = props.optionValue ? item[props.optionValue] : item;

        value.value = val;
        search.value = props.optionLabel ? formatLabel(item) : val;

        ctx.emit('update:modelValue', val);
        ctx.emit('search', '');
        isFilter.value = false;

        showOption.value = false;
      };

      const formatLabel = (item: any) => {
        if (props.optionLabel) {
          if (typeof props.optionLabel === 'function') {
            return props.optionLabel(item);
          } else {
            return item[props.optionLabel];
          }
        }
        return item;
      };

      const checkIsActive = (item: any) => {
        if (props.optionValue && value.value === item[props.optionValue]) return true;
        if (JSON.stringify(value.value) === JSON.stringify(item)) return true;
        return false;
      };

      let timeoutDebounce: number;

      const eventKeydown = () => {
        if (props.debounce === 0) {
          ctx.emit('search', search.value);
          isFilter.value = true;
        } else {
          clearTimeout(timeoutDebounce);
          timeoutDebounce = setTimeout(() => {
            ctx.emit('search', search.value);
            isFilter.value = true;
          }, props.debounce);
        }
      };

      watch(
        () => props.modelValue,
        () => {
          value.value = props.modelValue;

          if (props.optionValue) {
            const data = props.options.find((r: any) => r[props.optionValue] === props.modelValue);
            if (data) search.value = formatLabel(data);
            else search.value = '';
          }
        }
      );

      watch(
        () => props.options,
        () => {
          if (props.optionValue && !isFilter.value) {
            const data = filtered.value.find((r: any) => r[props.optionValue] === props.modelValue);
            if (data) search.value = formatLabel(data);
            else search.value = '';
          }
        }
      );

      onMounted(() => {
        window.addEventListener('click', listener);

        if (props.optionValue) {
          const data = filtered.value.find((r: any) => r[props.optionValue] === props.modelValue);
          if (data) search.value = formatLabel(data);
        }
      });

      onUnmounted(() => {
        window.removeEventListener('click', listener);
      });

      return {
        value,
        search,
        elOption,
        showOption,
        isFilter,
        filtered,

        checkIsActive,
        chooseOption,
        formatLabel,
        eventKeydown,
      };
    },
  });
</script>
