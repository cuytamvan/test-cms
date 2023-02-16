<template>
  <c-section>
    <div v-if="data" class="p-5 shadow-lg shadow-gray-200 rounded-lg">
      <img :src="data.banner_url" :alt="data.title" class="w-full h-[200px] rounded-lg object-cover" />

      <h1 class="text-2xl font-medium text-gray-700 capitalize mt-5">{{ data.title }}</h1>
      <small v-if="data.created_at" class="text-xs text-gray-700">{{ $helper.beautyDate(data.created_at) }}</small>

      <div v-html="data.content" class="mt-5"></div>
    </div>
  </c-section>
</template>

<script lang="ts">
  import { defineComponent, onMounted, ref } from 'vue';
  import useHandler from '@/composables/handler';

  import { ArticleInstance } from '@/instances/article.instance';

  import { Meta } from './meta';

  export default defineComponent({
    props: {
      slug: {
        type: String,
        required: true,
      },
    },
    setup(props) {
      const $repo = useHandler(Meta);

      const data = ref<ArticleInstance | null>(null);

      const getData = async () => {
        data.value = await $repo.getData(props.slug);
      };

      onMounted(async () => {
        await getData();
      });

      return {
        data,
      };
    },
  });
</script>
