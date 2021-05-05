<template>
    <div class="admin-users px-10 py-10 flex justify-center">
        <api-table :columns="['id', 'name', 'email']"
                   :table="'users'"
                   :defaultActions="['view']"
        ></api-table>
    </div>
</template>

<script>
import ApiTable from '~/components/tables/ApiTable.vue';

export default {
    name: 'AdminUsers',

    components: { ApiTable },
};
</script>

<style lang="scss">

</style>
