<template>
  <div>
    <router-view v-show="$route.matched.length==3"></router-view>
    <PageHeaderLayout v-show="$route.matched.length==2">
      <div class="main-page-content">
        <el-row class="table-header">
          <el-col>
            <el-button type="primary" size="medium" icon="iconfont icon-tianjiacaidan2"  v-if="userPermissions.indexOf('article_create') != -1"  @click="addButton(0)"></el-button>
          </el-col>
        </el-row>
        <el-row class="table-search">
          <el-form size="medium" :inline="true" :model="searchCondition" class="demo-form-inline">
            <el-form-item label="分类">
              <el-cascader
                placeholder="选择分类"
                :props="cascaderProps"
                :options="searchCategory"
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
            <el-form-item label="发布时间">
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
              <el-button type="primary" @click="searchButton">查询</el-button>
            </el-form-item>
          </el-form>
        </el-row>
        <ApeTable ref="apeTable" :data="articleList" :columns="columns" :loading="loadingStaus" :pagingData="pagingData" @switchPaging="switchPaging" highlight-current-row>
          <el-table-column
            slot="second-column"
            width="64"
            label="序号">
            <template slot-scope="scope">
              <span>{{offset+scope.$index+1}}</span>
            </template>
          </el-table-column>
          <el-table-column
            width="120"
            label="操作">
            <template slot-scope="scope">
              <span>
                <el-button size="mini" icon="el-icon-edit" v-if="userPermissions.indexOf('article_edit') != -1"  @click="editButton(scope.row.id)"></el-button>
                <el-popover
                  v-if="userPermissions.indexOf('article_delete') != -1" 
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
    <ApeDrawer :drawerData="drawerData"  @drawerClose="drawerClose" @drawerConfirm="drawerConfirm">
      <template slot="ape-drawer">
        <el-form :model="formData" :rules="rules" ref="atlasForm" label-position="right" label-width="96px">
          <el-col :span="22" class="content-left">
            <el-form-item label="分类" prop="selectedList" ref="categoryItem">
                <el-cascader class="atlas-category" 
                  placeholder="选择分类"
                  :props="cascaderProps"
                  :options="categoryList"
                  v-model="selectedList"
                  @change="cascaderChange"
                  change-on-select
                  show-all-levels
                  clearable
                  filterable>
                </el-cascader>
              </el-form-item>
            <el-form-item label="标签" prop="selectedLabelList" ref="categoryLabelItem">
              <el-cascader  class="atlas-category" 
                placeholder="选择分类"
                :props="cascaderProps"
                :options="categoryLabelList"
                v-model="selectedLabelList"
                @change="cascaderLabelChange"
                change-on-select
                show-all-levels
                clearable
                filterable>
              </el-cascader>
            </el-form-item>
            <el-form-item label="标题" prop="title">
              <el-input v-model="formData.title"></el-input>
            </el-form-item>
            <el-form-item label="图集来源" prop="source">
              <el-input v-model="formData.source"></el-input>
            </el-form-item>
            <el-form-item label="摘要" prop="excerpt">
              <el-input type="textarea" v-model="formData.excerpt"></el-input>
            </el-form-item>
            <el-form-item label="封面+图片" prop="cover">
              <ApeUploader :limit="2" @handleUploadSuccess="handleUploadSuccess" @handleUploadRemove="handleUploadRemove" :upload-file-list="uploadFileList"></ApeUploader>
            </el-form-item>
            <el-form-item label="发布时间" prop="published_time">
              <el-date-picker
                v-model="formData.published_time"
                type="datetime"
                placeholder="选择日期时间"
                value-format="yyyy-MM-dd HH:mm:ss">
              </el-date-picker>
            </el-form-item>
            <el-form-item label="状态" prop="article_status">
              <el-checkbox-group v-model="formData.article_status">
                <el-checkbox label="is_published">发布</el-checkbox>
              </el-checkbox-group>
            </el-form-item>
          </el-col>
        </el-form>     
      </template>
    </ApeDrawer>
  </div >
</template>

