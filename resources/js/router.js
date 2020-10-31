import Vue from "vue";
import Router from "vue-router";

import overview from "./components/dashboard/overview/overview.vue";
import user from "./components/dashboard/user/Home.vue"
import userlist from "./components/dashboard/user/userlist.vue"
import type from "./components/dashboard/user/Type.vue"

import pages from "./components/dashboard/pages/Home.vue"
import categoryList from "./components/dashboard/pages/categoryList.vue";
import pageslist from "./components/dashboard/pages/pagesList.vue";
import add from "./components/dashboard/pages/AddPage.vue";



import privilige from "./components/dashboard/privilige/Home.vue"
import priviligelist from "./components/dashboard/privilige/PriviligeList.vue";
import permission from "./components/dashboard/privilige/Permission.vue";


import profile from "./components/dashboard/user/profile.vue"



let onlyAdmin = [1];
let allUser = [1, 2];
let onlyModerator = [2];

Vue.use(Router);
export default new Router({
    mode: "history",
    routes: [
        {
            path: "/home",
            name: "home",
            component: overview,
            meta: {
                icon: "dashboard",
                title: "Dashboard",
                type: allUser,
                status: true
            },
            children: [
                {
                    path: "overview",
                    name: "overview",
                    component: overview,
                    meta: {
                        icon: "dashboard",
                        title: "Overview",
                        type: allUser,
                        status: true
                    }
                }
            ]
        },

        {
            path: "/user",
            name: "user",
            component: user,
            meta: {
                icon: "supervisor_account",
                title: "User",
                type: onlyAdmin,
                status: true
            },
            children: [
                {
                    path: "userlist",
                    name: "userlist",
                    component: userlist,
                    meta: {
                        icon: "class",
                        title: "List",
                        type: onlyAdmin,
                        status: true
                    }
                },
                {
                    path: "type",
                    name: "type",
                    component: type,
                    meta: {
                        icon: "class",
                        title: "Type",
                        type: onlyAdmin,
                        status: true
                    }
                },
            ]
        },

        {
            path: "/pages",
            name: "pages",
            component: pages,
            meta: {
                icon: "pages",
                title: "Pages",
                type: allUser,
                status: true
            },
            children: [
                {
                    path: "category",
                    name: "categoryList",
                    component: categoryList,
                    meta: {
                        icon: "class",
                        title: "Category",
                        type: allUser,
                        status: true
                    }
                },
                {
                    path: "pageslist",
                    name: "pageslist",
                    component: pageslist,
                    meta: {
                        icon: "class",
                        title: "List",
                        type: allUser,
                        status: true
                    }
                },
                {
                    path: "add",
                    name: "add",
                    component: add,
                    meta: {
                        icon: "class",
                        title: "Add",
                        type: allUser,
                        status: true
                    }
                },
                {
                    path: "edit/:id",
                    name: "edit",
                    component: add,
                    meta: {
                        icon: "class",
                        title: "Edit",
                        type: allUser,
                        status: false
                    }
                },
            ]
        },

        {
            path: "/privilige",
            name: "privilige",
            component: privilige,
            meta: {
                icon: "lock_open",
                title: "Priviliges",
                type: onlyAdmin,
                status: true
            },
            children: [
                {
                    path: "priviligelist",
                    name: "priviligelist",
                    component: priviligelist,
                    meta: {
                        icon: "class",
                        title: "List",
                        type: onlyAdmin,
                        status: true
                    }
                },
                {
                    path: "permission/:id",
                    name: "permission",
                    component: permission,
                    meta: {
                        icon: "class",
                        title: "Permission",
                        type: onlyAdmin,
                        status: false
                    }
                },
            ]
        },

       
        
        {
            path: "/setting",
            name: "setting",
            component: user,
            meta: {
                icon: "face",
                title: "Setting",
                type: allUser,
                status: true
            },
            children: [
                {
                    path: "profile",
                    name: "profile",
                    component: profile,
                    meta: {
                        icon: "dashboard",
                        title: "Profile",
                        type: allUser,
                        status: true
                    }
                },
                
            ]
        }
    ]
});

// let userType = window.authUser.userType
// router.beforeEach((to, from, next) => {

//     var flag=0;
//     if(to.meta){
//         let allowed = to.meta.type
//         for(let a of allowed){
//             if(a==userType){                
//                 flag=1;
//             }
//         }
//     }
//     if(flag==1)
//     next();
//     else
//     return;
  
//   });