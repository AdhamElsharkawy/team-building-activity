<template>
    <Toast />
    <div class="col-12">
        <div class="card">
            <h5 class="mb-5">Information</h5>
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
                        <label for="content">{{
                            $t("content")
                        }}</label>
                    </span>
                    <span class="p-float-label">
                        <Editor
                            id="content"
                            v-model="content"
                            editorStyle="height: 320px"
                            aria-required="true"
                            :modules="$store.getters.getEditorOptions.modules"
                            :class="{
                                'border rounded-lg border-red-500':
                                    submitted && !content,
                            }"
                            :placeholder="$t('content')"
                        />
                        <small
                            class="p-invalid"
                            v-if="submitted && !content"
                            >{{ $t("contentIsRequired") }}</small
                        >
                    </span>
                </div>

                <div class="field col-12 mt-4">
                    <div
                        class="flex justify-content-between"
                        :class="[{ 'flex-row-reverse': $store.getters.isRtl }]"
                    >
                        <div>
                            <Button
                                icon="pi pi-check"
                                :label="$t('submit')"
                                class="p-mt-2 m-0"
                                @click.prevent="updateInfo"
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
            content: "",
            loading: false,
            submitted: false,
        };
    }, // end of data

    methods: {
        fill() {
            this.loading = true;
            axios
                .get("/api/admin/informations")
                .then((response) => {
                    console.log(response.data.information);
                    this.title = response.data.information.title;
                    this.content = response.data.information.content;
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

        updateInfo() {
            this.submitted = true;
            if (
                this.title &&
                this.content
            ) {
                this.loading = true;
                const formData = new FormData();
                formData.append("title", this.title);
                formData.append("content", this.content);
                formData.append("_method", "PUT");
                axios
                    .post("/api/admin/informations/1", formData, {
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
        }, // end of updateInfo
    }, // end of methods

    beforeMount() {
        this.toast = useToast();
    }, // end of beforeMount

    mounted() {
        this.fill();
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
