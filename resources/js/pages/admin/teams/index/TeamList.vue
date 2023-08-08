<template>
    <Loading v-if="loading" />
    <DataTable
        ref="dt"
        :value="teams"
        v-model:selection="selectedTeams"
        dataKey="id"
        :paginator="true"
        :rows="10"
        :filters="filters"
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
        :rowsPerPageOptions="[5, 10, 25]"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords} teams"
        responsiveLayout="scroll"
    >
        <template #header>
            <div
                class="flex flex-column md:flex-row md:justify-content-between md:align-items-center"
                :class="{ 'md:flex-row-reverse': $store.getters['isRtl'] }"
            >
                <h5 class="m-0">Manage Team</h5>
                <span class="block mt-2 md:mt-0 p-input-icon-left">
                    <i class="pi pi-search" />
                    <InputText
                        v-model="filters['global'].value"
                        placeholder="Search"
                        :class="{ 'text-right': $store.getters['isRtl'] }"
                    />
                </span>
            </div>
        </template>

        <Column
            selectionMode="multiple"
            headerStyle="width: 3rem"
            :class="{ 'text-right': $store.getters['isRtl'] }"
        ></Column>

        <Column
            field="name"
            header="Name"
            :sortable="true"
            headerStyle="width:14%; min-width:10rem;"
            :class="{ 'text-right': $store.getters['isRtl'] }"
        >
            <template #body="slotProps">
                <span class="p-column-title">Name</span>
                {{ slotProps.data.name }}
            </template>
        </Column>

        <Column
            field="image"
            header="Image"
            headerStyle="width:14%; min-width:10rem;"
            :class="{ 'text-right': $store.getters['isRtl'] }"
        >
            <template #body="slotProps">
                <span class="p-column-title">Image</span>
                <img
                    :src="slotProps.data.image_path"
                    :alt="slotProps.data.image"
                    class="shadow-2"
                    width="100"
                />
            </template>
        </Column>

        <Column
            field="color"
            header="Color"
            :sortable="true"
            headerStyle="width:14%; min-width:14rem;"
            :class="{ 'text-right': $store.getters['isRtl'] }"
        >
            <template #body="slotProps">
                <span class="p-column-title">color</span>
                {{ slotProps.data.color }}
            </template>
        </Column>

        <Column
            field="usersCount"
            header="Users Count"
            :sortable="true"
            headerStyle="width:14%; min-width:14rem;"
            :class="{ 'text-right': $store.getters['isRtl'] }"
        >
            <template #body="slotProps">
                <span class="p-column-title">users</span>
                {{ slotProps.data.users_count }}
            </template>
        </Column>

         <Column
            field="score"
            header="Score"
            :sortable="true"
            headerStyle="width:14%; min-width:14rem;"
            :class="{ 'text-right': $store.getters['isRtl'] }"
        >
            <template #body="slotProps">
                <span class="p-column-title">score</span>
                {{ slotProps.data.score }}
            </template>
        </Column>

        <Column
            field="action"
            header="Actions"
            headerStyle="min-width:10rem;display: flex; justify-content: center;"
            class="text-center"
        >
            <template #body="slotProps">
                <Button
                    icon="pi pi-eye"
                    class="p-button-rounded p-button-secondary mx-2"
                    @click="showTeam(slotProps.data)"
                />
                <Button
                    icon="pi pi-pencil"
                    class="p-button-rounded p-button-success mx-2"
                    @click="editTeam(slotProps.data)"
                />
                <Button
                    icon="pi pi-trash"
                    class="p-button-rounded p-button-warning mx-2"
                    @click="confirmDeleteTeam(slotProps.data)"
                />
            </template>
        </Column>
    </DataTable>
    <Dialog
        v-model:visible="deleteTeamDialog"
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
                >Are you sure you want to delete <b>{{ team.name }}</b
                >?</span
            >
        </div>
        <template #footer>
            <Button
                label="No"
                icon="pi pi-times"
                class="p-button-text"
                @click="deleteTeamDialog = false"
            />
            <Button
                label="Yes"
                icon="pi pi-check"
                class="p-button-text"
                @click="deleteTeam"
            />
        </template>
    </Dialog>
</template>

<script>
import { FilterMatchMode } from "primevue/api";
import { useToast } from "primevue/usetoast";

export default {
    props: {
        currentTeams: {
            type: Array,
            required: true,
        },
    }, //end of props

    emits: ["selectTeams", "deleteTeam", "editTeam", "showTeam"],

    data() {
        return {
            toast: null,
            loading: false,
            teamDialog: false,
            deleteTeamDialog: false,
            team: {},
            teams: this.currentTeams,
            selectedTeams: null,
            filters: {
                global: { value: null, matchMode: FilterMatchMode.CONTAINS },
            },
        };
    }, //end of data

    watch: {
        selectedTeams(val) {
            this.$emit("selectTeams", val);
        },
    }, //end of watch

    beforeMount() {
        this.initFilters();
        this.toast = useToast();
    }, //end of beforeMount

    methods: {
        confirmDeleteTeam(team) {
            this.team = team;
            this.deleteTeamDialog = true;
        }, //end of confirmDeleteTeam

        deleteTeam() {
            this.loading = true;
            axios
                .delete("/api/admin/teams/" + this.team.id)
                .then((response) => {
                    this.toast.add({
                        severity: "success",
                        summary: "Successful",
                        detail: response.data.message,
                        life: 3000,
                    });
                    this.$emit("deleteTeam");
                    this.deleteTeamDialog = false;
                    this.team = {};
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
                    this.deleteTeamDialog = false;
                });
        }, //end of deleteTeam

        initFilters() {
            this.filters = {
                global: { value: null, matchMode: FilterMatchMode.CONTAINS },
            };
        }, //end of initFilters

        exportCSV() {
            this.$refs.dt.exportCSV();
        }, //end of exportCSV

        editTeam(team) {
            this.$emit("editTeam", team);
        }, //end of editTeam

        showTeam(teamId) {
            this.$emit("showTeam", teamId);
        }, //end of showTeam
    }, //end of methods
};
</script>

<style scoped lang="scss">
@import "../../../../assets/demo/styles/badges.scss";
</style>

<style lang="scss">
.text-right {
    .p-datatable {
        .p-column-header-content {
            display: flex;
            gap: 0.5rem;
        }
    }
    table {
        direction: rtl;
    }
}
</style>
