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
                label: "Users",
                icon: "pi pi-user",
                to: "/admin/users",
                name: "admin.users",
            },
            {
                label: "Teams",
                icon: "pi pi-users",
                to: "/admin/teams",
                name: "admin.teams",
            },
            {
                label: "Levels",
                icon: "pi pi-qrcode",
                to: "/admin/levels",
                name: "admin.levels",
            },
            {
                label: "Info",
                icon: "pi pi-info-circle",
                to: "/admin/informations",
                name: "admin.informations",
            }
        ],
    },
]);
</script>

<template>
    <ul class="layout-menu" style="font-size: 1.2rem;">
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
