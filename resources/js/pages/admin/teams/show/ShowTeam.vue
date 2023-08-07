<template>
    <Dialog
        v-model:visible="teamDialog"
        :style="{ width: '450px' }"
        :header="$t('showTeam')"
        :modal="true"
        class="p-fluid"
    >
        <img
            :src="team.image_path"
            :alt="team.image"
            v-if="team.image"
            width="150"
            class="mt-0 mx-auto mb-5 block shadow-2"
        />

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
