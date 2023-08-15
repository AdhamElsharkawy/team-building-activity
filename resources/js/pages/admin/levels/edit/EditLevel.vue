<template>
    <Dialog
        v-model:visible="levelDialog"
        :style="{ width: '450px' }"
        header="Edit Level"
        :modal="true"
        class="p-fluid min-w-max"
    >
        <div class="field">
            <label for="name" :class="[{ 'float-right': $store.getters.isRtl }]"
                >Name</label
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
            <small class="p-invalid" v-if="submitted && !level.name"
                >Name Is Required</small
            >
        </div>

        <div class="field">
            <label
                for="color"
                :class="[{ 'float-right': $store.getters.isRtl }]"
                >Color</label
            >
            <InputText
                id="color"
                v-model.trim="level.color"
                required="true"
                type="color"
                :class="[
                    { 'p-invalid': submitted && !level.color },
                    { 'text-right': $store.getters.isRtl },
                ]"
            />
            <small class="p-invalid" v-if="submitted && !level.color"
                >Email Is Required
            </small>
        </div>

        <div class="field">
            <label
                for="order"
                :class="[{ 'float-right': $store.getters.isRtl }]"
                >Order</label
            >
            <InputText
                id="order"
                v-model.trim="level.order"
                required="true"
                type="number"
                :class="[{ 'text-right': $store.getters.isRtl }]"
            />
        </div>

        <div class="field">
            <label
                class="mb-3"
                :class="[{ 'float-right': $store.getters.isRtl }]"
                >Type</label
            >
            <div class="formgrid grid">
                <div class="field-radiobutton col-4">
                    <RadioButton
                        id="type1"
                        name="type"
                        value="score"
                        v-model="level.type"
                    />
                    <label for="type1">Score</label>
                </div>
                <div class="field-radiobutton col-4">
                    <RadioButton
                        id="type2"
                        name="type"
                        value="evaluation"
                        v-model="level.type"
                    />
                    <label for="type2">Evaluation</label>
                </div>
            </div>
        </div>

        <div v-if="level.type == 'evaluation'">
            <div class="field">
                <label
                    for="evaluationList"
                    :class="[{ 'float-right': $store.getters.isRtl }]"
                    >Evaluation List</label
                >

                <div
                    v-for="(evaluation, index) in level.evaluations"
                    :key="index"
                >
                    <div class="flex gap-3 mb-3">
                        <div class="flex-1">
                            <InputText
                                id="evaluationList"
                                v-model.trim="evaluation.name"
                                required="true"
                                type="text"
                                style="height: 44px"
                                placeholder="Evaluation Name"
                                :class="[
                                    {
                                        'p-invalid':
                                            submitted && !evaluation.name,
                                    },
                                    { 'text-right': $store.getters.isRtl },
                                ]"
                            />
                            <Button
                                v-if="index != 0"
                                @click="level.evaluations.splice(index, 1)"
                                icon="pi pi-trash"
                                class="p-button p-button-text p-button-outlined mt-3"
                                label="Remove Evaluation"
                                severity="danger"
                                type="button"
                            />
                        </div>
                        <div>
                            <div
                                class="field"
                                v-for="(criteria, index) in evaluation.criteria"
                                :key="index"
                            >
                                <div class="flex gap-2">
                                    <InputText
                                        id="criteriaName"
                                        v-model.trim="criteria.name"
                                        required="true"
                                        type="text"
                                        placeholder="Criteria Name"
                                        :class="[
                                            {
                                                'p-invalid':
                                                    submitted && !criteria.name,
                                            },
                                            {
                                                'text-right':
                                                    $store.getters.isRtl,
                                            },
                                        ]"
                                    />
                                    <InputText
                                        id="criteriaWeight"
                                        v-model.trim="criteria.weight"
                                        required="true"
                                        type="number"
                                        placeholder="Weight"
                                        :class="[
                                            {
                                                'p-invalid':
                                                    submitted &&
                                                    !criteria.weight,
                                            },
                                            {
                                                'text-right':
                                                    $store.getters.isRtl,
                                            },
                                        ]"
                                    />
                                    <span class="flex align-items-center"
                                        >%</span
                                    >
                                    <InputText
                                        id="criteriaOrder"
                                        v-model.trim="criteria.order"
                                        required="true"
                                        type="number"
                                        placeholder="Order"
                                        :class="[
                                            {
                                                'p-invalid':
                                                    submitted &&
                                                    !criteria.order,
                                            },
                                            {
                                                'text-right':
                                                    $store.getters.isRtl,
                                            },
                                        ]"
                                    />
                                    <Button
                                        v-if="index != 0"
                                        @click="
                                            evaluation.criteria.splice(index, 1)
                                        "
                                        icon="pi pi-trash"
                                        class="p-button p-button-text p-button-outlined px-5"
                                        severity="danger"
                                        type="button"
                                    />
                                </div>
                                <small
                                    class="p-invalid"
                                    v-if="submitted && !criteria.name"
                                    >Criteria Name Is Required</small
                                >
                            </div>
                            <Button
                                class="p-button p-button-text p-button-outlined"
                                label="Add Criteria"
                                icon="pi pi-plus"
                                type="button"
                                @click="
                                    evaluation.criteria.push({
                                        name: '',
                                        weight: '',
                                        order: '',
                                    })
                                "
                            />
                        </div>
                    </div>

                    <Divider v-if="index != level.evaluations.length - 1" />
                </div>

                <Button
                    class="p-button p-button-text p-button-outlined"
                    label="Add Evaluation"
                    icon="pi pi-plus"
                    type="button"
                    @click="
                        level.evaluations.push({
                            name: '',
                            criteria: [
                                {
                                    name: '',
                                    weight: '',
                                    order: '',
                                },
                            ],
                        })
                    "
                />
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
            level: {
                name: "",
                color: "",
                type: "",
                order: "",
                evaluations: [
                    {
                        name: "",
                        criteria: [
                            {
                                name: "",
                                weight: "",
                                order: "",
                            },
                        ],
                    },
                ],
            },
            levelDialog: false,
            submitted: false,
        };
    }, //end of data

    methods: {
        updateLevel() {
            this.submitted = true;

            if (
                this.level.name &&
                this.level.name.trim() &&
                this.level.color &&
                this.level.type &&
                this.level.order
            ) {
                this.loading = true;
                const formData = new FormData();

                for (let key in this.level) {
                    if (key != "evaluations") {
                        formData.append(key, this.level[key]);
                    } else {
                        for (let evaluation_key in this.level.evaluations) {
                            for (let criteria_key in this.level.evaluations[
                                evaluation_key
                            ].criteria) {
                                for (let criteria in this.level.evaluations[
                                    evaluation_key
                                ].criteria[criteria_key]) {
                                    formData.append(
                                        `evaluations[${evaluation_key}][criteria][${criteria_key}][${criteria}]`,
                                        this.level.evaluations[evaluation_key]
                                            .criteria[criteria_key][criteria]
                                    );
                                }
                            }
                            formData.append(
                                `evaluations[${evaluation_key}][name]`,
                                this.level.evaluations[evaluation_key].name
                            );
                            formData.append(
                                `evaluations[${evaluation_key}][id]`,
                                this.level.evaluations[evaluation_key].id
                            );
                        }
                    }
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
            this.loading = true;
            axios
                .get("/api/admin/levels/" + level.id + "/edit")
                .then((response) => {
                    this.level = { ...response.data.level };
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
            this.loading = false;
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
