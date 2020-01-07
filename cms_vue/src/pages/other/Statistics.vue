<template>
  <div>
    <router-view v-show="$route.matched.length==3"></router-view>
    <PageHeaderLayout v-show="$route.matched.length==2">
      <div class="main-page-content">
        <el-tabs type="card" v-model="panelTabs">
          <el-tab-pane name="flow" label="流量分析">
            <StatisticsFlow v-if="panelTabs == 'flow'"></StatisticsFlow>
          </el-tab-pane>
          <el-tab-pane name="referer" label="来源分析">
            <StatisticsReferer v-if="panelTabs == 'referer'"></StatisticsReferer>
          </el-tab-pane>
          <el-tab-pane name="region" label="地域分析">
            <StatisticsRegion v-if="panelTabs == 'region'"></StatisticsRegion>
          </el-tab-pane>
          <el-tab-pane name="browser" label="浏览器分析">
            <StatisticsBrowser v-if="panelTabs == 'browser'"></StatisticsBrowser>
          </el-tab-pane>
          <el-tab-pane name="platform" label="操作系统分析">
            <StatisticsPlatform v-if="panelTabs == 'platform'"></StatisticsPlatform>
          </el-tab-pane>
          <el-tab-pane name="log" label="访问日志分析">
            <StatisticsLog v-if="panelTabs == 'log'"></StatisticsLog>
          </el-tab-pane>
        </el-tabs>
      </div>
    </PageHeaderLayout>
  </div >
</template>

<script>
import PageHeaderLayout from '@/layouts/PageHeaderLayout'
import { mapGetters } from 'vuex'
import StatisticsFlow from '@/pages/other/StatisticsFlow'
import StatisticsReferer from '@/pages/other/StatisticsReferer'
import StatisticsBrowser from '@/pages/other/StatisticsBrowser'
import StatisticsPlatform from '@/pages/other/StatisticsPlatform'
import StatisticsRegion from '@/pages/other/StatisticsRegion'
import StatisticsLog from '@/pages/other/StatisticsLog'

export default {
  components: {
    PageHeaderLayout,
    StatisticsFlow,
    StatisticsReferer,
    StatisticsBrowser,
    StatisticsRegion,
    StatisticsLog,
    StatisticsPlatform
  },
  data() {
    return {
      panelTabs: 'flow',
    }
  },
  computed: {
    ...mapGetters(['userPermissions'])
  },
  watch: {

  },
  methods: {
  },

  mounted() {
  },
}
</script>

<style lang="stylus">
</style>
