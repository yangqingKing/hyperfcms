<template>
  <div class="statistics-log">
    <el-row class="table-search">
      <el-form ref="searchForm" :rules="searchRules" size="medium" :inline="true" :model="searchCondition" class="demo-form-inline">
        <el-form-item label="选择时间" prop="time_value">
          <el-date-picker
            v-model="searchCondition.time_value"
            type="daterange"
            align="right"
            unlink-panels
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            value-format="yyyy-MM-dd"
            :picker-options="pickerOptions"
            >
          </el-date-picker>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="searchButton" :loading='loadingStatus'>查询</el-button>
        </el-form-item>
      </el-form>
    </el-row>
    <el-row>
      <ApeTable ref="dataTable" :pagingData="pagingData" @switchPaging="switchPaging" :data="dataList" :columns="dataColumns" :loading="loadingStatus" highlight-current-row border>
        <el-table-column
          slot="second-column"
          width="64"
          label="序号">
          <template slot-scope="scope">
            <span>{{offset + scope.$index+1}}</span>
          </template>
        </el-table-column>
      </ApeTable>
    </el-row>
  </div>
</template>

<script>
import echarts from 'echarts'
import ApeTable from '@/components/ApeTable'

export default {
  components: {
    echarts,
    ApeTable,
  },
  data() {
    return {
      // 表单验证
      searchRules: {
        time_value: [
          {required: true, message: '请选择时间', trigger: 'blur' },
        ],
      },
      offset: 0,
      dataList: [],
      dataColumns: [
        {
          title: '请求地址',
          value: 'url',
        },
        {
          title: '浏览次数',
          value: 'value',
          width: '150px'
        },
        {
          title: '独立访客',
          value: 'uv',
          width: '150px'
        },
        {
          title: 'IP',
          value: 'ip',
          width: '150px'
        },
      ],
      loadingStatus: false,
      // 分页信息
      pagingData:{
        is_show: true,
        layout: 'total, sizes, prev, pager, next, jumper',
        total: 0
      },
      searchCondition: {
        time_value: null
      },
      pickerOptions: {
        shortcuts: [
          {
            text: '今天',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime());
              picker.$emit('pick', [start, end]);
            }
          },
          {
            text: '昨天',
            onClick(picker) {
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24);
              picker.$emit('pick', [start, start]);
            }
          },
          {
          text: '最近一周',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '最近一个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '最近三个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            picker.$emit('pick', [start, end]);
          }
        }]
      },
    }
  },
  computed: {
  },
  watch: {

  },
  methods: {
    initData() {
      let nowTime = new Date()
      let startTime = new Date(nowTime.getTime() - (1000 * 86400 * 7))

      let start = startTime.getFullYear() + '-' + (startTime.getMonth() + 1) + '-' + startTime.getDate()
      let end = nowTime.getFullYear() + '-' + (nowTime.getMonth() + 1) + '-' + nowTime.getDate()

      this.searchCondition.time_value = [start, end]
      let condition = this.handleCondition()
      this.loadTableData(condition)
    },
    async searchButton() {
      this.$refs['searchForm'].validate((valid) => {
        if (valid) {
          this.$refs['dataTable'].currentPage = 1
          let condition = this.handleCondition()
          this.loadTableData(condition)
        }
      })
    },
    handleCondition() {
      let condition = {
        start_time : this.searchCondition.time_value[0],
        end_time : this.searchCondition.time_value[1]
      }
      let pagingInfo = this.$refs['dataTable'].getPagingInfo()
      Object.assign(condition, pagingInfo)
      return condition
    },
    async loadTableData(condition) {
      this.dataList = []
      this.loadingStatus = true
      let info = await this.$api.getUrlData(condition)
      this.loadingStatus = false
      this.dataList = info.list
      this.pagingData.total = info.total
      let offset = (this.$refs['dataTable'].currentPage - 1) * this.$refs['dataTable'].pageSize
      this.offset = offset
    },
    // 切换页码操作
    async switchPaging() {
      let condition = this.handleCondition()
      this.loadTableData(condition)
    },
  },

  mounted() {
    this.initData()
  },
}
</script>

<style lang="stylus">
.statistics-region
  #main
    width 100%
    height 500px
</style>
