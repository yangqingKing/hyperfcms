<template>
  <div>
    <router-view v-show="$route.matched.length==3"></router-view>
    <PageHeaderLayout v-show="$route.matched.length==2">
      <div class="main-page-content">
        <el-row class="table-header">
          <el-col>
            <el-button type="primary" size="medium" icon="iconfont icon-tianjiacaidan2"  v-if="userPermissions.indexOf('course_create') != -1"  @click="addButton(0)"></el-button>
          </el-col>
        </el-row>
        <el-row class="table-search">
          <el-form size="medium" :inline="true" :model="searchCondition" class="demo-form-inline">
            <el-form-item label="分类">
              <el-cascader
                placeholder="选择分类"
                :props="cascaderProps"
                :options="searchCourse"
                v-model="searchCondition.selected_category"
                change-on-select
                show-all-levels
                clearable
                filterabl
              ></el-cascader>
            </el-form-item>
            <el-form-item label="标题">
              <el-input v-model="searchCondition.title" placeholder="输入标题" clearable></el-input>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="searchButton">查询</el-button>
            </el-form-item>
          </el-form>
        </el-row>
        <ApeTable ref="apeTable" :data="courseList" :columns="columns" :loading="loadingStaus" :pagingData="pagingData" @switchPaging="switchPaging" highlight-current-row>
          <el-table-column
            slot="second-column"
            width="64"
            label="序号">
            <template slot-scope="scope">
              <span>{{offset+scope.$index+1}}</span>
            </template>
          </el-table-column>
          <el-table-column
            width="240"
            label="操作">
            <template slot-scope="scope">
              <span>
                <el-button size="mini" icon="el-icon-edit" v-if="userPermissions.indexOf('course_edit') != -1"  @click="editButton(scope.row.id)"></el-button>
                <el-popover
                  v-if="userPermissions.indexOf('course_delete') != -1" 
                  placement="top"
                  width="150"
                  v-model="scope.row.visible">
                  <p>确定要删除记录吗？</p>
                  <div style="text-align: right; margin: 0;">
                    <el-button type="text" size="mini" @click="scope.row.visible=false">取消</el-button>
                    <el-button type="danger" size="mini" @click="deleteButton(scope.row.id)">确定</el-button>
                  </div>
                  <el-button slot="reference" type="danger" size="mini" icon="el-icon-delete"></el-button>
                </el-popover>
              </span>
            </template>
          </el-table-column>
        </ApeTable>
      </div>
    </PageHeaderLayout>
  </div >
</template>

<script>
import PageHeaderLayout from '@/layouts/PageHeaderLayout'
import ApeTable from '@/components/ApeTable'
import { mapGetters } from 'vuex'

export default {
  components: {
    PageHeaderLayout,
    ApeTable,
  },
  data() {
    return {
      loadingStaus: true,
      columns: [
        {
          title: '封面',
          type: 'image',
          value: 'cover_pc_url',
          width: 80
        },
        {
          title: '课程信息',
          value: [
            {lable:'ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号：',value:'id'},
            {lable:'标&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;题：',value:'title'},
            {lable:'课程类型：',value:'type_alias'},
            {lable:'连载状态：',value:'serial_alias'},
            {lable:'创建时间：',value:'created_at'},
          ]
        },
        {
          title: '课程信息',
          value: [
            {lable:'价&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;格：',value:'price'},
            {lable:'课程状态：',value:'status_alias'},
            {lable:'课程时长：',value:'duration_alias'},
            {lable:'学习人数：',value:'learning_nums'},
            {lable:'点播次数：',value:'play_nums'},
          ]
        }
      ],
      // element的cascader的props值
      cascaderProps:{
        label:'display_name',
        value:'id',
      },
      searchCourse: [],
      // 搜索条件
      searchCondition:{
        selected_category:[]
      },
      // 表格列表数据
      courseList:[],
      // 分页信息
      pagingData:{
        is_show: true,
        layout: 'total, sizes, prev, pager, next, jumper',
        total: 0
      },
      // 分页的offset,序号列使用
      offset:0,
      // 已上传文件列表
      uploadFileList:[],
      // 表单验证
      rules: {
        title: [
          {required: true, message: '输入标题', trigger: 'blur' },
        ],
        type: [
          {required: true, message: '选择类型', trigger: 'blur' },
        ],
        is_new_win: [
          {required: true, message: '选择打开发方式', trigger: 'blur' },
        ],
        is_show: [
          {required: true, message: '选择状态', trigger: 'blur' },
        ],
        image: [
          {required: true, message: '上传图片', trigger: 'blur', validator:this.apeUploaderVerify},
        ],
      },
    }
  },
  computed: {
    ...mapGetters(['userPermissions'])
  },
  watch: {
    "$route.matched" : function(n,o) {
      if (n.length === 2) {
        this.initCourseList()
      }
    }
  },
  methods: {
    // 搜索
    searchButton() {
      this.initCourseList()
    },
    // 切换页码操作
    async switchPaging() {
      this.initCourseList()
    },
    // 响应添加按钮
    async addButton() {
      this.$router.push(this.$route.path+'/create')
    },
    // 响应编辑按钮
    async editButton(id) {
      this.$router.push(this.$route.path+'/'+id+'/edit')
    },
    // 相应删除按钮
    async deleteButton(id) {
      let info = await this.$api.deleteCourse(id)
      if (info == 'ok') {
        this.$nextTick(() => {
          this.initCourseList('del')
        })
      } else {
        this.$message.error(info)
      }
      
    },
    // 初始化课程列表
    async initCourseList(type) {
      this.loadingStaus=true
      let pagingInfo = this.$refs['apeTable'].getPagingInfo(type)
      let searchCondition = this.handleSearchCondition()
      Object.assign(searchCondition,pagingInfo)
      let {list,pages}= await this.$api.getCourseList(searchCondition)
      this.courseList = []
      this.$nextTick(() => {
        this.courseList=list
      })
      this.pagingData.total = pages.total
      this.offset = pages.offset
      this.loadingStaus=false
    },
    // 处理搜索条件
    handleSearchCondition() {
      let condition = {}
      if (this.searchCondition.selected_category.length) {
        condition.category_id = this.searchCondition.selected_category[this.searchCondition.selected_category.length-1]
      }
      if (this.searchCondition.title) {
        condition.title = this.searchCondition.title
      }
      if (this.searchCondition.time_value) {
        condition.start_time = this.searchCondition.time_value[0]
        condition.end_time = this.searchCondition.time_value[1]
      }

      return condition
    },
    // 获取文章分类列表
    async getCategoryList() {
      // 获取文章分类列表
      let {list} = await this.$api.getCourseCategoryList()
      this.searchCourse = list
    },
  },
  mounted() {
    this.getCategoryList()
    this.initCourseList()
  },
}
</script>

<style lang="stylus">
  .el-button
    margin-right 4px
  .table-header
    margin-bottom 12px
  .el-input-group__prepend, .el-input-group__append
    background #ffffff
    padding 0 12px
  .el-input-group__append
    color #ffffff
    background #1890ff
  .el-popover .el-checkbox-group .el-checkbox
    margin-left 0px
    margin-right 12px
</style>
