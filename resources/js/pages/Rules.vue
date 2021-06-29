<template>
    <div class="rules">
        <base-side-panel class="rules__side-panel"
                         :links="links"
                         @locationWasChanged="locationWasChanged"
        ></base-side-panel>

        <custom-ck-editor-wrapper class="rules-content__wrapper">
            <h1 class="rules-content__title">{{ title }}</h1>
            <div class="rules-content" v-html="content"></div>
        </custom-ck-editor-wrapper>
    </div>
</template>

<script>
import {
    onBeforeMount, reactive, ref,
} from 'vue';
import { useRoute } from 'vue-router';
import BaseSidePanel from '../components/sidePanels/BaseSidePanel.vue';
import ruleCategoriesAllRequest from '../api/ruleCategories/ruleCategoriesAllRequest';
import ruleGetOneRequest from '../api/rules/ruleGetOneRequest';
import CustomCkEditorWrapper from '../components/ckeditor/CustomCkEditorWrapper.vue';

export default {
    name: 'Rules',

    setup() {
        const route = useRoute();

        const title = ref('');
        const content = ref('');

        const fetchRuleContent = async (ruleName) => {
            const data = await ruleGetOneRequest(ruleName);

            if (data.exists === false) {
                console.log('Can not fetch rule');
                return;
            }

            title.value = ruleName;
            content.value = data.content;
        };

        const links = reactive([]);

        const setLinksAfterRequest = (data) => {
            data.categories.forEach((category) => {
                links.push({
                    name: category.name,
                    link: `/rules/${category.name}`,
                });
            });
        };

        const locationWasChanged = async (indexInLinks) => {
            await fetchRuleContent(links[indexInLinks].name);
        };

        onBeforeMount(async () => {
            const data = await ruleCategoriesAllRequest();

            if (data.status === false) {
                console.log('Can not fetch rule categories');
                return;
            }

            if (route.params.rule !== '') {
                await fetchRuleContent(route.params.rule);
            }

            setLinksAfterRequest(data);
        });

        return {
            links,
            content,
            title,
            locationWasChanged,
        };
    },

    components: {
        BaseSidePanel,
        CustomCkEditorWrapper,
    },
};
</script>

<style lang="scss">
.rules {
    @apply flex min-h-full;

    .rules__side-panel {
        @apply flex-shrink-0;
    }

    .rules-content__wrapper {
        @apply flex-grow w-full py-10 px-10;

        .rules-content__title {
            @apply text-4xl mb-4 pb-3 w-full;
        }
    }
}
</style>
