<template>
    <Loading v-if="loading" />
    <Dialog
        v-model:visible="newTeamDialog"
        :style="{ width: '450px' }"
        header="New Team"
        :modal="true"
        class="p-fluid"
    >
        <div class="field text-center">
            <div class="p-inputgroup flex justify-content-center">
                <div class="custom-file">
                    <FileUpload
                        mode="basic"
                        accept="image/*"
                        customUpload
                        :maxFileSize="2048000"
                        chooseLabel="Choose Image"
                        @change="uploadImage"
                        ref="fileUploader"
                        class="m-0"
                    />
                </div>
            </div>
        </div>
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
                    @click="createTeam"
                />
            </div>
        </template>
    </Dialog>
</template>
<script>
import { useToast } from "primevue/usetoast";

export default {
    emits: ["teamCreated"],

    data() {
        return {
            newTeamDialog: false,
            team: {
                image: "",
                name: "",
                color: "",
            },
            selectedUsers: null,
            users: [],
            submitted: false,
            loading: false,
        };
    }, //end of data

    beforeMount() {
        this.toast = useToast();
    }, //end of beforeMount

    mounted() {
        axios
            .get("/api/admin/teams/create")
            .then((response) => {
                this.users = response.data.users;
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
    }, //end of mounted

    watch: {
        selectedUsers() {
            console.log(this.selectedUsers);
        },
    },

    methods: {
        uploadImage() {
            if (!this.$refs.fileUploader.files[0]) return;
            this.team.image = this.$refs.fileUploader.files[0];
        }, //end of uploadImage

        createTeam() {
            console.log(this.team);
            this.submitted = true;

            if (this.selectedUsers) {
                this.team.user_ids = this.selectedUsers.map((user) => user.id);
            }

            if (
                this.team.name &&
                this.team.name.trim() &&
                this.team.color &&
                this.team.image
            ) {
                this.loading = true;
                const formData = new FormData();
                for (let key in this.team) {
                    if (key === "user_ids") {
                        this.team[key].forEach((value) => {
                            formData.append(key + "[]", value);
                        });
                    } else {
                        formData.append(key, this.team[key]);
                    }
                }
                axios
                    .post("/api/admin/teams", formData, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    })
                    .then((response) => {
                        this.toast.add({
                            severity: "success",
                            summary: "Successful",
                            detail: response.data.message,
                            life: 3000,
                        });
                        this.$emit("teamCreated");
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
        }, //end of createTeam

        openDialog() {
            this.newTeamDialog = true;
        }, //end of openDialog

        hideDialog() {
            this.team = {};
            this.submitted = false;
            this.newTeamDialog = false;
        }, //end of hideDialog
    },
};
</script>
