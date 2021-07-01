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

    <teleport to="body">
        <warning-pop-up @closeWarningPopUp="closeWarningPopUp" v-if="warningSettings.isShown">
            <div class="text-2xl">
                <p>{{ warningSettings.message }}</p>
            </div>
        </warning-pop-up>
    </teleport>
</template>

<script>
import {
    onBeforeMount, reactive, ref,
} from 'vue';
import { useRoute, useRouter } from 'vue-router';
import BaseSidePanel from '../components/sidePanels/BaseSidePanel.vue';
import ruleCategoriesAllRequest from '../api/ruleCategories/ruleCategoriesAllRequest';
import ruleGetOneRequest from '../api/rules/ruleGetOneRequest';
import CustomCkEditorWrapper from '../components/ckeditor/CustomCkEditorWrapper.vue';
import WarningPopUp from '../components/popUps/WarningPopUp.vue';

export default {
    name: 'Rules',

    setup() {
        const route = useRoute();
        const router = useRouter();

        /* ---------- Warning ----------*/
        const warningSettings = reactive({
            isShown: false,
            message: '',
        });

        const closeWarningPopUp = () => {
            warningSettings.message = '';
            warningSettings.isShown = false;
        };

        /* ---------- Rule ----------*/
        const title = ref('');
        const content = ref('');

        /** @return {String|Boolean} */
        const fetchRuleContent = async (ruleName) => {
            closeWarningPopUp();

            const data = await ruleGetOneRequest(ruleName);

            if (data.exists === false) {
                warningSettings.isShown = true;
                warningSettings.message = 'This rule not exists yet';
                await router.replace('/rules');
                return false;
            }

            return data.content;
        };

        /**
         * Set from server or sessionStorage and cache them
         *
         *  @return {Boolean}
         *  */
        const setRuleContent = async (ruleName) => {
            let ruleContent = JSON.parse(sessionStorage.getItem(`rule-${ruleName}-content`));

            if (ruleContent === null) {
                ruleContent = await fetchRuleContent(ruleName);

                if (ruleContent === false) {
                    return false;
                }

                sessionStorage.setItem(`rule-${ruleName}-content`, JSON.stringify(ruleContent));
            }

            title.value = ruleName;
            content.value = ruleContent;
            return true;
        };

        /* ---------- Rules links ----------*/
        const links = reactive([]);

        /**  @return {Object|Boolean} */
        const fetchLinks = async () => {
            const data = await ruleCategoriesAllRequest();

            if (data.status === false) {
                warningSettings.isShown = true;
                warningSettings.message = 'Can not fetch rules';
                return false;
            }

            return data.categories;
        };

        /**
         * Get from server or sessionStorage and cache them
         *
         *  @return {Object|Boolean}
         *  */
        const getLinks = async () => {
            let rulesLinks = JSON.parse(sessionStorage.getItem('rules-links'));

            if (rulesLinks === null) {
                rulesLinks = await fetchLinks();

                if (rulesLinks === false) {
                    return false;
                }

                sessionStorage.setItem('rules-links', JSON.stringify(rulesLinks));
            }

            return rulesLinks;
        };

        const setLinksAfterRequest = (rulesLinks) => {
            rulesLinks.forEach((category) => {
                links.push({
                    name: category.name,
                    link: `/rules/${category.name}`,
                });
            });
        };

        const locationWasChanged = async (indexInLinks) => {
            await setRuleContent(links[indexInLinks].name);
        };

        onBeforeMount(async () => {
            const rulesLinks = await getLinks();

            setLinksAfterRequest(rulesLinks);

            if (route.params.rule !== '') {
                await setRuleContent(route.params.rule);
            }
        });

        return {
            warningSettings,
            closeWarningPopUp,
            links,
            content,
            title,
            locationWasChanged,
        };
    },

    components: {
        WarningPopUp,
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
