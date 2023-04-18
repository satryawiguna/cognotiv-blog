import {createRouter, createWebHistory} from "vue-router";
import Login from "../views/Login.vue";
import DefaultLayout from "../components/DefaultLayout.vue";
import Register from "../views/Register.vue";
import Dashboard from "../views/Admin/Dashboard.vue";
import ListBlogCategory from "../views/Admin/BlogCategory/ListBlogCategory.vue";
import AddBlogCategory from "../views/Admin/BlogCategory/AddBlogCategory.vue";
import ListBlog from "../views/Admin/Blog/ListBlog.vue";
import AddBlog from "../views/Admin/Blog/AddBlog.vue";
import EditBlog from "../views/Admin/Blog/EditBlog.vue";
import Blog from "../views/Blog.vue";
import EditBlogCategory from "../views/Admin/BlogCategory/EditBlogCategory.vue";
import store from "../store";
import AuthLayout from "../components/AuthLayout.vue";

const routes = [
  {
    path: '/blog',
    name: 'Blog',
    component: Blog
  },
  {
    path: '/auth',
    name: 'Auth',
    redirect: '/login',
    meta: {isGuest: true},
    component: AuthLayout,
    children: [
      {
        path: '/login',
        name: 'Login',
        component: Login
      },
      {
        path: '/register',
        name: 'Register',
        component: Register
      }
    ]
  },
  {
    path: '/',
    name: 'Dashboard',
    redirect: '/dashboard',
    meta: { requiresAuth: true },
    component: DefaultLayout,
    children: [
      { path: '/dashboard', name: 'Dashboard', component: Dashboard },
      { path: '/blog-category', name: 'Blog Category', component: ListBlogCategory },
      { path: '/blog-category/add', name: 'Add Blog Category', component: AddBlogCategory },
      { path: '/blog-category/edit', name: 'Edit Blog Category', component: EditBlogCategory },
      { path: '/blog', name: 'Blog', component: ListBlog },
      { path: '/blog/add', name: 'Add Blog', component: AddBlog },
      { path: '/blog/edit', name: 'Edit Blog', component: EditBlog }
    ]
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !store.state.user.token) {
    next({ name: "Login" });
  } else if (store.state.user.token && to.meta.isGuest) {
    next({ name: "Dashboard" });
  } else {
    next();
  }
})

export default router