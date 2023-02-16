<template>
  <div :class="[' py-2 rounded-lg bg-white border border-gray-100', !withoutShadow && 'shadow-lg shadow-gray-200']">
    <div v-if="title || $slots.header" class="px-4 pb-4">
      <slot v-if="$slots.header" name="header" />

      <h1 v-else-if="title" class="text-lg font-medium text-gray-700">
        {{ title }}
      </h1>
    </div>

    <table class="w-full border-collapse text-sm relative">
      <div
        v-show="loading"
        class="absolute top-0 left-0 right-0 bottom-0 bg-gray-900 bg-opacity-5 text-primary-500 flex items-center justify-center"
      >
        <c-icon name="abstract-27" class="animate-spin" />
      </div>

      <thead class="border-b">
        <tr>
          <th v-if="select" class="py-3 px-5 font-normal bg-white text-primary-500 text-left">#</th>
          <th
            v-for="column in columns"
            :key="`header-${column.name}`"
            class="py-3 px-5 font-normal bg-white text-primary-500 text-left"
          >
            {{ column.label }}
          </th>
        </tr>
      </thead>

      <tbody>
        <template v-if="mappedRows.length">
          <template v-for="(r, i) in mappedRows" :key="i">
            <tr class="group">
              <td v-if="select" class="py-3 px-5 text-gray-700 group-hover:bg-gray-50">
                <input
                  v-model="selectedItem"
                  type="checkbox"
                  :class="[
                    'appearance-none bg-white w-5 h-5 border-2 inline-flex items-center justify-center cursor-pointer',
                    'checked:border-primary-400 focus:ring-1',
                    'before:w-[90%] before:h-2 before:inline-block transition-colors before:transition-all before:duration-400',
                    'before:opacity-0 checked:before:opacity-100 checked:before:-rotate-45 checked:before:-translate-y-0.5',
                    'before:border-2 before:border-transparent before:border-l-primary-400 before:border-b-primary-400',
                  ]"
                  :value="r[select]"
                  :key="`checkbox-${i}`"
                />
              </td>
              <td
                v-for="column in columns"
                :key="`td-${column.name}-${i}`"
                class="py-3 px-5 text-gray-700 group-hover:bg-gray-50"
              >
                <slot
                  v-if="$slots[`cell-${column.name}`]"
                  :name="`cell-${column.name}`"
                  :column="column"
                  :raw="r"
                  :index="i"
                />
                <template v-else>
                  <template v-if="column.format">
                    {{ column.format(r[column.field], r) }}
                  </template>
                  <span v-else-if="column.field === null"></span>
                  <template v-else>
                    {{ r[column.field] }}
                  </template>
                </template>
              </td>
            </tr>

            <tr v-show="checkExpand(i)" class="group">
              <td colspan="100%" class="px-5 py-7 group-hover:bg-gray-50">
                <slot name="row-expand" :raw="r" :index="i" />
              </td>
            </tr>
          </template>
        </template>
        <tr v-else>
          <td colspan="100%" class="text-center py-3 px-5">No data</td>
        </tr>
      </tbody>
    </table>

    <p class="text-xs text-right px-4 flex justify-end items-center pt-5 text-gray-500">
      Record per page: {{ pagination.limit }}, {{ startCount }} - {{ endCount }} of {{ pagination.total }}

      <c-icon
        tag="button"
        type="button"
        name="arrow-3"
        class="text-gray-700 disabled:text-gray-400 ml-2"
        @click="firstPage"
        :disabled="pagination.page === 1"
      />
      <c-icon
        tag="button"
        type="button"
        name="arrow-73"
        class="text-gray-700 disabled:text-gray-400"
        @click="prevPage"
        :disabled="pagination.page === 1"
      />

      <c-icon
        tag="button"
        type="button"
        name="arrow-46"
        class="text-gray-700 disabled:text-gray-400"
        @click="nextPage"
        :disabled="pagination.page === pagination.lastPage"
      />
      <c-icon
        tag="button"
        type="button"
        name="arrow-91"
        class="text-gray-700 disabled:text-gray-400"
        @click="lastPage"
        :disabled="pagination.page === pagination.lastPage"
      />
    </p>
  </div>
</template>

