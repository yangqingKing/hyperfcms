<template>
  <div class="statistics-flow">
    <el-row class="table-search">
      <el-col class="kuaijieriqi">
        <el-radio-group v-model="checkedRadio" size="medium">
          <el-radio-button label="1">今天</el-radio-button>
          <el-radio-button label="2">昨天</el-radio-button>
          <el-radio-button label="7">近7日</el-radio-button>
          <el-radio-button label="30">近30日</el-radio-button>
        </el-radio-group>
      </el-col>
      <el-col :span="14">
        <el-form ref="searchForm" size="medium" :inline="true" :model="searchCondition" class="demo-form-inline">
          <el-form-item label="选择时间">
            <el-date-picker
              v-model="searchCondition.time_value"
              type="daterange"
              align="right"
              unlink-panels
              range-separator="至"
              start-placeholder="开始日期"
              end-placeholder="结束日期"
              value-format="yyyy-MM-dd">
            </el-date-picker>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="searchButton" :loading='loadingStatus'>查询</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
    <el-row id="echarts-flow-data">
      <div id="main" ></div>
    </el-row>
  </div>
</template>

<script>
import echarts from 'echarts'
import elementResizeDetector from 'element-resize-detector'

export default {
  components: {
    echarts,
    elementResizeDetector
  },
  data() {
    return {
      checkedRadio:7,
      chartFlow: null,
      loadingStatus: false,
      searchCondition: {
        time_value: null
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
      let info = await this.$api.getStatisticsFlowData(condition)
      this.loadingStatus = false
      let option = {
        title: {
          text: '流量统计',
          top: 0,
          left: 'center',
        },
        tooltip: {
          trigger: 'axis',
          textStyle: {
            fontSize:16
          }
        },
        grid: {
          top: 80,
        },
        legend: {
          data:info.legend_data,
          top: 40,
          textStyle: {
            fontWeight:'bolder',
            fontSize:16
          }
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
    let erd = elementResizeDetector()
    let _this = this
    erd.listenTo(document.getElementById("echarts-flow-data"),function(){
      _this.chartFlow.resize()
    })
  },
}
</script>

<style lang="stylus">
  .kuaijieriqi
    width 320px
  #main
    width 100%
    height 600px
</style>
