<template>
    <Loading v-if="loading" />
    <Dialog
        v-model:visible="newUserDialog"
        :style="{ width: '450px' }"
        header="New User"
        :modal="true"
        class="p-fluid"
    >
        <div class="field text-center ">
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
                v-model.trim="user.name"
                required="true"
                autofocus
                type="text"
                :class="[
                    { 'p-invalid': submitted && !user.name },
                    { 'text-right': $store.getters.isRtl },
                ]"
            />
            <small class="p-invalid" v-if="submitted && !user.name"
                >Name Is Required</small
            >
        </div>

        <div class="field">
            <label
                class="mb-3"
                :class="[{ 'float-right': $store.getters.isRtl }]"
                >Role</label
            >
            <div class="formgrid grid">
                <div class="field-radiobutton col-4">
                    <RadioButton
                        id="role1"
                        name="role"
                        value="admin"
                        v-model="user.role"
                    />
                    <label for="role1">Admin</label>
                </div>
                <div class="field-radiobutton col-4">
                    <RadioButton
                        id="role2"
                        name="role"
                        value="member"
                        v-model="user.role"
                    />
                    <label for="role2">Member</label>
                </div>
                <div class="field-radiobutton col-4">
                    <RadioButton
                        id="role3"
                        name="role"
                        value="captin"
                        v-model="user.role"
                    />
                    <label for="role3">Captin</label>
                </div>
            </div>
        </div>

        <div class="field" v-if="user.role == 'admin'">
            <label
                for="email"
                :class="[{ 'float-right': $store.getters.isRtl }]"
                >Email</label
            >
            <InputText
                id="email"
                v-model.trim="user.email"
                required="true"
                type="email"
                :class="[
                    { 'p-invalid': submitted && !user.email },
                    { 'text-right': $store.getters.isRtl },
                ]"
            />
            <small class="p-invalid" v-if="submitted && !user.email"
                >Email Is Required
            </small>
        </div>
        
        <div class="field" v-if="user.role == 'admin'">
            <label
                for="password"
                :class="[{ 'float-right': $store.getters.isRtl }]"
                >Password</label
            >
            <InputText
                id="password"
                v-model.trim="user.password"
                required="true"
                type="password"
                :class="[
                    {
                        'p-invalid':
                            (submitted && !user.password) ||
                            (submitted &&
                                user.password_confirmation !== user.password),
                    },
                    { 'text-right': $store.getters.isRtl },
                ]"
            />
            <small class="p-invalid" v-if="submitted && !user.password">
                PasswordIsRequired
            </small>
        </div>

        <div class="field" v-if="user.role == 'admin'">
            <label
                for="password_confirmation"
                :class="[{ 'float-right': $store.getters.isRtl }]"
                >Password Confirmation</label
            >
            <InputText
                id="password_confirmation"
                v-model.trim="user.password_confirmation"
                required="true"
                type="password"
                :class="[
                    {
                        'p-invalid':
                            (submitted && !user.password) ||
                            (submitted &&
                                user.password_confirmation !== user.password),
                    },
                    { 'text-right': $store.getters.isRtl },
                ]"
            />
            <small
                class="p-invalid"
                v-if="submitted && !user.password_confirmation"
                >Password Confirmation Is Required"</small
            >
            <small
                class="p-invalid"
                v-if="submitted && user.password_confirmation !== user.password"
                >Password Confirmation Must Match Password</small
            >
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
                    @click="createUser"
                />
            </div>
        </template>
    </Dialog>
</template>
<script>
import { useToast } from "primevue/usetoast";

export default {
    emits: ["userCreated"],

    data() {
        return {
            newUserDialog: false,
            user: {
                name: "",
                email: "",
                role: "",
                password: "",
                password_confirmation: "",
                image: "",
            },
            submitted: false,
            loading: false,
        };
    }, //end of data

    beforeMount() {
        this.toast = useToast();
    },

    methods: {
        uploadImage() {
            if (!this.$refs.fileUploader.files[0]) return;
            this.user.image = this.$refs.fileUploader.files[0];
        }, //end of uploadImage
        createUser() {
            this.submitted = true;
            if (
                this.user.name &&
                this.user.name.trim()
            ) {
                this.loading = true;
                const formData = new FormData();
                for (let key in this.user) {
                    formData.append(key, this.user[key]);
                }
                axios
                    .post("/api/admin/users", formData, {
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
                        this.$emit("userCreated");
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
        }, //end of createUser

        openDialog() {
            this.newUserDialog = true;
        }, //end of openDialog

        hideDialog() {
            this.user = {};
            this.submitted = false;
            this.newUserDialog = false;
        }, //end of hideDialog
    },
};
</script>
