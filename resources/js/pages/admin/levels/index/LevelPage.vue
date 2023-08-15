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
                                label="new"
                                icon="pi pi-plus"
                                class="p-button-success"
                                :class="{
                                    'mr-2': !$store.getters['isRtl'],
                                    'ml-2': $store.getters['isRtl'],
                                }"
                                @click="createNewLevel"
                            />
                            <Button
                                label="delete"
                                icon="pi pi-trash"
                                class="p-button-danger"
                                @click="confirmDeleteSelected"
                                :disabled="
                                    !selectedLevels || !selectedLevels.length
                                "
                            />
                        </div>
                    </template>

                    <template v-slot:end>
                        <Button
                            label="export"
                            icon="pi pi-upload"
                            class="p-button-help"
                            @click="exportCSV($event)"
                        />
                    </template>
                </Toolbar>

                <level-list
                    ref="listLevelComponent"
                    :currentLevels="currentLevels"
                    @selectLevels="selectLevels"
                    @editLevel="editLevel"
                    @deleteLevel="fill"
                ></level-list>

                <edit-level ref="editLevelComponent"></edit-level>

                <create-level
                    ref="createLevelComponent"
                    @levelCreated="fill"
                ></create-level>

                <Dialog
                    v-model:visible="deleteLevelsDialog"
                    :style="{ width: '450px' }"
                    header="Confirm"
                    :modal="true"
                >
                    <div class="flex align-items-center justify-content-center">
                        <i
                            class="pi pi-exclamation-triangle mr-3"
                            style="font-size: 2rem"
                        />
                        <span v-if="level"
                            >Are you sure you want to delete the selected
                            levels?</span
                        >
                    </div>
                    <template #footer>
                        <Button
                            label="No"
                            icon="pi pi-times"
                            class="p-button-text"
                            @click="deleteLevelsDialog = false"
                        />
                        <Button
                            label="Yes"
                            icon="pi pi-check"
                            class="p-button-text"
                            @click="deleteSelectedLevels"
                        />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>

<script>
import LevelList from "./LevelList.vue";
import EditLevel from "../edit/EditLevel.vue";
import CreateLevel from "../create/CreateLevel.vue";
import { useToast } from "primevue/usetoast";

export default {
    components: { LevelList, EditLevel, CreateLevel },
    data() {
        return {
            currentLevels: [],
            deleteLevelsDialog: false,
            selectedLevels: null,
            loading: false,
            isEmpty: false,
            errors: null,
        };
    }, //end of data

    methods: {
        createNewLevel() {
            this.level = {};
            this.$refs.createLevelComponent.openDialog();
        }, //end of openNew

        deleteSelectedLevels() {
            this.loading = true;
            axios
                .delete("/api/admin/levels/delete/many", {
                    data: {
                        levels: this.selectedLevels.map((val) => val.id),
                    },
                })
                .then((response) => {
                    this.currentLevels = this.currentLevels.filter(
                        (val) => !this.selectedLevels.includes(val)
                    );
                    this.selectedLevels = null;
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
                    this.deleteLevelsDialog = false;
                });
        }, //end of deleteSelectedLevels

        confirmDeleteSelected() {
            this.deleteLevelsDialog = true;
        }, //end of confirmDeleteSelected

        exportCSV() {
            this.$refs.listLevelComponent.exportCSV();
        }, //end of exportCSV

        fill() {
            this.loading = true;
            axios
                .get("/api/admin/levels")
                .then((response) => {
                    this.currentLevels = response.data.levels;
                })
                .catch((errors) => {
                    this.error = errors.response.data;
                })
                .then(() => {
                    this.loading = false;
                }); //end of axios request
        }, //end of fill function

        selectLevels(selectedLevels) {
            this.selectedLevels = selectedLevels;
        }, //end of selectLevels

        editLevel(level) {
            this.$refs.editLevelComponent.openDialog(level);
        }, //end of editLevel
    }, //end of methods

    beforeMount() {
        this.toast = useToast();
    }, //end of beforeMount

    created() {
        this.fill();
    }, //end of created
};
</script>
