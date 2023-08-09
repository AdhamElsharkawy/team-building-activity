<template>
    <Dialog
        v-model:visible="teamDialog"
        :style="{ width: '450px' }"
        header="Edit Team"
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

        <div class="field">
            <label for="name" :class="[{ 'float-right': $store.getters.isRtl }]"
                >Name</label
            >
            <InputText
                id="name"
                v-model.trim="team.name"
                required="true"
                autofocus
                type="text"
                :class="[
                    { 'p-invalid': submitted && !team.name },
                    { 'text-right': $store.getters.isRtl },
                ]"
            />
            <small class="p-invalid" v-if="submitted && !team.name">
                Name Is Required
            </small>
        </div>

        <div class="field">
            <label
                for="color"
                :class="[{ 'float-right': $store.getters.isRtl }]"
                >Color</label
            >
            <InputText
                id="color"
                v-model.trim="team.color"
                required="true"
                type="color"
                :class="[
                    { 'p-invalid': submitted && !team.color },
                    { 'text-right': $store.getters.isRtl },
                ]"
            />
            <small class="p-invalid" v-if="submitted && !team.color">
                Color Is Required
            </small>
        </div>

        <div class="field">
            <label
                for="selectUsers"
                :class="[{ 'float-right': $store.getters.isRtl }]"
                >Select Users</label
            >

            <MultiSelect
                v-model="selectedUsers"
                display="chip"
                :options="users"
                optionLabel="name"
                placeholder="Select Users"
                class="w-full"
            />
        </div>

        <template #footer>
            <div
                :class="{
                    'flex flex-row-reverse float-left': $store.getters.isRtl,
                }"
            >
                <Button
                    label="Cancel"
                    icon="pi pi-times"
                    class="p-button-text"
                    @click="hideDialog"
                />
                <Button
                    label="Submit"
                    icon="pi pi-check"
                    class="p-button-text"
                    @click="updateTeam"
                />
            </div>
        </template>
    </Dialog>
</template>

<script>
import { useToast } from "primevue/usetoast";

export default {
    data() {
        return {
            team: {},
            selectedUsers: null,
            users: [],
            teamDialog: false,
            submitted: false,
        };
    }, //end of data

    methods: {
        updateTeam() {
            this.submitted = true;
            if (this.team.name && this.team.name.trim() && this.team.color && this.selectedUsers) {
                this.loading = true;
                const formData = new FormData();
                formData.append("name", this.team.name);
                formData.append("color", this.team.color);
                for (let i = 0; i < this.selectedUsers.length; i++) {
                    formData.append("user_ids[]", this.selectedUsers[i].id);
                }
                formData.append("_method", "PUT");

                axios
                    .post("/api/admin/teams/" + this.team.id, formData)
                    .then((response) => {
                        this.toast.add({
                            severity: "success",
                            summary: "Successful",
                            detail: response.data.message,
                            life: 3000,
                        });
                        this.hideDialog();
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
                    });
            }
        }, //end of updateTeam

        editTeam(editTeam) {
            this.team = { ...editTeam };
            this.teamDialog = true;
        }, //end of editTeam

        async openDialog(team) {
            await axios
                .get("/api/admin/teams/" + team.id + "/edit")
                .then((response) => {
                    this.users = response.data.users;
                    this.team = response.data.team;
                    this.selectedUsers = this.team.users;
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
                });
            this.teamDialog = true;
        }, //end of openDialog

        hideDialog() {
            this.team = {};
            this.teamDialog = false;
            this.submitted = false;
        }, //end of hideDialog
    }, //end of methods

    beforeMount() {
        this.toast = useToast();
    }, //end of beforeMount
};
</script>
