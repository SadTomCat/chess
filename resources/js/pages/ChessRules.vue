<template>
    <div class="chess-rules">
        <base-side-panel class="chess-rules__side-panel"
                         :links="links"
                         @locationWasChanged="locationWasChanged"
        ></base-side-panel>

        <custom-ck-editor-wrapper class="chess-rules-content__wrapper">
            <h1 class="chess-rules__title">{{ title }}</h1>

            <div class="chess-rules__content" v-html="content"></div>
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
import { onBeforeMount, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import BaseSidePanel from '../components/sidePanels/BaseSidePanel.vue';
import chessRuleNamesAllRequest from '../api/chessRuleNames/chessRuleNamesAllRequest';
import chessRulesGetOneRequest from '../api/chessRules/chessRulesGetOneRequest';
import CustomCkEditorWrapper from '../components/ckeditor/CustomCkEditorWrapper.vue';
import WarningPopUp from '../components/popUps/WarningPopUp.vue';

export default {
    name: 'ChessRules',

    setup() {
        const route = useRoute();
        const router = useRouter();

        /* ---------- Cache ---------- */

        const getFromCache = (key) => {
            try {
                return JSON.parse(sessionStorage.getItem(key));
            } catch (e) {
                return undefined;
            }
        };

        const setCache = (key, value) => {
            if (value === undefined || value === null || Number.isNaN(value) === true) return;

            sessionStorage.setItem(key, JSON.stringify(value));
        };

        const removeFromCache = (key) => {
            sessionStorage.removeItem(key);
        };

        /* ---------- Types validators ---------- */

        /** @return boolean */
        const isArrayOfChessRuleNameInfo = (value) => window.isArrayOf(window.isChessRuleNameInfo, value);

        /* ---------- Warning ----------*/
        const warningSettings = reactive({
            isShown: false,
            message: '',
        });

        const setWarningSettings = (message) => {
            warningSettings.isShown = true;
            warningSettings.message = message;
        };

        const closeWarningPopUp = () => {
            warningSettings.message = '';
            warningSettings.isShown = false;
        };

        /* ---------- Rule links ----------*/
        /** @member {Array<ChessRuleLink>}  */
        const links = reactive([]);

        /**
         * @param {Array<ChessRuleNameInfo>} ruleNamesInfo
         * */
        const setLinks = (ruleNamesInfo) => {
            ruleNamesInfo.forEach((ruleName) => {
                links.push({
                    name: ruleName.name,
                    slug: ruleName.slug,
                    link: `/chess-rules/${ruleName.slug}`,
                });
            });
        };

        /** @return Promise<Array<ChessRuleNameInfo>|boolean> */
        const fetchRuleNamesInfo = async () => {
            const data = await chessRuleNamesAllRequest(true);

            if (isArrayOfChessRuleNameInfo(data.names_info) === true) {
                return data.names_info;
            }

            setWarningSettings('Can not fetch rules');
            return false;
        };

        /**
         * Get from server or sessionStorage and cache them
         *
         *  @return {Array.<ChessRuleNameInfo>|boolean}
         *  */
        const getRuleNamesInfo = async () => {
            let ruleNamesInfo = getFromCache('chess-rules-links');

            if (isArrayOfChessRuleNameInfo(ruleNamesInfo) === false) {
                ruleNamesInfo = await fetchRuleNamesInfo();

                if (ruleNamesInfo === false) {
                    return false;
                }

                setCache('chess-rules-links', ruleNamesInfo);
            }

            return ruleNamesInfo;
        };

        const invalidateLinks = async () => {
            removeFromCache('chess-rules-links');
            links.length = 0;

            const ruleNamesInfo = await getRuleNamesInfo();

            if (ruleNamesInfo === false) return;

            setLinks(ruleNamesInfo);
        };

        /* ---------- ChessRule ----------*/

        const title = ref('');
        const content = ref('');

        const clearRulePage = () => {
            title.value = '';
            content.value = '';
        };

        /** @return {string|boolean} */
        const fetchRuleContent = async (slug) => {
            closeWarningPopUp();

            const data = await chessRulesGetOneRequest(slug);

            if (window.isString(data.content)) {
                return data.content;
            }

            const message = data.exists === false || data.message === undefined
                ? 'This rule not exists yet'
                : data.message;

            clearRulePage();
            setWarningSettings(message);
            await invalidateLinks();

            await router.replace('/chess-rules');

            return false;
        };

        /**
         * Set from server or sessionStorage and cache them
         *
         *  @param {ChessRuleNameInfo} ruleNameInfo
         *  @return {Boolean}
         *  */
        const setRuleContent = async (ruleNameInfo) => {
            if (window.isChessRuleNameInfo(ruleNameInfo) === false) {
                setWarningSettings('Something went wrong');
                return false;
            }

            let ruleContent = getFromCache(`chess-rule-${ruleNameInfo.slug}-content`);

            if (window.isString(ruleContent) === false) {
                ruleContent = await fetchRuleContent(ruleNameInfo.slug);

                if (ruleContent === false) {
                    return false;
                }

                setCache(`chess-rule-${ruleNameInfo.slug}-content`, ruleContent);
            }

            title.value = ruleNameInfo.name;
            content.value = ruleContent;
            return true;
        };

        /* ---------- Other ---------- */

        const locationWasChanged = async (indexInLinks) => {
            await setRuleContent(links[indexInLinks]);
        };

        onBeforeMount(async () => {
            const ruleNamesInfo = await getRuleNamesInfo();

            if (ruleNamesInfo === false) return;

            setLinks(ruleNamesInfo);

            if (route.params.rule !== '') {
                const ruleNameInfo = ruleNamesInfo.find((el) => el.slug === route.params.rule);
                await setRuleContent(ruleNameInfo);
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
.chess-rules {
    @apply flex min-h-full;

    &__side-panel {
        @apply flex-shrink-0;
    }

    &-content__wrapper {
        @apply flex-grow w-full py-10 px-10;
    }

    &__title {
        @apply text-4xl mb-4 pb-3 w-full;
    }
}
</style>
