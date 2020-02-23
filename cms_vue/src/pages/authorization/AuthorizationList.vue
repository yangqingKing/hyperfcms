<template>
  <div>
    <PageHeaderLayout>
      <div class="main-page-content">
        <el-row class="table-header">
          <el-col>
            <el-button type="primary" size="medium" icon="iconfont"  v-if="userPermissions.indexOf('authorization_create') != -1"  @click="addButton(0)">添加</el-button>
          </el-col>
        </el-row>
        <el-row class="table-search">
          <el-form size="medium" :inline="true" :model="searchCondition" class="demo-form-inline">
            <el-form-item label="账号">
              <el-input v-model="searchCondition.user_mobile" placeholder="输入用户手机号" clearable></el-input>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="searchButton">查询</el-button>
            </el-form-item>
          </el-form>
        </el-row>
        <ApeTable ref="apeTable" :data="authorizationList" :columns="columns" :loading="loadingStaus" :pagingData="pagingData" @switchPaging="switchPaging" highlight-current-row>
          <el-table-column
            slot="second-column"
            width="64"
            label="序号">
            <template slot-scope="scope">
              <span>{{offset+scope.$index+1}}</span>
            </template>
          </el-table-column>
          <el-table-column
            label="操作">
            <template slot-scope="scope">
              <span>
                <el-popover
                  v-if="userPermissions.indexOf('authorization_cancel') != -1 && scope.row.authorization_status === 1" 
                  placement="top"
                  width="150"
                  v-model="scope.row.visible">
                  <p>确定取消授权吗？</p>
                  <div style="text-align: right; margin: 0;">
                    <el-button type="text" size="mini" @click="scope.row.visible=false">取消</el-button>
                    <el-button type="danger" size="mini" @click="cancelAuthorizationButton(scope.row.id)">确定</el-button>
                  </div>
                  <el-button slot="reference" type="danger" size="mini" icon="iconfont icon-quxiao5"></el-button>
                </el-popover>
                <el-popover
                  v-if="userPermissions.indexOf('authorization_recover') != -1  && scope.row.authorization_status === 2" 
                  placement="top"
                  width="150"
                  v-model="scope.row.visible1">
                  <p>确定恢复授权吗？</p>
                  <div style="text-align: right; margin: 0;">
                    <el-button type="text" size="mini" @click="scope.row.visible1=false">取消</el-button>
                    <el-button type="primary" size="mini" @click="recoverAuthorizationButton(scope.row.id)">确定</el-button>
                  </div>
                  <el-button slot="reference" type="primary" size="mini" icon="iconfont icon-huifu2"></el-button>
                </el-popover>
              </span>
            </template>
          </el-table-column>
        </ApeTable>
      </div>
    </PageHeaderLayout>
    <ModalDialog :dialogData="dialogData" @dialogConfirm="dialogConfirm" @dialogClose="dialogClose">
      <template slot="content">
        <el-form :model="formData" :rules="rules" ref="authorizationForm" label-position="right" label-width="64px">
          <el-row>
            <el-col :span="22">
              <el-form-item label="用户" prop="mobile_alias">
                <el-autocomplete
                  class="authorization-search-user"
                  v-model="formData.mobile_alias"
                  :fetch-suggestions="searchUser"
                  placeholder="输入手机号检索用户"
                  @select="handleUserSelect"
                  @clear="initMobileVerify"
                  clearable>
                </el-autocomplete>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col :span="22">
              <el-form-item label="课程" prop="course_id">
                <el-autocomplete
                  class="search-course"
                  v-model="formData.course_title"
                  :fetch-suggestions="searchCourse"
                  placeholder="输入手机号检索用户"
                  @select="handleCourseSelect"
                  @clear="initCourseVerify"
                  clearable>
                </el-autocomplete>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col :span="22">
              <el-form-item label="备注" prop="remarks">
                <el-input type="textarea" :rows="6" v-model="formData.remarks"></el-input>
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>     
      </template>
    </ModalDialog>
  </div >
</template>

<script>
import PageHeaderLayout from '@/layouts/PageHeaderLayout'
import ApeTable from '@/components/ApeTable'
import ModalDialog from '@/components/ModalDialog'
import { mapGetters } from 'vuex'

