import Vue from 'vue'
import Router from 'vue-router'
import $vuex from '@/store'
import MENU from './menu'
import PERMISSIONS from './permissions'
import ROLES from './roles'
import USER from './user'
import SETTING from './setting'
import CAROUSEL from './carousel'
import ADPOSITION from './ad_position'
import LINK from './link'
import CATEGORY from './category';

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
      MENU, // 菜单
      PERMISSIONS, // 权限
      ROLES, // 角色
      USER, // 用户
      SETTING, // 基础设置
      CAROUSEL, // 轮播图
      ADPOSITION, // 广告位
      LINK, // 友情链接
      CATEGORY, // 分类管理
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
  base : process.env.NODE_ENV === 'production' ? process.env.VUE_APP_BASE_URL : '/',
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
