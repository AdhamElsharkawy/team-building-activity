const admin = [
    ////////////////////////////////// admin routes ////////////////////////////
    //dashboard
    {
        path: "dashboard",
        name: "admin.dashboard",
        component: () => import("../pages/admin/dashboard/DashboardPage.vue"),
    },

    // users
    {
        name: "admin.users",
        path: "users",
        component: () => import("../pages/admin/users/index/UserPage.vue"),
        props: true,
    },

    // teams
    {
        name: "admin.teams",
        path: "teams",
        component: () => import("../pages/admin/teams/index/TeamPage.vue"),
        props: true,
    },

    // levels
    {
        name: "admin.levels",
        path: "levels",
        component: () => import("../pages/admin/levels/index/LevelPage.vue"),
        props: true,
    },

    //settings
    {
        name: "admin.settings",
        path: "settings",
        component: () => import("../pages/admin/settings/SettingPage.vue"),
    },
    // informations
    {
        name: "admin.informations",
        path: "informations",
        component: () =>
            import("../pages/admin/informations/InfoPage.vue"),
    },
];

export default admin;
