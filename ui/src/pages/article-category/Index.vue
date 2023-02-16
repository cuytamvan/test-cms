<template>
  <c-section>
    <c-title :title="Meta.title" :icon="Meta.icon" :description="Meta.description" />
  </c-section>

  <c-dialog ref="dialogMessage" title="Validation" message="Are you sure to delete this data?">
    <div class="bg-white py-6 px-10 rounded-md" style="width: 400px; max-width: 90vw">
      <h4 class="flex text-xl text-gray-600">
        <span class="flex-1">Validation</span>
        <button
          type="button"
          class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8"
          data-dismiss-target="#toast-success"
          aria-label="Close"
          @click="dialogMessage?.hide()"
        >
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path
              fill-rule="evenodd"
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clip-rule="evenodd"
            ></path>
          </svg>
        </button>
      </h4>
      <p class="mt-2 text-gray-500">Are you sure to delete this data?</p>

      <div class="text-center pt-5">
        <button
          class="text-blue-500 bg-blue-50 focus:bg-blue-100 hover:bg-blue-100 focus:ring py-2 px-4 mr-2 rounded-md"
          @click="dialogMessage?.hide()"
        >
          Back
        </button>
        <button
          class="text-red-500 bg-red-50 focus:bg-red-100 hover:bg-red-100 focus:ring py-2 px-4 rounded-md"
          @click="selectedId && remove(selectedId)"
        >
          Delete
        </button>
      </div>
    </div>
  </c-dialog>

  <c-dialog ref="dialogForm" :title="`${title} ${Meta.title}`">
    <div class="max-w-[80vw] w-[600px] bg-white relative rounded-md mx-auto">
      <div class="flex items-center py-4 px-5 border-b border-b-gray-100">
        <span class="font-medium text-gray-700">{{ title }} {{ Meta.title }}</span>

        <button
          type="button"
          class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8"
          @click="dialogForm?.hide()"
        >
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path
              fill-rule="evenodd"
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clip-rule="evenodd"
            ></path>
          </svg>
        </button>
      </div>

      <form @submit.prevent="submit()">
        <div class="py-4 px-5 grid gap-3">
          <div>
            <label class="mb-1 block text-gray-700">Name <span class="text-red-500">*</span></label>
            <c-input v-model="form.name" required />
          </div>
        </div>

        <div class="text-right pb-4 px-3">
          <button
            type="button"
            class="text-red-500 bg-red-50 focus:bg-red-100 hover:bg-red-100 focus:ring py-1 px-2.5 rounded-md text-sm mr-2"
            @click="dialogForm?.hide()"
          >
            Close
          </button>

          <button
            type="submit"
            class="text-primary-500 bg-primary-50 focus:bg-primary-100 hover:bg-primary-100 focus:ring py-1 px-2.5 rounded-md text-sm mr-2"
          >
            Save
          </button>
        </div>
      </form>
    </div>
  </c-dialog>

  <c-section class="py-10">
    <c-table
      v-model:model-pagination="table.pagination"
      :columns="columns"
      :data="table.data"
      :loading="table.loading"
      server-side
      @changePage="getList"
      class="overflow-x-auto custom-scroll"
    >
      <template #header>
        <div class="pt-4 flex flex-wrap justify-between items-center">
          <div>
            <button
              v-if="checkPermission('Create')"
              class="text-primary-500 bg-primary-50 focus:bg-primary-100 hover:bg-primary-100 focus:ring py-2 px-4 rounded-md"
              @click="create()"
            >
              Create
            </button>
          </div>

          <div>
            <c-input v-model="table.search" size="sm" :debounce="700" />
          </div>
        </div>
      </template>

      <template #cell-action="{ raw }">
        <button
          v-if="checkPermission('Update')"
          class="bg-blue-100 focus:bg-blue-200 text-blue-500 inline-flex items-center justify-center py-1 px-2 w-8 h-8 rounded-full"
          @click="edit(raw)"
        >
          <span class="material-icons-outlined text-base">edit</span>
        </button>

        <button
          v-if="checkPermission('Delete')"
          class="bg-red-100 focus:bg-red-200 text-red-500 inline-flex items-center justify-center py-1 px-2 w-8 h-8 rounded-full ml-1"
          @click="
            () => {
              dialogMessage?.show();
              selectedId = raw.id;
            }
          "
        >
          <span class="material-icons-outlined text-base">delete</span>
        </button>
      </template>
    </c-table>
  </c-section>
</template>

<script lang="ts">
  import { computed, defineComponent, onMounted, ref, watch } from 'vue';
  import useHandler from '@/composables/handler';
  import { ColumnInstance } from '@/instances/ui.instance';
  import CDialog from '@/components/_global/CDialog.vue';
  import { Meta } from './meta';
  import { ArticleCategoryInstance } from '@/instances/article_category.instance';

  export default defineComponent({
    components: {
      CDialog,
    },
    setup() {
      const $repo = useHandler(Meta);

      const { table, checkPermission } = $repo;

      const dialogMessage = ref<InstanceType<typeof CDialog>>();
      const selectedId = ref<number>();
      const dialogForm = ref<InstanceType<typeof CDialog>>();

      const defModel: ArticleCategoryInstance = {
        id: null,
        name: '',
      };

      const form = ref<ArticleCategoryInstance>({
        ...defModel,
      });

      const columns = computed<ColumnInstance[]>(() => [
        {
          label: 'Action',
          name: 'action',
          field: 'id',
        },
        ...table.columns,
      ]);
      const title = computed(() => (form.value.id ? 'Edit' : 'Create'));

      const getList = async (): Promise<void> => {
        await $repo.getList();
      };

      const remove = async (id: string | number) => {
        const data = await $repo.remove(id);

        if (data && data.status === 200) {
          dialogMessage.value?.hide();

          table.pagination.page = 1;
          await getList();
        }
      };

      const resetForm = () => {
        form.value = { ...defModel };
      };

      const create = () => {
        dialogForm.value?.show();
        resetForm();
      };

      const edit = (data: ArticleCategoryInstance) => {
        dialogForm.value?.show();
        resetForm();

        form.value = { ...data };
      };

      const submit = async () => {
        const req = form.value.id ? await $repo.update(form.value.id, form.value) : await $repo.store(form.value);

        if (req?.status === 200) {
          await getList();

          dialogForm.value?.hide();
        }
      };

      watch(
        () => table.search,
        async () => {
          table.pagination.page = 1;
          await getList();
        }
      );

      onMounted(async () => {
        await getList();
      });

      return {
        Meta,
        table,
        columns,
        dialogMessage,
        dialogForm,
        selectedId,

        form,

        title,

        checkPermission,
        getList,
        create,
        edit,
        resetForm,
        submit,
        remove,
      };
    },
  });
</script>
