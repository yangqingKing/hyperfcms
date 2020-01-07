<template>
  <div
    v-loading="loading"
    element-loading-text="玩命加载中……"
    element-loading-spinner="el-icon-loading">
    <el-table
      ref="elTable"
      :data="tableData"
      v-bind="$attrs"
      @row-click="rowClick">
        <slot name="first-column"></slot>
        <slot name="second-column"></slot>
        <el-table-column
          v-for="(v,k) in columns"
          :key="k"
          :label="v.title"
          :width="v.width">
          <template slot-scope="scope">
            <span v-if="typeof(v.value)=='string'">
              <span v-if="v.type == 'image'"> <img :src="scope.row[v.value]" :alt="scope.row[v.value]" height="40px"></span>
              <span v-else>{{ scope.row[v.value] }}</span>
            </span>
            <span v-else-if="typeof(v.value)=='object'">
              <span v-for="(v1,k1) in v.value" :key="k1">
                <span v-if="typeof(v1)=='string'" v-html="scope.row[v1]"></span>
                <span v-if="typeof(v1)=='object'" v-html="v1.lable+scope.row[v1.value]"></span>
                <br/>
              </span>
            </span>
          </template>
        </el-table-column>
        <slot/>
    </el-table>
    <el-pagination
      v-if="initPaging"
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
      :current-page="currentPage"
      :page-sizes="pageSizes"
      :page-size="pageSize"
      :layout="defaultLayout"
      :total="dataTotal"
      background>
    </el-pagination>
  </div>
</template>

<script>

export default {
  props: {
    // ------ 表格相关 ---------
    data: {
      type: Array,
      required: true
    },
    columns: {
      type: Array,
      default: () => []
    },
    loading: {
      default: true
    },
    // ------ 分页相关 ---------
    pagingData: {
      type: Object,
      default: () => {},
      // required: true
    }
  },
  data() {
    return {
      // ------ 表格相关 ---------
      currentRowId: null,
      // ---- 分页相关配置 -------
      pageSize: 10,
      currentPage: 1,
      // dataTotal: this.pagingData.total,
      // defaultLayout: 'total, sizes, prev, pager, next, jumper',
    }
  },
  computed: {
    // 表格数据
    tableData() {
      return this.data
    },
    // ---- 分页相关配置 -------
    // 初始化分页
    initPaging() {
      if (!(typeof(this.pagingData) == 'undefined')) {
        if (this.pagingData.is_show && this.pagingData.total > this.pageSize) {
          return true
        }
      }
      return false
    },
    // 每页条数，切换
    pageSizes() {
      if (!(typeof(this.pagingData.page_size) == 'undefined') && this.pagingData.page_size < 10) {
        return [this.pagingData.page_size]
      }
      return [10, 20, 50, 100]
    },
    // 数组总数
    dataTotal() {
      return this.pagingData.total
    },
    // 默认分页结构
    defaultLayout() {
      if (!(typeof(this.pagingData) == 'undefined')) {
        if (this.pagingData.layout) {
          return this.pagingData.layout
        }
      }
      return 'total, sizes, prev, pager, next, jumper'
    }
  },
  methods: {
    // ------ 表格相关 ---------
    /**
     * @description 通过数据id，转换对应的行index，并且选中
     * @author YM
     * @date 2019-01-10
     * @returns string
     */
    defaultSelectedRow(rowId) {
      if (rowId) {
        let list = this.tableData
        for (let i = 0,len=list.length; i < len; i++) {
          if (list[i].id == rowId) {
            this.$refs['elTable'].setCurrentRow(list[i])
          }
        }
      } else {
         this.$refs['elTable'].setCurrentRow()
      }
    },
    /**
     * @description 监听行的点击事件,
     * @author YM
     * @date 2019-01-10
     * @returns string
     */
    rowClick(row) {
      this.currentRowId = row.id
    },
    // ------ 分页相关 ---------
    // 获取当前分页相关信息,type类型主要处理删除删除后的情况处理
    // 解决当前页数据全部删除完空白作用
    getPagingInfo(type = 'no_del') {
      if (type == 'del' && (this.pagingData.total-1) <= (this.currentPage-1)*this.pageSize) {
        this.currentPage = this.currentPage-1>0?this.currentPage-1:1
      }
      let pagingInfo = {
        page_size: this.pageSize,
        current_page: this.currentPage
      }
      return pagingInfo
    },
    // pageSize 改变时处理
    handleSizeChange(val) {
      this.pageSize = val
      this.currentPage = 1
      this.$emit('switchPaging')
    },
    // currentPage 改变时处理
    handleCurrentChange(val) {
      this.currentPage = val
      this.$emit('switchPaging')
    }
  },
  updated() {
    this.$nextTick(function () {
      this.defaultSelectedRow(this.currentRowId)
    }) 
  },
}
</script>

<style lang="stylus" scope>
  table td
    line-height: 26px
  .el-table__body tr.current-row > td
    background-color: #91d5ff !important
  .el-button + .el-button
    margin-left 0px
    margin-right 4px
  .iconfont
    font-size 12px
  .el-pagination
    text-align right
    margin-top 24px
  .el-pager li
    font-size 14px
  .el-pagination.is-background .btn-prev, .el-pagination.is-background .btn-next, .el-pagination.is-background .el-pager li 
    border: solid 1px #f5f5f5
    background-color #ffffff
</style>
