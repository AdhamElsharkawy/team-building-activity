<template>
    <Dialog
        v-model:visible="levelDialog"
        :style="{ width: '450px' }"
        header="Edit Level"
        :modal="true"
        class="p-fluid"
    >
        <img
            :src="level.image_path"
            :alt="level.image"
            v-if="level.image"
            width="150"
            class="mt-0 mx-auto mb-5 block shadow-2"
        />

        <div class="field text-center">
            <div class="p-inputgroup">
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
            <label
                for="name"
                :class="[{ 'float-right': $store.getters.isRtl }]"
                >name</label
            >
            <InputText
                id="name"
                v-model.trim="level.name"
                required="true"
                autofocus
                type="text"
                :class="[
                    { 'p-invalid': submitted && !level.name },
                    { 'text-right': $store.getters.isRtl },
                ]"
            />
            <small class="p-invalid" v-if="submitted && !level.name">{{
                nameIsRequired
            }}</small>
        </div>

        <div class="field">
            <label
                for="email"
                :class="[{ 'float-right': $store.getters.isRtl }]"
                >email</label
            >
            <InputText
                id="email"
                v-model.trim="level.email"
                required="true"
                type="email"
                :class="[
                    { 'p-invalid': submitted && !level.email },
                    { 'text-right': $store.getters.isRtl },
                ]"
            />
            <small class="p-invalid" v-if="submitted && !level.email">
                emailIsRequired
            </small>
        </div>

        <div class="field">
            <label
                class="mb-3"
                :class="[{ 'float-right': $store.getters.isRtl }]"
                >role</label
            >
            <div class="formgrid grid">
                <div class="field-radiobutton col-4">
                    <RadioButton
                        id="role1"
                        name="role"
                        value="admin"
                        v-model="level.role"
                    />
                    <label for="role1">admin</label>
                </div>
                <div class="field-radiobutton col-4">
                    <RadioButton
                        id="role2"
                        name="role"
                        value="member"
                        v-model="level.role"
                    />
                    <label for="role2">Member</label>
                </div>
                <div class="field-radiobutton col-4">
                    <RadioButton
                        id="role3"
                        name="role"
                        value="captin"
                        v-model="level.role"
                    />
                    <label for="role3">Captin</label>
                </div>
            </div>
        </div>

        <template #footer>
            <div
                :class="{
                    'flex flex-row-reverse float-left': $store.getters.isRtl,
                }"
            >
                <Button
                    label="cancel"
                    icon="pi pi-times"
                    class="p-button-text"
                    @click="hideDialog"
                />
                <Button
                    label="submit"
                    icon="pi pi-check"
                    class="p-button-text"
                    @click="updateLevel"
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
            level: {},
            levelDialog: false,
            submitted: false,
        };
    },
    methods: {
        uploadImage() {
            if (!this.$refs.fileUploader.files[0]) return;
            this.level.image = this.$refs.fileUploader.files[0];
        }, //end of uploadImage
        updateLevel() {
            this.submitted = true;

            if (this.level.name && this.level.name.trim() && this.level.email) {
                this.loading = true;
                const formData = new FormData();
                formData.append("name", this.level.name);
                formData.append("email", this.level.email);
                formData.append("role", this.level.role);
                if (typeof this.level.image == "object") {
                    formData.append("image", this.level.image);
                }
                formData.append("_method", "PUT");
                axios
                    .post("/api/admin/levels/" + this.level.id, formData)
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
        }, //end of updateLevel

        editLevel(editLevel) {
            this.level = { ...editLevel };
            this.levelDialog = true;
        }, //end of editLevel

        openDialog(level) {
            this.level = level;
            this.levelDialog = true;
        }, //end of openDialog

        hideDialog() {
            this.level = {};
            this.levelDialog = false;
            this.submitted = false;
        }, //end of hideDialog
    }, //end of methods

    beforeMount() {
        this.toast = useToast();
    }, //end of beforeMount
};
</script>
