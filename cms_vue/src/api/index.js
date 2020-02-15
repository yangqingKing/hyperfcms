import passport from './passport'
import common from './common'
import statistics from './statistics'
import menu from './menu'
import permissions from './permissions'
import roles from './roles'
import user from './user'
import setting from './setting'
import carousel from './carousel'
import adposition from './ad_position'
import link from './link'
import category from './category'
import article from './article'
import live from './live'
import lecturer from './lecturer'
import course from './course';
import authorization from './authorization'
import attachment from './attachment'


export default {
  // 通行证
  ...passport,
  // 通用
  ...common,
  // 统计数据
  ...statistics,
  // 菜单
  ...menu,
  // 权限
  ...permissions,
  // 角色
  ...roles,
  // 用户
  ...user,
  // 基础配置
  ...setting,
  // 轮播图
  ...carousel,
  // 广告位
  ...adposition,
  // 友情链接
  ...link,
  // 分类管理
  ...category,
  // 文章管理
  ...article,
  // 直播管理
  ...live,
  // 讲师管理
  ...lecturer,
  // 课程管理
  ...course,
  // 授权管理
  ...authorization,
  // 附件管理
  ...attachment
}
