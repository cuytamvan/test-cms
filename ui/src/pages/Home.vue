<template>
  <c-section>
    <c-title
      title="Explore"
      icon="abstract-14"
      description="Lorem, ipsum dolor sit amet consectetur adipisicing elit. Minima, maiores."
    />
  </c-section>

  <c-section class="py-10">
    <div class="flex gap-10">
      <div class="flex-1">
        <div class="flex mb-5">
          <div class="w-4/12 flex items-center gap-3">
            <label>Search</label>
            <c-input v-model="article.search" size="sm" :debounce="700" class="flex-1" />
          </div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
          <template v-if="!article.loading">
            <div
              v-for="r in article.data"
              :key="`article-${r.id}`"
              class="shadow-lg shadow-gray-200 rounded-lg overflow-hidden group"
            >
              <div class="overflow-hidden h-32">
                <router-link :to="{ name: 'article-detail', params: { slug: r.slug } }">
                  <img
                    :src="r.banner_url"
                    :alt="r.title"
                    class="w-full h-full object-cover group-hover:scale-105 transition-all duration-300"
                  />
                </router-link>
              </div>

              <div class="py-3 px-4">
                <span
                  v-if="r.article_category"
                  class="py-1 px-3 inline-block text-xs text-primary-600 bg-primary-100 rounded-full"
                >
                  {{ r.article_category.name }}
                </span>

                <router-link
                  :to="{ name: 'article-detail', params: { slug: r.slug } }"
                  class="block font-medium text-gray-600 mt-2 capitalize truncate"
                >
                  {{ r.title }}
                </router-link>

                <p class="truncate text-gray-500 mt-1 mb-2 text-sm">
                  {{ r.preview }}
                </p>
              </div>
            </div>
          </template>
          <template v-else>
            <div v-for="r in $helper.range(3)" :key="r" class="bg-gray-200 h-[100px] rounded-lg animate-pulse"></div>
          </template>
        </div>

        <div class="text-center mt-10">
          <button
            v-if="article.data.length && article.pagination.page !== article.pagination.lastPage"
            class="py-2 px-6 text-sm inline-block text-primary-600 bg-primary-100 rounded-full"
            :disabled="article.loading"
            @click="loadMore"
          >
            Load More
          </button>
        </div>
      </div>

      <div class="w-72">
        <div class="sticky top-5">
          <div class="bg-white shadow-lg shadow-gray-200 p-5 rounded-lg flex flex-wrap gap-1.5">
            <h1 class="w-full font-medium mb-2 text-gray-700">Categories</h1>

            <button
              key="category-null"
              :class="[
                'py-1 px-3 text-xs rounded-full transition-all duration-300',
                selectedCategory === null
                  ? 'text-white bg-blue-400'
                  : 'text-gray-700 bg-gray-100 hover:bg-gray-200 focus:bg-gray-200',
              ]"
              @click="selectedCategory = null"
            >
              All
            </button>

            <button
              v-for="categoryItem in category.data"
              :key="`category-${categoryItem.id}`"
              :class="[
                'py-1 px-3 text-xs rounded-full transition-all duration-300',
                selectedCategory === categoryItem.id
                  ? 'text-white bg-blue-400'
                  : 'text-gray-700 bg-gray-100 hover:bg-gray-200 focus:bg-gray-200',
              ]"
              @click="selectedCategory = categoryItem.id"
            >
              {{ categoryItem.name }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </c-section>
</template>

<script lang="ts">
  import { defineComponent, onMounted, reactive, ref, watch } from 'vue';

  import useHandler from '@/composables/handler';

  import { useAuthStore } from '@/stores/auth.store';

  import { ArticleCategoryInstance } from '@/instances/article_category.instance';
  import { PaginationInstance } from '@/instances/ui.instance';
  import { ArticleInstance } from '@/instances/article.instance';

  interface Table {
    data: ArticleInstance[];
    pagination: PaginationInstance;
    loading: boolean;
    search: string;
  }

  export default defineComponent({
    setup() {
      const authStore = useAuthStore();

      const { http, defPagination } = useHandler();

      const selectedCategory = ref<number | null>(null);
      const category = reactive<{ loading: boolean; data: ArticleCategoryInstance[] }>({
        loading: false,
        data: [],
      });

      const article = reactive<Table>({
        data: [],
        pagination: { ...defPagination },
        loading: false,
        search: '',
      });

      const getArticle = async (): Promise<void> => {
        let uri = `api/articles?_page=${article.pagination.page}&_limit=${article.pagination.limit}`;
        uri += `&_like=title:${encodeURIComponent(article.search)}`;
        if (selectedCategory.value) uri += `&_search=article_category_id!:${selectedCategory.value}`;

        article.loading = true;
        const res = await http.get(uri);
        article.loading = false;

        if (res?.status === 200) {
          const data = res.data;

          article.data = [...article.data, ...data.items];
          article.pagination.lastPage = data.meta.last_page;
          article.pagination.limit = data.meta.item_per_page;
          article.pagination.page = data.meta.current_page;
          article.pagination.total = data.meta.total;
          article.pagination.count = data.meta.count;
        }
      };

      const loadMore = async () => {
        if (article.pagination.page !== article.pagination.total) {
          console.log('test');
          article.pagination.page += 1;
          await getArticle();
        }
      };

      const getCategory = async () => {
        const endpoint = 'api/article-categories?_limit=0';
        category.loading = true;
        const req = await http.get(endpoint);
        category.loading = false;

        if (req?.status === 200) category.data = req.data;
      };

      watch(selectedCategory, async () => {
        article.pagination.page = 1;
        article.data = [];

        await getArticle();
      });

      watch(
        () => article.search,
        async () => {
          article.pagination.page = 1;
          article.data = [];

          await getArticle();
        }
      );

      onMounted(async () => {
        await getCategory();
        await getArticle();
      });

      return {
        authStore,

        article,

        category,
        selectedCategory,

        getArticle,
        loadMore,
      };
    },
  });
</script>