<script>
import PageHeaderLayout from '@/layouts/PageHeaderLayout'
import ApeTable from '@/components/ApeTable'
import ApeDrawer from '@/components/ApeDrawer'
import ApeUploader from '@/components/ApeUploader'
import { mapGetters } from 'vuex'

export default {
  components: {
    PageHeaderLayout,
    ApeTable,
    ApeDrawer,
    ApeUploader
  },
  data() {
    return {
      // element的cascader的props值
      cascaderProps:{
        label:'display_name',
        value:'id',
      },
      searchCategory: [],
      // 搜索条件
      searchCondition:{
        selected_category:[]
      },
      loadingStaus: true,
      columns: [
        {
          title: '封面',
          type: 'image',
          value: 'cover_pc_url',
          width: 120
        },
        {
          title: '标题',
          value: [
            {lable:'ID号：',value:'id'},
            {lable:'标题：',value:'title'},
          ]
        },
        {
          title: '分类',
          value: 'category_alias',
          width: 120
        },
        {
          title: '作者',
          value: 'author_name',
          width: 120
        },
        {
          title: '发布时间',
          value: 'published_time',
          width: 160
        },
        {
          title: '状态',
          value: [
            {lable:'发布：',value:'published_alias'},
          ],
          width:160
        }
      
        
      ],
      // 表格列表数据
      articleList:[],
      // 分页信息
      pagingData:{
        is_show: true,
        layout: 'total, sizes, prev, pager, next, jumper',
        total: 0
      },
      // 分页的offset,序号列使用
      offset:0,
      //------抽屉数据-------
      // 抽屉数据
      drawerData: {
        visible: false,
        loading: true,
        loading_text: '玩命加载中……',
        // direction: 'right',
        title: '图集管理',
        width_height: '720px',
        // mask: false,
        // close_name: '关 闭',
        // confirm_name: '打 印',
      },
      loadingStaus: true,
      loadingText: '玩命提交中……',
      articleId:0, // 文章id，0表示创建，非零为编辑
      // element的cascader的props值
      cascaderProps:{
        label:'display_name',
        value:'id',
      },
      // 分类列表
      categoryList:[],
      // 标签
      categoryLabelList:[],
      // 已选中的分类列表，用于cascader组件选中效果
      selectedList:[],
      // 已选中标签
      selectedLabelList:[],
      // 文章基本信息表单结构
      formData:{article_status:['is_published'],type:2},
      // 已上传文件列表
      uploadFileList:[],
      // 文章基本信息规则
      rules: {
        title: [
          {required: true, message: '输入文章名称', trigger: 'blur' },
        ],
        selectedList: [{required: true, message: '选择分类', trigger: 'change', validator:this.cascaderVerify}],
        content: [{required: true, message: '输入内容', trigger: 'blur' }],
      },
    }
  },
  computed: {
    ...mapGetters(['userPermissions'])
  },
  methods: {
    // 搜索
    searchButton() {
      this.initAtlasList()
    },
    // 切换页码操作
    async switchPaging() {
      this.initAtlasList()
    },
    // 响应添加按钮
    async addButton() {
      this.drawerData.loading_text = '玩命加载中……'
      this.drawerData.visible = true
      this.drawerData.title = '添加图集'
      // 获取文章分类列表
      let {list} = await this.$api.getAtlasCategoryList()
      this.categoryList = list
      // 获取文章分类标签列表
      let {label_list} = await this.$api.getAtlasCategoryLabelList()
      this.categoryLabelList = label_list
      this.$nextTick(() => {
        this.drawerData.loading = false
      })
    },
    // 响应编辑按钮
    async editButton(id) {
      this.drawerData.loading_text = '玩命加载中……'
      this.drawerData.visible = true
      this.drawerData.title = '编辑图集(ID：'+id+')'
      // 获取文章分类列表
      let {list} = await this.$api.getAtlasCategoryList()
      this.categoryList = list
      // 获取文章分类标签列表
      let {label_list} = await this.$api.getAtlasCategoryLabelList()
      this.categoryLabelList = label_list
      let {info} = await this.$api.getArticleInfo(id)
      this.formData = info
      this.selectedList = info.category_ids
      this.selectedLabelList = info.category_2_ids
      if (info.cover) {
        this.uploadFileList.push({id:info.cover_info.id,name:info.cover_info.title,url:info.cover_info.full_path})
      }
      if (info.pic) {
        this.uploadFileList.push({id:info.pic_info.id,name:info.pic_info.title,url:info.pic_info.full_path})
      } 
      this.drawerData.loading = false
    },
    // 相应删除按钮
    async deleteButton(id) {
      let info = await this.$api.deleteAtlas(id)
      if (info == 'ok') {
        this.$nextTick(() => {
          this.initAtlasList('del')
        })
      } else {
        this.$message.error(info)
      }
      
    },
    // 初始化图集列表
    async initAtlasList(type) {
      this.loadingStaus=true
      let pagingInfo = this.$refs['apeTable'].getPagingInfo(type)
      let searchCondition = this.handleSearchCondition()
      Object.assign(searchCondition,pagingInfo)
      let {list,pages}= await this.$api.getAtlasList(searchCondition)
      this.articleList = []
      this.$nextTick(() => {
        this.articleList=list
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
    // 获取图集分类列表
    async getCategoryList() {
      // 获取图集分类列表
      let {list} = await this.$api.getAtlasCategoryList()
      this.searchCategory = list
    },
    // ----- 抽屉、添加编辑-----------------------------
    // 处理抽屉关闭
    drawerClose() {
      this.initFormData()
      this.drawerData.visible = false
      this.drawerData.loading = true
    },
    // 处理抽屉确认
    async drawerConfirm() {
      // 调用组件的数据验证方法
      this.$refs['atlasForm'].validate((valid) => {
        if (valid) {
            this.formSubmit()
        } else {
          this.$message.error('数据验证失败，请检查必填项数据！')
        }
      })
    },
    // form数据提交，请求接口
    async formSubmit() {
      this.drawerData.loading_text = '玩命提交中……'
      this.drawerData.loading = true
      let id = await this.$api.saveAtlas(this.formData)
      this.$nextTick(() => {
        this.drawerData.visible = false
      })
      this.$nextTick(() => {
        if (id) {
          this.initAtlasList()
        }
      })
      this.$nextTick(() => {
        this.$message.success('保存成功!')
      })
      this.initFormData()
    },
    // 初始化数据
    async initFormData() {
      // 初始化form表单
      this.$nextTick(() => {
        this.formData = {article_status:['is_published'],type:2}
        this.$refs['atlasForm'].resetFields()
        this.uploadFileList = []
        this.selectedList = []
        this.selectedLabelList = []
      })
    },
    // 级联菜单组件change事件
    cascaderChange(v) {
      if (v.length) {
        this.formData.category_id = v[(v.length-1)]
        this.formData.category_ids = v
      }
    },
    // 级联菜单组件change事件
    cascaderLabelChange(v) {
      if (v.length) {
        this.formData.category_2_id = v[(v.length-1)]
        this.formData.category_2_ids = v
      }
    },
    // 级联菜单自定义验证，选择分类
    cascaderVerify(rule, value, callback) {
      if (rule.required && !this.selectedList.length) {
        callback(new Error(rule.message))
      }
      callback()
    },
    // 图片上传成功回调
    handleUploadSuccess(file, fileList) {
      if(fileList.length==1) {
        this.formData.cover = file.id
      }
      if(fileList.length==2) {
        this.formData.pic = file.id
      }
      this.uploadFileList = fileList
    },
    // 图片删除回调
    handleUploadRemove(file, fileList) {
      if(fileList.length==1) {
        this.formData.pic = 0
      }
      if(fileList.length==0) {
        this.formData.cover = 0
      }
      this.uploadFileList = fileList
    },
    // 图片上传自定义验证
    apeUploaderVerify(rule, value, callback) {
      if (rule.required && !this.formData.cover) {
        callback(new Error(rule.message))
      }
      callback()
    },
  },

  mounted() {
    this.getCategoryList()
    this.initAtlasList()
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
  .atlas-category .el-input
    width 520px !important
</style>
