<template>
    <div id="app" :class="isRtl">
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    name: "App",

    computed: {
        isRtl() {
            return {
                "text-right": this.$store.getters["isRtl"],
            };
        },
    },
};
</script>

<style scoped>
#app {
    min-height: 100vh;
}
</style>
<style lang="scss">
input[type="color"] {
    height: 3.2rem;
    padding: 0;
    border: none;
}
.text-right {
    * {
        font-family: "Almarai", sans-serif;
    }
    .pi {
        font-family: "primeicons" !important;
    }
}
.flex-col {
    display: flex !important;
    flex-direction: column !important;
}
</style>
