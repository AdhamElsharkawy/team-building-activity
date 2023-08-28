<template>
    <Toast />
    <div class="col-12">
        <div class="card">
            <h5 class="mb-5">App Settings</h5>

            <div>
                <FileUpload
                    mode="basic"
                    accept="image/*"
                    customUpload
                    :maxFileSize="2048000"
                    chooseLabel="Choose Logo"
                    @change="uploadLogo"
                    ref="logoUploader"
                    class="m-0"
                />
            </div>

            <div class="field mt-5">
                <div>
                    <FileUpload
                        name="sound_1"
                        mode="basic"
                        chooseLabel="Choose Winner Sound"
                        :customUpload="true"
                        ref="file1"
                        @change="uploadAudio(1)"
                    />
                </div>
                <div class="mt-5">
                    <FileUpload
                        name="sound_2"
                        chooseLabel="Choose score tracker up Sound"
                        mode="basic"
                        :customUpload="true"
                        ref="file2"
                        @change="uploadAudio(2)"
                    />
                </div>
                <div class="mt-5">
                    <FileUpload
                        name="sound_3"
                        chooseLabel="Choose score tracker down Sound"
                        mode="basic"
                        :customUpload="true"
                        ref="file3"
                        @change="uploadAudio(3)"
                    />
                </div>
            </div>

            <div class="field mt-5">
                <label
                    for="color"
                    :class="[{ 'float-right': $store.getters.isRtl }]"
                    class="mr-4"
                    >Application Primary Color</label
                >
                <InputText
                    id="color"
                    v-model.trim="color"
                    required="true"
                    type="color"
                    class="w-full"
                    :class="[
                        { 'p-invalid': submitted && !color },
                        { 'text-right': $store.getters.isRtl },
                    ]"
                />
                <small class="p-invalid" v-if="submitted && color">
                    Color Is Required
                </small>
            </div>
            <div>
                <Button
                    icon="pi pi-check"
                    label="Submit Settings"
                    class="p-mt-2 m-0"
                    @click.prevent="updateSettings"
                    :disabled="loading"
                />
            </div>
        </div>
        <div class="card">
            <h5 class="mb-5">{{ $t("Seos") }}</h5>
            <Loading v-if="loading" />
            <div v-else class="p-fluid flex flex-col">
                <div class="field">
                    <span class="p-float-label">
                        <InputText
                            type="text"
                            id="title"
                            v-model="title"
                            required="true"
                            :placeholder="$t('title')"
                            :class="{ 'p-invalid': submitted && !title }"
                        />
                        <label for="title">{{ $t("title") }}</label>
                    </span>
                    <small class="p-invalid" v-if="submitted && !title">{{
                        $t("titleIsRequired")
                    }}</small>
                </div>

                <div class="field">
                    <span class="">
                        <label for="description">{{ $t("description") }}</label>
                    </span>
                    <span class="p-float-label">
                        <Editor
                            id="description"
                            v-model="description"
                            editorStyle="height: 320px"
                            aria-required="true"
                            :modules="$store.getters.getEditorOptions.modules"
                            :class="{
                                'border rounded-lg border-red-500':
                                    submitted && !description,
                            }"
                            :placeholder="$t('description')"
                        />
                        <small
                            class="p-invalid"
                            v-if="submitted && !description"
                            >{{ $t("descriptionIsRequired") }}</small
                        >
                    </span>
                </div>

                <div class="field col-12 mt-4">
                    <span class="p-float-label">
                        <Chips
                            inputId="chips"
                            v-model="keywords"
                            :class="[
                                {
                                    'p-invalid':
                                        submitted && keywords.length === 0,
                                },
                            ]"
                        ></Chips>
                        <label for="chips">Chips</label>
                    </span>
                    <small
                        class="p-invalid"
                        v-if="submitted && keywords.length === 0"
                        >{{ $t("keywordsAreRequired") }}</small
                    >
                </div>

                <div class="field col-12 mt-4">
                    <div
                        class="flex justify-content-between"
                        :class="[{ 'flex-row-reverse': $store.getters.isRtl }]"
                    >
                        <div>
                            <FileUpload
                                mode="basic"
                                accept="image/*"
                                customUpload
                                :maxFileSize="2048000"
                                :chooseLabel="$t('chooseImage')"
                                @change="uploadImage"
                                ref="fileUploader"
                                class="m-0"
                            />
                        </div>
                        <div>
                            <Button
                                icon="pi pi-check"
                                :label="$t('submit')"
                                class="p-mt-2 m-0"
                                @click.prevent="updateSeo"
                                :disabled="loading"
                            />
                        </div>
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
            title: "",
            description: "",
            keywords: [],
            image: null,
            loading: false,
            submitted: false,
            color: "",
            audio1: null,
            audio2: null,
            audio3: null,
            logoImage: null,
        };
    }, // end of data

    methods: {
        fill() {
            this.loading = true;
            axios
                .get("/api/admin/seos")
                .then((response) => {
                    this.title = response.data.seo.title;
                    this.description = response.data.seo.description;
                    this.keywords = response.data.seo.keywords.split(",");
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
        fillSettings() {
            this.loading = true;
            axios
                .get("/api/admin/settings")
                .then((response) => {
                    console.log(response.data);
                    this.color = response.data.settings.color;
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
        formatKeywords() {
            let stringedKeywords = "";
            this.keywords.forEach((keyword) => {
                stringedKeywords += keyword + ",";
            });
            stringedKeywords = stringedKeywords.slice(0, -1);
            return stringedKeywords;
        }, // end of formatKeywords

        uploadImage() {
            if (!this.$refs.fileUploader.files[0]) return;
            this.image = this.$refs.fileUploader.files[0];
        }, // end of onUpload
        uploadLogo() {
            if (!this.$refs.logoUploader.files[0]) return;
            this.logoImage = this.$refs.logoUploader.files[0];
        }, // end of onUpload
        updateSeo() {
            this.submitted = true;
            if (this.title && this.description && this.keywords.length > 0) {
                this.loading = true;
                const formData = new FormData();
                formData.append("title", this.title);
                formData.append("description", this.description);
                formData.append("keywords", this.formatKeywords());
                if (this.image) formData.append("image", this.image);
                formData.append("_method", "PUT");
                axios
                    .post("/api/admin/seos/1", formData, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    })
                    .then((response) => {
                        this.toast.add({
                            severity: "success",
                            summary: "Success",
                            detail: response.data.message,
                            life: 3000,
                        });
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
                        this.submitted = false;
                    });
            }
        }, // end of updateSeo
        uploadAudio(fileNum) {
            if (fileNum === 1) {
                if (!this.$refs.file1.files[0]) return;
                this.audio1 = this.$refs.file1.files[0];
            }
            if (fileNum === 2) {
                if (!this.$refs.file2.files[0]) return;
                this.audio2 = this.$refs.file2.files[0];
            }
            if (fileNum === 3) {
                if (!this.$refs.file3.files[0]) return;
                this.audio3 = this.$refs.file3.files[0];
            }
        }, // end of onUpload
        updateSettings() {
            this.submitted = true;
            if (this.color) {
                this.loading = true;
                const formData = new FormData();
                formData.append("color", this.color);
                if (this.logoImage) formData.append("image", this.logoImage);
                this.audio1 ? formData.append("sound_1", this.audio1) : null ;
                this.audio2 ? formData.append("sound_2", this.audio2) : null ;
                this.audio3 ? formData.append("sound_3", this.audio3) : null ;
                formData.append("_method", "PUT");
                axios
                    .post("/api/admin/settings/1", formData, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    })
                    .then((response) => {
                        this.toast.add({
                            severity: "success",
                            summary: "Success",
                            detail: response.data.message,
                            life: 3000,
                        });
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
                        this.submitted = false;
                    });
            }
        },
    }, // end of methods

    beforeMount() {
        this.toast = useToast();
    }, // end of beforeMount

    mounted() {
        this.fill();
        this.fillSettings();
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
