<template>
  <div class="statistics-flow">
    <el-row class="table-search">
      <el-form ref="searchForm" size="medium" :rules="searchRules" :inline="true" :model="searchCondition" class="demo-form-inline">
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
  </div>
</template>

<script>
import echarts from 'echarts'

export default {
  components: {
    echarts,
  },
  data() {
    return {
      // 表单验证
      searchRules: {
        time_value: [
          {required: true, message: '请选择时间', trigger: 'blur' },
        ],
      },
      chartFlow: null,
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
      this.chartFlow = echarts.init(document.getElementById('main'))
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
      this.chartFlow.clear()
      this.loadingStatus = true
      let info = await this.$api.getFlowDataForDay(condition)
      this.loadingStatus = false
      let option = {
        title: {
          text: '流量分析',
          subtext: '网站请求流量',
          top: 'top',
          left: 'left',
        },
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          data:['浏览次数','独立访客','IP']
        },
        xAxis: {
          type: 'category',
          data: info.x_axis
        },
        yAxis: {
          type: 'value'
        },
        series: info.series
      }
      this.chartFlow.setOption(option)
    }
  },

  mounted() {
    this.initChart()
  },
}
</script>

<style lang="stylus">
.statistics-flow
  #main
    width 100%
    height 500px
</style>
