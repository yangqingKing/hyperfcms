<template>
  <div class="statistics-browser">
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
      <div id="main" ></div>
    </el-row>
    <el-row>
      <ApeTable ref="referTable" :data="dataList" :columns="dataColumns" :loading="loadingStatus" highlight-current-row border>
        <el-table-column
          slot="second-column"
          width="64"
          label="序号">
          <template slot-scope="scope">
            <span>{{scope.$index+1}}</span>
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
      dataList: [],
      dataColumns: [
        {
          title: '来源浏览器',
          value: 'name',
        },
        {
          title: '浏览次数',
          value: 'value',
        },
        {
          title: '独立访客',
          value: 'uv',
        },
        {
          title: 'IP',
          value: 'ip',
        },
      ],
      chartBrowser: null,
      loadingStatus: false,
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
    initChart() {
      this.chartBrowser = echarts.init(document.getElementById('main'))
      let nowTime = new Date()
      let startTime = new Date(nowTime.getTime() - (1000 * 86400 * 7))

      let start = startTime.getFullYear() + '-' + (startTime.getMonth() + 1) + '-' + startTime.getDate()
      let end = nowTime.getFullYear() + '-' + (nowTime.getMonth() + 1) + '-' + nowTime.getDate()

      this.searchCondition.time_value = [start, end]
      let condition = this.handleCondition()
      this.loadChartData(condition)
    },
    async searchButton() {
      this.$refs['searchForm'].validate((valid) => {
        if (valid) {
          let condition = this.handleCondition()
          this.loadChartData(condition)
        }
      })
    },
    handleCondition() {
      let condition = {
        start_time : this.searchCondition.time_value[0],
        end_time : this.searchCondition.time_value[1]
      }
      return condition
    },
    async loadChartData(condition) {
      this.chartBrowser.clear()
      this.dataList = []
      this.loadingStatus = true
      let info = await this.$api.getBrowserDataForDay(condition)
      if(info.series){
        this.dataList = info.series
      }
      this.loadingStatus = false
      let option = {
        title: {
          text: '浏览器分析',
          subtext: '网站请求浏览器',
          top: 'top',
          left: 'center',
        },
        tooltip: {
          trigger: 'item',
        },
        legend: {
          type: 'scroll',
          orient: 'vertical',
          right: 10,
          top: 20,
          bottom: 20,
          data: info.legend,
        },
        series: [{
          data: info.series,
          center: ['40%', '50%'],
          type: 'pie',
          itemStyle: {
            emphasis: {
              shadowBlur: 10,
              shadowOffsetX: 0,
              shadowColor: 'rgba(0, 0, 0, 0.5)'
            }
          }
        }]
      }
      this.chartBrowser.setOption(option)
    }
  },

  mounted() {
    this.initChart()
  },
}
</script>

<style lang="stylus">
.statistics-browser
  #main
    width 100%
    height 500px
</style>
