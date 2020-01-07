<template>
  <div>
    <PageHeaderLayout>
      <div class="main-page-content">
        <ApeTable ref="apeTable" @switchPaging="switchPaging" :pagingData="pagingData" :data="backupList" :columns="backupColumns" :loading="loadingStaus" highlight-current-row border>
          <el-table-column
            slot="second-column"
            width="64"
            label="序号">
            <template slot-scope="scope">
              <span>{{offset+scope.$index+1}}</span>
            </template>
          </el-table-column>
          <el-table-column label="操作" width="80">
            <template slot-scope="scope">
              <a :href="scope.row.save_file" target="_blank">下载</a>
            </template>
          </el-table-column>
        </ApeTable>
      </div >
    </PageHeaderLayout>
  </div >
</template>

<script>
import PageHeaderLayout from '@/layouts/PageHeaderLayout'
import ApeTable from '@/components/ApeTable'
import { mapGetters } from 'vuex'

export default {
  components: {
    ApeTable,
    PageHeaderLayout,
  },
  data() {
    return {
      backupList: [],
      // 分页信息
      pagingData:{
        is_show: true,
        layout: 'total, sizes, prev, pager, next, jumper',
        page_size: 10,
        total: 0
      },
      backupColumns: [
        {
          title: '备份文件名',
          value: 'file_name',
        },
        {
          title: '备份信息',
          value: 'info',
        },
      ],
      loadingStaus: false
    }
  },
  computed: {
    ...mapGetters(['userPermissions'])
  },
  watch: {

  },
  methods: {
    async initBackupList() {
      this.loadingStaus=true
      let pagingInfo = this.$refs['apeTable'].getPagingInfo()
      let data = await this.$api.getBackupList(pagingInfo)
      this.backupList = data.list
      this.pagingData.total = data.pages.count
      this.offset = data.pages.offset
      this.loadingStaus = false
    },
    download(row) {
      location.href = row.save_file
    },
    // 切换页码操作
    async switchPaging() {
      this.initBackupList()
    },
  },

  async mounted() {
    this.initBackupList()
  },
}
</script>

<style lang="stylus">
</style>