export default {
  components: {
    PageHeaderLayout,
    ApeTable,
    ModalDialog
  },
  data() {
    return {
      // 搜索条件
      searchCondition:{},
      loadingStaus: true,
      columns: [
        {
          title: '用户信息',
          value: [
            {label:'账号：',value:'user_mobile'},
            {label:'昵称：',value:'user_nickname'},
          ],
          width: 240
        },
        {
          title: '商品信息',
          value: [
            {label:'类型：',value:'goods_type'},
            {label:'名称：',value:'goods_name'},
          ]
        },
        {
          title: '授权信息',
          value: [
            {label:'类型：',value:'authorization_type_alias'},
            {label:'状态：',value:'authorization_staus_alias'},
          ]
        },
        {
          title: '操作人',
          value: [
            {label:'操作人：',value:'handle_user'},
            {label:'',value:'handle_time'},
          ],
          width: 240
        }
      ],
      // 表格列表数据
      authorizationList:[],
      // 分页信息
      pagingData:{
        is_show: true,
        layout: 'total, sizes, prev, pager, next, jumper',
        total: 0
      },
      // 分页的offset,序号列使用
      offset:0,
      // 表单结构
      formData: {},
      // 已上传文件列表
      uploadFileList:[],
      // 表单验证
      rules: {
        title: [
          {required: true, message: '输入标题', trigger: 'blur'},
        ],
        course_id: [
          {required: true, message: '选择课程', trigger: 'blur', validator:this.searchCourseVerify},
        ],
        mobile_alias: [
          {required: true, message: '选择讲师', trigger: 'blur', validator:this.searchUserVerify},
        ],
      },
      // 授权弹窗数据
      dialogData:{
        visible: false,
        title: '',
        width: '480px',
        loading: true,
        modal: true
      },
      
    }
  },
  computed: {
    ...mapGetters(['userPermissions'])
  },
  methods: {
    // 搜索
    searchButton() {
      this.initAuthorizationList()
    },
    // 切换页码操作
    async switchPaging() {
      this.initAuthorizationList()
    },
    // 响应添加按钮
    async addButton() {
      this.dialogData.loading_text = '玩命加载中……'
      this.dialogData.visible = true
      this.dialogData.title = '添加授权'
      this.$nextTick(() => {
        this.dialogData.loading = false
      })
    },
    // 处理抽屉关闭
    dialogClose() {
      this.initFormData()
      this.dialogData.visible = false
      this.dialogData.loading = true
    },
    // 处理抽屉确认
    async dialogConfirm() {
      // 调用组件的数据验证方法
      this.$refs['authorizationForm'].validate((valid) => {
        if (valid) {
          this.formSubmit()
        } else {
          this.$message.error('数据验证失败，请检查必填项数据！')
        }
      })
    },
    // form数据提交，请求接口
    async formSubmit() {
      this.dialogData.loading_text = '玩命提交中……'
      this.dialogData.loading = true
      let id = await this.$api.saveAuthorizationInfo(this.formData)
      this.$nextTick(() => {
        this.dialogData.visible = false
      })
      this.$nextTick(() => {
        if (id) {
          this.initAuthorizationList()
        }
      })
      this.$nextTick(() => {
        this.$message.success('保存成功!')
      })
      this.initFormData()
    },
    // 相应删除按钮
    async deleteButton(id) {
      let info = await this.$api.deleteAuthorization(id)
      if (info == 'ok') {
        this.$nextTick(() => {
          this.initAuthorizationList('del')
        })
      } else {
        this.$message.error(info)
      }
      
    },
    // 初始化数据
    initFormData() {
      // 初始化form表单
      this.$nextTick(() => {
        this.formData = {}
        if (this.$refs['authorizationForm']) {
          this.$refs['authorizationForm'].resetFields()
        }
        
      })
    },
    // 初始化授权列表
    async initAuthorizationList(type) {
      this.loadingStaus=true
      let pagingInfo = this.$refs['apeTable'].getPagingInfo(type)
      let searchCondition = this.searchCondition
      Object.assign(searchCondition,pagingInfo)
      let {list,pages}= await this.$api.getAuthorizationList(searchCondition)
      this.authorizationList = []
      this.$nextTick(() => {
        this.authorizationList=list
      })
      this.pagingData.total = pages.total
      this.offset = pages.offset
      this.loadingStaus=false
    },
    // 搜索用户验证
    searchUserVerify(rule, value, callback) {
      if (rule.required && !this.formData.user_id) {
        callback(new Error(rule.message))
      }
      callback()
    },
    // 搜索用户,qs请求参数，cb回调函数，详见组件用法
    async searchUser(qs, cb) {
      let list = await this.$api.authorizationSearchUser(qs)
      cb(list)
    },
    // 处理搜索结果的选中
    handleUserSelect(item) {
      this.formData.user_id = item.id
      if (this.formData.user_id) {
        this.$refs['authorizationForm'].validateField('mobile_alias')
      }
    },
    // 初始化选择验证
    initMobileVerify() {
      this.formData.user_id = ''
      this.$refs['authorizationForm'].validateField('mobile_alias')
    },
    // 搜索课程验证
    searchCourseVerify(rule, value, callback) {
      if (rule.required && !this.formData.course_id) {
        callback(new Error(rule.message))
      }
      callback()
    },
    // 搜索课程,qs请求参数，cb回调函数，详见组件用法
    async searchCourse(qs, cb) {
      let list = await this.$api.authorizationSearchCourse(qs)
      cb(list)
    },
    // 处理课程搜索结果的选中
    handleCourseSelect(item) {
      this.formData.course_id = item.id
      if (this.formData.course_id) {
        this.$refs['authorizationForm'].validateField('course_id')
      }
    },
    // 初始化课程选择验证
    initCourseVerify() {
      this.formData.course_id = ''
      this.$refs['authorizationForm'].validateField('course_id')
    },
    // 取消授权
    cancelAuthorizationButton(id) {
      this.formData.id = id
      this.formData.authorization_status = 2
      this.formSubmit()
    },
    // 恢复授权
    recoverAuthorizationButton(id) {
      this.formData.id = id
      this.formData.authorization_status = 1
      this.formSubmit()
    }
  },
  mounted() {
    this.initAuthorizationList()
  },
}
</script>

<style lang="stylus">
  .el-button
    margin-right 4px
  .table-header
    margin-bottom 12px
  .drag-handle
    font-size 24px
    cursor pointer
  .el-input-group__prepend, .el-input-group__append
    background #ffffff
    padding 0 12px
  .el-input-group__append
    color #ffffff
    background #1890ff
  .el-popover .el-checkbox-group .el-checkbox
    margin-left 0px
    margin-right 12px
  .el-select > .el-input 
    width 300px
  .el-radio.is-bordered
    width 100px
  .el-color-picker
    position absolute
  .authorization-search-user input,.search-course input
    width 332px !important

</style>
