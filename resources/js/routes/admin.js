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
    {
        name: "admin.users.create",
        path: "users/add",
        component: () => import("../pages/admin/users/create/CreateUser.vue"),
        props: true,
    },
    {
        name: "admin.users.edit",
        path: "users/edit",
        component: () => import("../pages/admin/users/edit/EditUser.vue"),
        props: true,
    },
    
    // teams
    {
        name: "admin.teams",
        path: "teams",
        component: () => import("../pages/admin/teams/index/TeamPage.vue"),
        props: true,
    },
    {
        name: "admin.teams.create",
        path: "teams/add",
        component: () => import("../pages/admin/teams/create/CreateTeam.vue"),
        props: true,
    },
    {
        name: "admin.teams.edit",
        path: "teams/edit",
        component: () => import("../pages/admin/teams/edit/EditTeam.vue"),
        props: true,
    },

    //settings
    {
        name: "admin.settings",
        path: "settings",
        component: () => import("../pages/admin/settings/SettingPage.vue"),
    },
];

export default admin;
