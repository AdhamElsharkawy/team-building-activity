<template>
    <Toast />
    <Loading v-if="loading" />
    <div class="grid" v-else>
        <div class="col-12">
            <div class="card">
                <Toolbar
                    class="mb-4"
                    :class="{
                        'flex flex-row-reverse': $store.getters['isRtl'],
                    }"
                >
                    <template v-slot:start>
                        <div
                            class="my-2"
                            :class="{
                                'flex flex-row-reverse':
                                    $store.getters['isRtl'],
                            }"
                        >
                            <Button
                                label="New"
                                icon="pi pi-plus"
                                class="p-button-success"
                                :class="{
                                    'mr-2': !$store.getters['isRtl'],
                                    'ml-2': $store.getters['isRtl'],
                                }"
                                @click="createNewTeam"
                            />
                            <Button
                                label="Delete"
                                icon="pi pi-trash"
                                class="p-button-danger"
                                @click="confirmDeleteSelected"
                                :disabled="
                                    !selectedTeams || !selectedTeams.length
                                "
                            />
                        </div>
                    </template>

                    <template v-slot:end>
                        <Button
                            label="Export"
                            icon="pi pi-upload"
                            class="p-button-help"
                            @click="exportCSV($event)"
                        />
                    </template>
                </Toolbar>

                <team-list
                    ref="listTeamComponent"
                    :currentTeams="currentTeams"
                    @selectTeams="selectTeams"
                    @editTeam="editTeam"
                    @showTeam="showTeam"
                    @deleteTeam="fill"
                ></team-list>

                <edit-team ref="editTeamComponent"></edit-team>

                <show-team
                    ref="showTeamComponent"
                ></show-team>

                <create-team
                    ref="createTeamComponent"
                    @teamCreated="fill"
                ></create-team>

                <Dialog
                    v-model:visible="deleteTeamsDialog"
                    :style="{ width: '450px' }"
                    header="Confirm"
                    :modal="true"
                >
                    <div class="flex align-items-center justify-content-center">
                        <i
                            class="pi pi-exclamation-triangle mr-3"
                            style="font-size: 2rem"
                        />
                        <span v-if="team"
                            >Are you sure you want to delete the selected
                            teams?</span
                        >
                    </div>
                    <template #footer>
                        <Button
                            label="No"
                            icon="pi pi-times"
                            class="p-button-text"
                            @click="deleteTeamsDialog = false"
                        />
                        <Button
                            label="Yes"
                            icon="pi pi-check"
                            class="p-button-text"
                            @click="deleteSelectedTeams"
                        />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>

<script>
import TeamList from "./TeamList.vue";
import EditTeam from "../edit/EditTeam.vue";
import ShowTeam from "../show/ShowTeam.vue";
import CreateTeam from "../create/CreateTeam.vue";
import { useToast } from "primevue/usetoast";

export default {
    components: { TeamList, EditTeam, CreateTeam, ShowTeam },
    data() {
        return {
            currentTeams: [],
            deleteTeamsDialog: false,
            selectedTeams: null,
            loading: false,
            isEmpty: false,
            errors: null,
        };
    }, //end of data

    methods: {
        createNewTeam() {
            this.team = {};
            this.$refs.createTeamComponent.openDialog();
        }, //end of openNew

        deleteSelectedTeams() {
            this.loading = true;
            axios
                .delete("/api/admin/teams/delete/all", {
                    data: {
                        teams: this.selectedTeams.map((val) => val.id),
                    },
                })
                .then((response) => {
                    this.currentTeams = this.currentTeams.filter(
                        (val) => !this.selectedTeams.includes(val)
                    );
                    this.selectedTeams = null;
                    this.toast.add({
                        severity: "success",
                        summary: "Successful",
                        detail: response.data.message,
                        life: 3000,
                    });
                })
                .catch((errors) => {
                    if (errors.response) {
                        this.toast.add({
                            severity: "error",
                            summary: "Error",
                            detail: errors.response.data.message,
                            life: 15000,
                        });
                    }
                })
                .then(() => {
                    this.loading = false;
                    this.deleteTeamsDialog = false;
                });
        }, //end of deleteSelectedTeams

        confirmDeleteSelected() {
            this.deleteTeamsDialog = true;
        }, //end of confirmDeleteSelected

        exportCSV() {
            this.$refs.listTeamComponent.exportCSV();
        }, //end of exportCSV

        fill() {
            this.loading = true;
            axios
                .get("/api/admin/teams")
                .then((response) => {
                    this.currentTeams = response.data.teams;
                })
                .catch((errors) => {
                    this.error = errors.response.data;
                })
                .then(() => {
                    this.loading = false;
                }); //end of axios request
        }, //end of fill function

        selectTeams(selectedTeams) {
            this.selectedTeams = selectedTeams;
        }, //end of selectTeams

        editTeam(team) {
            this.$refs.editTeamComponent.openDialog(team);
        }, //end of editTeam

        showTeam(team) {
            this.$refs.showTeamComponent.openDialog(team);
        }, //end of showTeam
    }, //end of methods

    beforeMount() {
        this.toast = useToast();
    }, //end of beforeMount

    created() {
        this.fill();
    }, //end of created
};
</script>
