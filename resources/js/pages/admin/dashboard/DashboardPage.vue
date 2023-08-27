<template>
    <Toast />
    <div class="surface-ground px-4 py-5 md:px-6 lg:px-8">
        <div class="grid">
            <div class="col-12 md:col-6 lg:col-3">
                <div class="surface-card shadow-2 p-3 border-round">
                    <div class="flex justify-content-between mb-3">
                        <div>
                            <span class="block text-500 font-medium mb-3"
                                >All Users</span
                            >
                            <div class="text-green-500 font-medium">
                                {{ users_count }}
                            </div>
                        </div>
                        <div
                            class="flex align-items-center justify-content-center bg-cyan-100 border-round"
                            style="width: 2.5rem; height: 2.5rem"
                        >
                            <i class="pi pi-inbox text-cyan-500 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 md:col-6 lg:col-3">
                <div class="surface-card shadow-2 p-3 border-round">
                    <div class="flex justify-content-between mb-3">
                        <div>
                            <span class="block text-500 font-medium mb-3"
                                >All Levels</span
                            >
                            <div class="text-green-500 font-medium">
                                {{ levels_count }}
                            </div>
                        </div>
                        <div
                            class="flex align-items-center justify-content-center bg-cyan-100 border-round"
                            style="width: 2.5rem; height: 2.5rem"
                        >
                            <i class="pi pi-inbox text-cyan-500 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 md:col-6 lg:col-3">
                <div class="surface-card shadow-2 p-3 border-round">
                    <div class="flex justify-content-between mb-3">
                        <div>
                            <span class="block text-500 font-medium mb-3"
                                >All Teams</span
                            >
                            <div class="text-green-500 font-medium">
                                {{ teams_count }}
                            </div>
                        </div>
                        <div
                            class="flex align-items-center justify-content-center bg-cyan-100 border-round"
                            style="width: 2.5rem; height: 2.5rem"
                        >
                            <i class="pi pi-inbox text-cyan-500 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 lg:col-4" v-for="team of teams">
                <div class="h-full">
                    <div
                        class="shadow-2 p-3 h-full flex flex-column surface-card"
                    >
                        <div
                            class="flex"
                            style="justify-content: space-between"
                        >
                            <div class="mb-2">
                                <div class="text-900 font-medium text-xl">
                                    {{ team.name }}
                                </div>
                                <hr
                                    class="my-3 mx-0 border-top-1 border-none surface-border"
                                />
                                <div>
                                    Team Members : &nbsp;
                                    <span class="font-bold text-2xl text-900">{{
                                        team.users_count
                                    }}</span>
                                </div>
                                <hr
                                    class="my-3 mx-0 border-top-1 border-none surface-border"
                                />
                                <div>
                                    Team Score : &nbsp;
                                    <span class="font-bold text-2xl text-900">{{
                                        team.score
                                    }}</span>
                                </div>
                            </div>
                            <div>
                                <img
                                    :src="team.image_path"
                                    :alt="team.image"
                                    class="shadow-2"
                                    width="100"
                                />
                            </div>
                        </div>
                        <hr
                            class="my-3 mx-0 border-top-1 border-none surface-border"
                        />
                        <table>
                            <thead class="text-center">
                                <th>
                                    <i
                                        class="pi pi-check-circle text-green-500"
                                    ></i>
                                </th>
                                <th>Level</th>
                                <th>Score Type</th>
                                <th>Score</th>
                            </thead>
                            <tr
                                class="text-center border-b border-gray-200 dark:border-gray-700"
                                v-for="(levels, index) of team.levels"
                            >
                                <td>
                                    <i
                                        class="pi pi-check-circle text-green-500"
                                    ></i>
                                </td>
                                <td>Level : {{ index + 1 }}</td>
                                <td>{{ levels.type }}</td>
                                <td>{{ levels.pivot.score }}</td>
                            </tr>
                        </table>
                        <hr
                            class="mb-3 mx-0 border-top-1 border-none surface-border"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useToast } from "primevue/usetoast";

export default {
    data() {
        return {
            users_count: 0,
            levels_count: 0,
            teams_count: 0,
            teams: [],
            loading: false,
            submitted: false,
        };
    }, // end of data

    methods: {
        fill() {
            this.loading = true;
            axios
                .get("/api/admin/dashboard")
                .then((response) => {
                    this.users_count = response.data.users_count;
                    this.levels_count = response.data.levels_count;
                    this.teams_count = response.data.teams_count;
                    this.teams = response.data.teams;
                    console.log(this.teams);
                })
                .catch((error) => {
                    if (error.response) {
                        this.toast.add({
                            severity: "error",
                            summary: "Error",
                            detail: error.response.data.message,
                            life: 15000,
                        });
                    }
                })
                .then(() => {
                    this.loading = false;
                });
        }, // end of fill
    }, // end of methods

    beforeMount() {
        this.toast = useToast();
    }, // end of beforeMount

    mounted() {
        this.fill();
    }, // end of mounted
};
</script>

<style lang="scss">
.right-to-left {
    .p-editor-content {
        .ql-editor {
            text-align: right;
        }
    }
}
</style>
