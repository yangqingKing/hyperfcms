<template>
  <div>
    <router-view v-show="$route.matched.length==3"></router-view>
    <PageHeaderLayout v-show="$route.matched.length==2">
      <div class="main-page-content">
        <el-row class="table-header">
            <el-button type="primary" v-if="userPermissions.indexOf('statistics_and_backup') != -1" >访问统计</el-button>
        </el-row>
      </div>
    </PageHeaderLayout>
  </div >
</template>

<script>
import PageHeaderLayout from '@/layouts/PageHeaderLayout'
import { mapGetters } from 'vuex'

export default {
  components: {
    PageHeaderLayout,
  },
  data() {
    return {

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
