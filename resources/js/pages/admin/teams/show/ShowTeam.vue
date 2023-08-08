<template>
    <Loading v-if="loading" />

    <Dialog
        v-model:visible="teamDialog"
        :header="'Team ' + team.name"
        :modal="true"
        class="p-fluid w-full m-5"
    >
        <div class="flex flex-col w-full">
            <div class="flex flex-col md:flex-row my-5">
                <img
                    :src="team.image_path"
                    :alt="team.image"
                    v-if="team.image"
                    width="150"
                    class="mt-0 mb-5 ml-2 block shadow-2"
                />
                <div class="ml-5">
                    <h3 class="text-lg font-bold">Members</h3>
                    <ul class="flex flex-row">
                        <li
                            v-for="member in team.users"
                            :key="member.id"
                            class="text-base mr-2"
                        >
                            <span class="p-2" v-if="member.role == 'captin'">
                                <Icon icon="ic:twotone-star" class="inline" />
                            </span>
                            {{ member.name }},
                        </li>
                    </ul>
                </div>
            </div>
            <div class="w-full">
                <div class="card">
                    <TabView :scrollable="true">
                        <TabPanel
                            v-for="tab in team.levels"
                            :key="tab.name"
                            :header="tab.name"
                        >
                            <div v-if="tab.type == 'score'">
                                <!-- <span>The team scored: </span> {{ tab.pivot.score }} -->
                                <div class="flex flex-col md:flex-row">
                                    <div
                                        class="flex flex-col text-center w-full"
                                    >
                                        <h2 class="text-lg text-gray-500">
                                            Score:
                                        </h2>
                                        <h3 class="text-lg font-bold">
                                            {{ tab.pivot.score }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="tab.type == 'evaluation'">
                                <!-- firts get the score from the pivot list the evaluations then list insider criteria with ther score -->
                                <div class="flex flex-col md:flex-row">
                                    <div
                                        class="flex flex-col text-center w-full"
                                    >
                                        <h2 class="text-lg text-gray-500">
                                            Score:
                                        </h2>
                                        <h3 class="text-lg font-bold">
                                            {{ tab.pivot.score }}
                                        </h3>
                                    </div>
                                </div>

                                <div
                                    class="flex flex-col md:flex-row justify-self-start"
                                >
                                    <div class="flex flex-col w-full">
                                        <h2 class="text-lg text-gray-500">
                                            Evaluations:
                                        </h2>
                                        <div class="card grid gap-5">
                                            <Card
                                                v-for="evaluation in tab.evaluations"
                                                :key="evaluation.id"
                                            >
                                                <template #title>
                                                    {{ evaluation.name }}
                                                </template>
                                                <template #content>
                                                    <Accordion :activeIndex="0">
                                                        <AccordionTab
                                                            v-for="criteria in evaluation.criteria"
                                                            :key="criteria.id"
                                                            :header="
                                                                criteria.name
                                                            "
                                                        >
                                                            <span class="block">
                                                                <span
                                                                    class="font-bold"
                                                                >
                                                                    Weight:
                                                                </span>
                                                                {{
                                                                    criteria.weight
                                                                }}
                                                                <span
                                                                    class="font-bold"
                                                                >
                                                                    {{
                                                                        criteria.type ==
                                                                        "number"
                                                                            ? "number"
                                                                            : "%"
                                                                    }}
                                                                </span>
                                                            </span>
                                                            <span class="block">
                                                                <span
                                                                    class="font-bold"
                                                                >
                                                                    Score:
                                                                </span>
                                                                {{
                                                                    criteria.score
                                                                }}
                                                            </span>
                                                            <span class="block">
                                                                <span
                                                                    class="font-bold"
                                                                >
                                                                    Calculated
                                                                    Score:
                                                                </span>
                                                                {{
                                                                    criteria.calculated_score
                                                                }}
                                                            </span>
                                                        </AccordionTab>
                                                    </Accordion>
                                                </template>
                                            </Card>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <p>{{ tab.content }}</p> -->
                        </TabPanel>
                    </TabView>
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
                    label="Close"
                    icon="pi pi-times"
                    class="p-button-text"
                    @click="hideDialog"
                />
            </div>
        </template>
    </Dialog>
</template>

<script>
export default {
    data() {
        return {
            team: {},
            loading: false,
            teamDialog: false,
            submitted: false,
        };
    },
    methods: {
        async openDialog(team) {
            this.loading = true;
            this.team = await axios.get("/api/admin/teams/" + team.id).then(
                (response) => {
                    return response.data.team;
                },
                (error) => {
                    console.log(error);
                }
            );
            this.loading = false;
            this.teamDialog = true;
        }, //end of openDialog

        hideDialog() {
            this.team = {};
            this.teamDialog = false;
            this.submitted = false;
        }, //end of hideDialog
    }, //end of methods
};
</script>

<style scoped>
@media (min-width: 640px) {
    .grid {
        display: grid !important;
        grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
    }
}

@media (min-width: 760px) {
    .grid {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    }
}
</style>
