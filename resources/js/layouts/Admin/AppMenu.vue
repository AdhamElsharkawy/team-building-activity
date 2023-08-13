<script setup>
import { ref } from "vue";
import AppMenuItem from "./AppMenuItem.vue";
import { useI18n } from "vue-i18n";

const { t: $t } = useI18n();

const model = ref([
    {
        label: $t('home'),
        items: [
            {
                label: $t('dashboard'),
                icon: "pi pi-fw pi-home",
                to: "/admin/dashboard",
                name: "admin.dashboard",
            },
        ],
    },
    {
        label: $t('collections'),
        items: [
            {
                label: "users",
                icon: "pi pi-user",
                to: "/admin/users",
                name: "admin.users",
            },
            {
                label: "teams",
                icon: "pi pi-users",
                to: "/admin/teams",
                name: "admin.teams",
            },
            {
                label: "levels",
                icon: "pi pi-qrcode",
                to: "/admin/levels",
                name: "admin.levels",
            }
        ],
    },
]);
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="item">
            <app-menu-item
                v-if="!item.separator"
                :item="item"
                :index="i"
            ></app-menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template>

<style lang="scss" scoped></style>
