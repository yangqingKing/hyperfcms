import Vue from 'vue'
import Router from 'vue-router'
import $vuex from '@/store'

// 解决vue报错vue-router.esm.js
const routerPush = Router.prototype.push
  Router.prototype.push = function push(location) {
  return routerPush.call(this, location).catch(error=> error)
}

Vue.use(Router)

// 路由详情
const routes = [
  {
    path: '/',
    component: () => import('@/layouts/BasicLayout'),
    meta: {title:'首页'},
    children: [
      {
        path: 'menu',
        component: () => import(/* webpackChunkName: "menu-list" */ '@/pages/menu/MenuList'),
        meta: {title:'菜单管理'}
      },
      {
        path: 'permissions',
        component: () => import(/* webpackChunkName: "permissions-list" */ '@/pages/permissions/PermissionsList'),
        meta: {title:'权限管理'}
      },
      {
        path: 'roles',
        component: () => import(/* webpackChunkName: "roles-list" */ '@/pages/roles/RolesList'),
        meta: {title:'角色管理'},
        children: [
          {
            path: ':role_id/manage-members',
            component: () => import(/* webpackChunkName: "manage-members" */ '@/pages/roles/ManageMembers'),
            meta: {title:'成员管理'},
          }
        ]
      },
      {
        path: 'category',
        component: () => import(/* webpackChunkName: "category-list" */ '@/pages/category/CategoryList'),
        meta: {title:'分类管理'}
      },
      {
        path: 'user',
        component: () => import(/* webpackChunkName: "user-list" */ '@/pages/user/UserList'),
        meta: {title:'用户管理'}
      },
      {
        path: 'carousel',
        component: () => import(/* webpackChunkName: "carousel-list" */ '@/pages/carousel/CarouselList'),
        meta: {title:'轮播图管理'}
      },
      {
        path: 'ad-position',
        component: () => import(/* webpackChunkName: "ad-position-list" */ '@/pages/ad_position/AdPositionList'),
        meta: {title:'广告位管理'}
      },
      {
        path: 'link',
        component: () => import(/* webpackChunkName: "link-list" */ '@/pages/link/LinkList'),
        meta: {title:'友情链接'}
      },
      {
        path: 'course',
        component: () => import(/* webpackChunkName: "course-list" */ '@/pages/course/CourseList'),
        meta: {title:'课程管理'},
        children: [
          {
            path: 'create',
            component: () => import(/* webpackChunkName: "course-create" */ '@/pages/course/CreateEdit'),
            meta: {title:'添加课程'},
          },
          {
            path: ':course_id/edit',
            component: () => import(/* webpackChunkName: "course-edit" */ '@/pages/course/CreateEdit'),
            meta: {title:'编辑课程'},
          }
        ]
      },
      {
        path: 'article',
        component: () => import(/* webpackChunkName: "article-list" */ '@/pages/article/ArticleList'),
        meta: {title:'文章管理'},
        children: [
          {
            path: 'create',
            component: () => import(/* webpackChunkName: "article-create" */ '@/pages/article/CreateEdit'),
            meta: {title:'添加文章'},
          },
          {
            path: ':article_id/edit',
            component: () => import(/* webpackChunkName: "article-edit" */ '@/pages/article/CreateEdit'),
            meta: {title:'编辑文章'},
          }
        ]
      },
      {
        path: 'atlas',
        component: () => import(/* webpackChunkName: "atlas-list" */ '@/pages/atlas/AtlasList'),
        meta: {title:'图集管理'},
      },
      {
        path: 'videos',
        component: () => import(/* webpackChunkName: "videos-list" */ '@/pages/videos/VideosList'),
        meta: {title:'视频管理'},
      },
      {
        path: 'lecturer',
        component: () => import(/* webpackChunkName: "lecturer-list" */ '@/pages/lecturer/LecturerList'),
        meta: {title:'讲师管理'}
      },
      {
        path: 'live',
        component: () => import(/* webpackChunkName: "live-list" */ '@/pages/live/LiveList'),
        meta: {title:'直播管理'}
      },
      {
        path: 'authorization',
        component: () => import(/* webpackChunkName: "authorization-list" */ '@/pages/authorization/AuthorizationList'),
        meta: {title:'授权管理'}
      },
      {
        path: 'setting',
        component: () => import(/* webpackChunkName: "setting-base" */ '@/pages/setting/SettingBase'),
        meta: {title:'系统配置'},
        children: [
          {
            path: 'site',
            component: () => import(/* webpackChunkName: "setting-site" */ '@/pages/setting/SettingSite'),
            meta: {title:'站点设置'},
          }
        ]
      },
      {
        path: 'statistics_backup',
        component: () => import(/* webpackChunkName: "setting-base" */ '@/pages/other/StatisticsBackup'),
        meta: {title:'统计与备份'},
      },
      {
        path: 'statistics',
        component: () => import(/* webpackChunkName: "setting-base" */ '@/pages/other/Statistics'),
        meta: {title:'统计分析'},
      },
      {
        path: 'backup_list',
        component: () => import(/* webpackChunkName: "setting-base" */ '@/pages/other/BackupList'),
        meta: {title:'备份管理'},
      },
      {
        path: 'exception/404',
        component: () => import(/* webpackChunkName: "exception-404" */ '@/pages/exception/404')
      }
    ]
  },
  {
    path: '/',
    component: () => import('@/layouts/PassportLayout'),
    children: [
      {
        path: 'login',
        component: () => import(/* webpackChunkName: "login" */ '@/pages/passport/Login'),
      },
    ]
  },
  {
    path:"*",
    redirect:'/exception/404',
    // component: () => import('@/pages/exception/404')
  }
]

// 实例化
const router = new Router({
  base : process.env.NODE_ENV === 'production' ? '/dzjadmin/' : '/',
  mode : 'history',
  routes
})

// 路由钩子
router.beforeEach((to, from, next) => {
  ifLogin(to, next)
  handelSiderMenu(to)
  next()
});

/**
 * @description 处理左侧菜单的选中
 * @author YM
 * @date 2019-01-10
 * @param {*} to 当前路由
 */
let handelSiderMenu =  (to) => {
  $vuex.commit('handleRoutePath', to.path)
}

/**
 * @description 处理左侧菜单的选中
 * @author YM
 * @date 2019-01-10
 * @param {*} to 当前路由
 * @param {*} next 回调函数
 */
let ifLogin = (to, next) => {
  let userInfo = JSON.parse(localStorage.getItem('user_info'))
  // 判断是否是登录页
  let idx = to.path.indexOf('login') != -1
   // 没有userInfo 且 不是login 页 => 登录页
  if (!userInfo && !idx) {
    next('/login')
  }
  // 有userinfo 且 是login 页 => 首页
  if (idx && userInfo) {
    next('/')
  }
}

export default router
