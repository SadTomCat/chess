<template>
    <div class="admin-games px-10 py-10 flex justify-center">
        <api-table :columns="['token', 'start_at', 'end_at', 'winner_color']" :table="'games'"></api-table>
    </div>
</template>

<script>
import ApiTable from '~/components/table/ApiTable.vue';

export default {
    name: 'AdminGames',

    components: { ApiTable },
};
</script>

<style scoped>

</style>