<script lang="ts">
  import { computed, defineComponent, onMounted, reactive, ref, watch } from 'vue';
  import { ColumnInstance, DataInstance, PaginationInstance } from '@/instances/ui.instance';
  import helper from '@/services/helper';

  export default defineComponent({
    props: {
      columns: {
        type: Array as () => ColumnInstance[],
        required: true,
      },
      data: {
        type: Array as () => DataInstance[],
        required: true,
      },
      title: {
        type: String,
        default: null,
      },
      serverSide: {
        type: Boolean,
        default: false,
      },
      loading: {
        type: Boolean,
        default: false,
      },
      modelPagination: {
        type: Object as () => PaginationInstance,
        default: null,
      },
      withoutShadow: {
        type: Boolean,
        default: false,
      },
      select: {
        type: String,
        default: null,
      },
      modelSelection: {
        type: Array,
        default: null,
      },
      modelExpand: {
        type: Array,
        default: null,
      },
    },
    emits: ['changePage', 'update:pagination', 'update:modelSelection', 'update:modelExpand'],
    setup(props, ctx) {
      const rows = ref<DataInstance[]>([]);
      const selectedItem = ref<any[]>(props.modelSelection || []);
      const expands = ref<any[]>(props.modelExpand || []);

      const pagination = reactive<PaginationInstance>(
        props.modelPagination
          ? props.modelPagination
          : {
              page: 1,
              limit: 10,
              total: 0,
              count: 0,
              lastPage: 0,
            }
      );

      const chunk = computed(() => (!props.serverSide ? helper.chunk(rows.value, pagination.limit) : []));
      const startCount = computed(() => {
        if (!props.serverSide) {
          return (pagination.page - 1) * pagination.limit + 1;
        }

        return (pagination.page - 1) * pagination.limit + 1;
      });

      const mappedRows = computed(() => {
        if (!props.serverSide) {
          return chunk.value[pagination.page - 1] || [];
        }

        return rows.value;
      });

      const endCount = computed(() => {
        if (!props.serverSide) {
          return (pagination.page - 1) * pagination.limit + mappedRows.value.length;
        }

        return (pagination.page - 1) * pagination.limit + pagination.count;
      });

      onMounted(() => {
        rows.value = props.data;

        if (!props.serverSide) {
          pagination.total = props.data.length;
          pagination.lastPage = helper.chunk(props.data, pagination.limit).length;
        }
      });

      watch(
        () => props.data,
        () => {
          rows.value = props.data;

          if (!props.serverSide) {
            pagination.total = props.data.length;
            pagination.lastPage = helper.chunk(props.data, pagination.limit).length;
          }
        }
      );

      watch(
        () => props.modelPagination,
        () => {
          if (props.modelPagination) {
            pagination.page = props.modelPagination.page;
            pagination.limit = props.modelPagination.limit;
            pagination.total = props.modelPagination.total;
            pagination.count = props.modelPagination.count;
            pagination.lastPage = props.modelPagination.lastPage;
          }
        }
      );

      watch(selectedItem, (to) => {
        ctx.emit('update:modelSelection', to);
      });

      watch(
        () => props.modelSelection,
        (to) => {
          selectedItem.value = to;
        }
      );

      watch(
        () => props.modelExpand,
        (to) => {
          expands.value = to;
        }
      );

      const emitPage = () => {
        ctx.emit('changePage', pagination.page);
        ctx.emit('update:pagination', pagination);

        expands.value = [];
        ctx.emit('update:modelExpand', expands.value);
      };

      const nextPage = () => {
        pagination.page += 1;
        emitPage();
      };

      const prevPage = () => {
        pagination.page -= 1;
        emitPage();
      };

      const firstPage = () => {
        pagination.page = 1;
        emitPage();
      };

      const lastPage = () => {
        pagination.page = pagination.lastPage;
        emitPage();
      };

      const checkExpand = (index: number): boolean => {
        return expands.value.includes(index);
      };

      return {
        rows,

        pagination,
        selectedItem,
        expands,

        chunk,
        startCount,
        endCount,
        mappedRows,

        nextPage,
        prevPage,
        firstPage,
        lastPage,

        checkExpand,
      };
    },
  });
</script>
