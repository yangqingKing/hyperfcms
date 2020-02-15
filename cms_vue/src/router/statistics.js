export default {
  path: 'statistics',
  component: () => import(/* webpackChunkName: "setting-base" */ '@/pages/other/Statistics'),
  meta: {title:'统计分析'},
}