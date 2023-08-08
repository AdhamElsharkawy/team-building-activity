<template>
    <Dialog
        v-model:visible="teamDialog"
        :header="'Team ' + team.name"
        :modal="true"
        class="p-fluid w-full m-5"
    >
        <div class="flex flex-col md:flex-row justify-content-center w-full">
            <div class="flex flex-col md:flex-row">
                <img
                    :src="team.image_path"
                    :alt="team.image"
                    v-if="team.image"
                    width="150"
                    class="mt-0 mb-5 block shadow-2"
                />
                <div>
                    <h3 class="text-lg font-bold">Members</h3>
                    <ul class="flex flex-row">
                        <li
                            v-for="member in team.users"
                            :key="member.id"
                            class="text-base"
                        >
                            {{ member.name }}
                        </li>
                    </ul>

                </div>
            </div>
            <div></div>
        </div>

        <template #footer>
            <div
                :class="{
                    'flex flex-row-reverse float-left': $store.getters.isRtl,
                }"
            >
                <Button
                    :label="$t('close')"
                    icon="pi pi-times"
                    class="p-button-text"
                    @click="hideDialog"
                />
            </div>
        </template>
    </Dialog>
</template>

<script>
export default {
    data() {
        return {
            team: {},
            teamDialog: false,
            submitted: false,
        };
    },
    methods: {
        async openDialog(team) {
            this.team = await axios.get("/api/admin/teams/" + team.id).then(
                (response) => {
                    return response.data.team;
                },
                (error) => {
                    console.log(error);
                }
            );
            console.log(this.team);
            this.teamDialog = true;
        }, //end of openDialog

        hideDialog() {
            this.team = {};
            this.teamDialog = false;
            this.submitted = false;
        }, //end of hideDialog
    }, //end of methods
};
</script>
