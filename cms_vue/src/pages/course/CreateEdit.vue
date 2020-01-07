<template>
  <div>
    <PageHeaderLayout>
      <el-tabs v-model="activeTab"
        @tab-click="handleTabClick"
        :before-leave="beforeSwitch"
        tab-position="left"
        type="card">
        <div class="tab-pane-header">
          {{hearTitle}}
          <el-button class="return-list" size="medium" type="primary" icon="iconfont icon-fanhui8" @click="returnList">返回列表</el-button>
        </div>
        <el-tab-pane label="基本设置"
          v-loading="loadingStaus"
          :element-loading-text="loadingText">
          <div class="tab-pane-body">
            <el-form :model="formData" :rules="basicInfoRules" ref="basicInfoForm" label-position="right" label-width="112px">
              <el-form-item label="课程名称" prop="title">
                <el-input v-model="formData.title"></el-input>
              </el-form-item>
              <el-form-item label="所属分类" prop="category_id">
                <el-cascader
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
              <el-form-item label="课程简介" prop="intro">
                <el-input type="textarea" v-model="formData.intro"></el-input>
              </el-form-item>
              <el-form-item label="课程图片" prop="cover_pc">
                <ApeUploader :limit="1" @handleUploadSuccess="handleUploadSuccess" @handleUploadRemove="handleUploadRemove" :upload-file-list="uploadFileList"></ApeUploader>
              </el-form-item>
              <el-form-item label="SEO标题" prop="seo_title">
                <el-input v-model="formData.seo_title"></el-input>
              </el-form-item>
              <el-form-item label="SEO关键词" prop="seo_keywords">
                <el-input v-model="formData.seo_keywords"></el-input>
              </el-form-item>
              <el-form-item label="SEO描述" prop="seo_description">
                <el-input type="textarea" v-model="formData.seo_description"></el-input>
              </el-form-item>
            </el-form>  
          </div>
        </el-tab-pane>
        <el-tab-pane label="课程介绍"
          v-loading="loadingStaus"
          :element-loading-text="loadingText">
          <div class="tab-pane-body-intro">
            <el-form :model="formData" :rules="courseIntroRules" ref="courseIntroForm" label-position="right" label-width="112px">
              <el-form-item label="WEB课程详情" prop="pc_detail_intro">
                <ApeEditor name="editor" :init-html="formData.pc_detail_intro" @handleTinymceInput="handleTinymceInput"></ApeEditor>
              </el-form-item>
            </el-form>  
          </div>
        </el-tab-pane>
        <el-tab-pane label="售卖信息"
          v-loading="loadingStaus"
          :element-loading-text="loadingText">
          <div class="tab-pane-body">
            <el-form :model="formData" ref="saleInfoForm" label-position="right" label-width="112px">
              <el-form-item label="收费状态" prop="is_free">
                <el-radio-group v-model="formData.is_free">
                  <el-radio label="0" border>免费</el-radio>
                  <el-radio label="1" border>收费</el-radio>  
                </el-radio-group>
              </el-form-item>
              <el-form-item label="课程价格" prop="price">
                <el-input v-model="formData.price"></el-input>
              </el-form-item>
            </el-form>
          </div>
        </el-tab-pane>
        <el-tab-pane label="高级设置"
          v-loading="loadingStaus"
          :element-loading-text="loadingText">
          <div class="tab-pane-body">
            <el-form :model="formData" ref="advancedSettingForm" label-position="right" label-width="112px">
              <el-form-item label="连载状态" prop="serial">
                <el-radio-group v-model="formData.serial">
                  <el-radio label="1" border>更新中</el-radio>
                  <el-radio label="2" border>已完结</el-radio>  
                </el-radio-group>
              </el-form-item>
              <el-form-item label="VIP是否免费" prop="vip_free">
                <el-radio-group v-model="formData.vip_free">
                  <el-radio label="1" border>免费</el-radio>
                  <el-radio label="0" border>收费</el-radio>  
                </el-radio-group>
              </el-form-item>
            </el-form>
          </div>
        </el-tab-pane>
        <el-tab-pane label="录播管理"
          v-loading="loadingStaus"
          element-loading-text="玩命获取中……">
          <div class="tab-pane-body-video">
            <div class="body-header">
              <el-button size="medium" type="warning" icon="iconfont icon-tianjiacaidan1"  @click="addVideoChapter" >添加章节</el-button>
            </div>
            <div class="body-content">
              <el-tree
                :data="videoChapterList"
                node-key="unique_key"
                :highlight-current="true"
                :expand-on-click-node="false"
                default-expand-all
                draggable
                :allow-drop="handleAllowDrop"
                @node-drop="handleNodeDrop">
                <div class="video-tree-node" slot-scope="{node,data}">
                  <div class="chapter-node" v-if="data.data_type=='chapter'">
                    <span>第{{data.identify_key}}章</span> 
                    <span>{{data.title}}</span>
                    <div class="right">
                      <el-button size="mini" type="primary" icon="iconfont icon-zengjiazicaidan" @click="addVideo(data.id)"></el-button>
                      <el-button size="mini" type="primary" icon="el-icon-edit" @click="editVideoChapter(data.id)"></el-button>
                      <el-popover
                        placement="top"
                        width="150"
                        v-model="data.visible">
                        <p>确定要删除记录吗？</p>
                        <div style="text-align: right; margin: 0;">
                          <el-button type="text" size="mini" @click="data.visible=false">取消</el-button>
                          <el-button type="danger" size="mini" @click="deleteVideoChapter(data)">确定</el-button>
                        </div>
                        <el-button slot="reference" type="danger" size="mini" icon="el-icon-delete"></el-button>
                      </el-popover>
                    </div>
                  </div>
                  <div class="video-node" v-if="data.data_type=='video'">
                    <span class="video-identify">{{data.identify_key}}</span>
                    <span>{{data.title}}</span>
                    <span v-if="data.status" class="success-status-tag">转码完成</span>
                    <span v-else class="warning-status-tag">转码中</span>
                    <div class="right">
                      <el-button v-if="data.status" size="mini" class="view-video" icon="iconfont icon-shipin2" @click="videoPreview(data)"></el-button>
                      <el-button size="mini" type="primary" icon="el-icon-edit" @click="editVideo(data.id)"></el-button>
                      <el-popover
                        placement="top"
                        width="150"
                        v-model="data.visible">
                        <p>确定要删除记录吗？</p>
                        <div style="text-align: right; margin: 0;">
                          <el-button type="text" size="mini" @click="data.visible=false">取消</el-button>
                          <el-button type="danger" size="mini" @click="deleteVideo(data)">确定</el-button>
                        </div>
                        <el-button slot="reference" type="danger" size="mini" icon="el-icon-delete"></el-button>
                      </el-popover>
                    </div>
                  </div>
                </div>
              </el-tree>
            </div>
          </div>
        </el-tab-pane>
        <el-tab-pane label="直播管理"
          v-loading="loadingStaus"
          element-loading-text="玩命获取中……">
          <div class="tab-pane-body-live">
            <div class="body-header">
              <el-button size="medium" type="primary" icon="iconfont icon-tianjiacaidan1"  @click="addLive" >添加直播</el-button>
            </div>
            <div class="body-content">
              <el-tree
                :data="liveList"
                node-key="unique_key"
                :highlight-current="true"
                :expand-on-click-node="false"
                default-expand-all
                draggable
                :allow-drop="handleLiveAllowDrop"
                @node-drop="handleLiveNodeDrop">
                <div class="live-tree-node" slot-scope="{node,data}">
                  <div class="live-node">
                    <span>{{data.identify_key}}</span> 
                    <span>{{data.title}}</span>
                    <span class="live-time-tag">开始时间：{{data.start_time}}</span>
                    <span class="live-time-tag">结束时间：{{data.end_time}}</span>
                    <div class="right">
                      <el-button size="mini" type="primary" icon="iconfont icon-zhibo2" @click="getLiveStreamInfo(data.id)" plain></el-button>
                      <el-button size="mini" type="primary" icon="el-icon-edit" @click="editLive(data.id)"></el-button>
                      <el-popover
                        placement="top"
                        width="150"
                        v-model="data.visible">
                        <p>确定要删除记录吗？</p>
                        <div style="text-align: right; margin: 0;">
                          <el-button type="text" size="mini" @click="data.visible=false">取消</el-button>
                          <el-button type="danger" size="mini" @click="deleteLive(data.id)">确定</el-button>
                        </div>
                        <el-button slot="reference" type="danger" size="mini" icon="el-icon-delete"></el-button>
                      </el-popover>
                    </div>
                  </div>
                </div>
              </el-tree>
            </div>
          </div>
        </el-tab-pane>
        <div class="tab-pane-footer"  v-if="paneFooterStatus" >
          <el-button size="medium" type="primary" :disabled="loadingStaus" @click="saveConfirm">保存</el-button>
        </div>
      </el-tabs>
      <ModalDialog :dialogData="dialogData" @dialogConfirm="dialogConfirm" @dialogClose="dialogClose">
        <template slot="content">
          <el-form :model="videoChapterFormData" :rules="videoChapterRules" ref="videoChapterForm" label-position="right" label-width="96px">
            <el-form-item label="章节标题" prop="title">
              <el-input v-model="videoChapterFormData.title"></el-input>
            </el-form-item>
            <el-form-item label="简介" prop="intro">
              <el-input type="textarea" v-model="videoChapterFormData.intro"></el-input>
            </el-form-item>
          </el-form>        
        </template>
      </ModalDialog>
      <ApeDrawer :drawerData="drawerData"  @drawerClose="drawerClose" @drawerConfirm="drawerConfirm">
        <template slot="ape-drawer">
          <el-form :model="videoFormData" :rules="videoRules" ref="videoForm" label-position="right" label-width="96px">
            <el-row>
              <el-col :span="22">
                <el-form-item label="标题" prop="title">
                  <el-input v-model="videoFormData.title"></el-input>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="22">
                <el-form-item label="简介" prop="intro">
                  <el-input type="textarea" :rows="6" v-model="videoFormData.intro"></el-input>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="22">
                <el-form-item label="视频" prop="aliyun_video_id" ref="aliyunVodUload">
                  <ApeAliyunVodUpload :is-start-upload="isStartUpload" :upload-file-list="uploadVodList" @handleUploadChange="handleVodUploadChange" @handleUploadRemove="handleVodUploadRemove" @handleUploadSuccess="handleVodUploadSuccess"></ApeAliyunVodUpload>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="22">
                <el-form-item label="SEO标题" prop="seo_title">
                  <el-input v-model="videoFormData.seo_title"></el-input>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="22">
                <el-form-item label="SEO关键词" prop="seo_keywords">
                  <el-input v-model="videoFormData.seo_keywords"></el-input>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="22">
                <el-form-item label="SEO描述" prop="seo_description">
                  <el-input type="textarea" :rows="4" v-model="videoFormData.seo_description"></el-input>
                </el-form-item>
              </el-col>
            </el-row>
          </el-form>     
        </template>
      </ApeDrawer>
      <ApeDrawer :drawerData="liveDrawerData"  @drawerClose="liveDrawerClose" @drawerConfirm="liveDrawerConfirm">
        <template slot="ape-drawer">
          <el-form :model="liveFormData" :rules="liveRules" ref="liveForm" label-position="right" label-width="96px">
            <el-row>
              <el-col :span="22">
                <el-form-item label="标题" prop="title">
                  <el-input v-model="liveFormData.title"></el-input>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="22">
                <el-form-item label="封面" prop="cover">
                  <ApeUploader :limit="1" @handleUploadSuccess="handleLiveUploadSuccess" @handleUploadRemove="handleLiveUploadRemove" :upload-file-list="uploadLiveFileList"></ApeUploader>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="22">
                <el-form-item label="讲师" prop="mobile_alias">
                  <el-autocomplete
                    class="live-lecturer-user"
                    v-model="liveFormData.mobile_alias"
                    :fetch-suggestions="searchLecturer"
                    placeholder="输入手机号检索用户"
                    @select="handleLecturerSelect"
                    @clear="initMobileVerify"
                    clearable>
                  </el-autocomplete>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="22">
                <el-form-item label="直播时间" prop="time_value">
                  <el-date-picker
                    class="live-datetimerange"
                    v-model="liveFormData.time_value"
                    type="datetimerange"
                    align="right"
                    unlink-panels
                    range-separator="至"
                    start-placeholder="开始时间"
                    end-placeholder="结束时间"
                    value-format="yyyy-MM-dd HH:mm:ss">
                  </el-date-picker>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="22">
                <el-form-item label="简介" prop="intro">
                  <el-input type="textarea" :rows="6" v-model="liveFormData.intro"></el-input>
                </el-form-item>
              </el-col>
            </el-row>
          </el-form>     
        </template>
      </ApeDrawer>
      <ModalDialog :dialogData="dialogliveStreamData" @dialogConfirm="dialogliveStreamButton" @dialogClose="dialogliveStreamButton">
        <template slot="content">
          <div class="live-stream">
            <span>推流地址</span>
            <span>{{liveStreamInfo.push_info}}</span>
          </div>
          <div class="live-stream">
            <span>播流地址</span>
            <div>
              <span v-for="playInfo in liveStreamInfo.play_info" :key="playInfo">{{playInfo}}</span>
            </div>
          </div>
        </template>
      </ModalDialog>
    </PageHeaderLayout>
    <div class="ape-aliyun-player-mask" :class="{'is-video-preview':isVideoPreview}">
      <h2 class="header-title">视频预览({{previewVideoTitle}})</h2>
      <el-button class="close-button" type="danger" size="mini" icon="iconfont icon-close" circle @click="closeVideoPreview"></el-button>
      <ApeAliyunPlayer ref="apeAliyunPlayer" :auto-play="true" :width="'640px'" :height="'480px'"></ApeAliyunPlayer>
    </div>
  </div >
</template>

<script>
import PageHeaderLayout from '@/layouts/PageHeaderLayout'
import ApeUploader from '@/components/ApeUploader'
import ApeEditor from '@/components/ApeEditor' 
import ApeDrawer from '@/components/ApeDrawer'
import ModalDialog from '@/components/ModalDialog'
import ApeAliyunVodUpload from '@/components/ApeAliyunVodUpload'
import ApeAliyunPlayer from '@/components/ApeAliyunPlayer'
import { mapGetters } from 'vuex'

export default {
  components: {
    PageHeaderLayout,
    ApeUploader,
    ApeEditor,
    ApeDrawer,
    ModalDialog,
    ApeAliyunVodUpload,
    ApeAliyunPlayer
  },
  data() {
    return {
      activeTab: '0',
      hearTitle: '基本设置',
      loadingStaus: true,
      loadingText: '玩命提交中……',
      courseId:0, // 课程id，0表示创建，非零为编辑
      // element的cascader的props值
      cascaderProps:{
        label:'display_name',
        value:'id',
      },
      // 分类列表
      categoryList:[],
      // 已选中的分类列表，用于cascader组件选中效果
      selectedList:[],
      // 课程基本信息表单结构
      formData: {},
      // 已上传文件列表
      uploadFileList:[],
      // 课程基本信息规则
      basicInfoRules: {
        title: [
          {required: true, message: '输入课程名称', trigger: 'blur' },
        ],
        cover_pc: [
          {required: true, message: '请上传图片', trigger: 'blur', validator:this.apeUploaderVerify},
        ],
      },
      // 课程介绍规则
      courseIntroRules: {
        pc_detail_intro: [
          {required: true, message: '输入课程详情', trigger: 'blur' },
        ],
      },
      // 视频章节list
      videoChapterList:[],
      // 视频章节信息，弹窗数据
      dialogData:{
        visible: false,
        title: '',
        width: '30%',
        loading: true,
        modal: true
      },
      // 视频章节信息表单结构
      videoChapterFormData:{},
      // 视频章节规则
      videoChapterRules: {
        title: [
          {required: true, message: '输入章节标题', trigger: 'blur' },
        ],
      },
      // 视频信息,抽屉数据
      drawerData: {
        visible: false,
        loading: true,
        loading_text: '玩命加载中……',
        // direction: 'right',
        title: '视频添加',
        width_height: '640px',
        // mask: false,
        // close_name: '关 闭',
        // confirm_name: '打 印',
      },
      // 视频上传控制
      isStartUpload:false,
      // 是否选取了视频文件，既要不要执行上传
      isChangeVideo:false,
      // 视频信息表单结构
      videoFormData:{},
      // 已上传的视频列表，虽然只允许上传一个
      uploadVodList:[],
      // 视频规则
      videoRules: {
        title: [
          {required: true, message: '输入视频标题', trigger: 'blur' },
        ],
        aliyun_video_id: [
          {required: true, message: '选择视频', trigger: 'blur', validator:this.apeAliyunVodUploaderVerify},
        ],
      },
      // 是否视频预览
      isVideoPreview: false,
      // 预览视频的title
      previewVideoTitle: '',
      // 轮询你请求视频列表
      videoInterval: null,
      // 直播
      liveList:[],
      // 直播信息,抽屉数据
      liveDrawerData: {
        visible: false,
        loading: true,
        loading_text: '玩命加载中……',
        // direction: 'right',
        title: '直播添加',
        width_height: '640px',
        // mask: false,
        // close_name: '关 闭',
        // confirm_name: '打 印',
      },
      // 直播信息表单结构
      liveFormData:{},
      // 直播已上传文件列表
      uploadLiveFileList:[],
      // 直播规则
      liveRules: {
        title: [
          {required: true, message: '输入视频标题', trigger: 'blur'},
        ],
        time_value: [
          {required: true, message: '选择直播时间', trigger: 'blur'},
        ],
        mobile_alias: [
          {required: true, message: '选择讲师', trigger: 'blur', validator:this.searchLecturerVerify},
        ],
      },
      // 直播流信息，弹窗数据
      dialogliveStreamData:{
        visible: false,
        title: '',
        width: '40%',
        loading: true,
        modal: true
      },
      // 直播流信息
      liveStreamInfo:{}
    }
  },
  computed: {
    ...mapGetters(['userPermissions']),
    // 课程相关信息处理窗格，页脚是否显示
    paneFooterStatus() {
      if (parseInt(this.activeTab) > 3) {
        return false
      }
      return true
    }
  },
  methods: {
    handleTabClick(tab) {
      this.hearTitle = tab.label
    },
    // tab标签切换前调用,activeName, oldActiveNam
    beforeSwitch(active) {
      // 创建课程是，只允许操作“基本设置”
      if (this.courseId === 0 && active !== '0') {
        this.$notify.warning('请完成当前设置！')
        return false
      }
      if (active === '4') {
        this.initVideoChapterList()
        this.videoInterval = setInterval(() => {return this.initVideoChapterList(true)},10000)
        this.$once('hook:beforeDestroy', () => {            
            clearInterval(this.videoInterval);                                    
        })
      } else {
        clearInterval(this.videoInterval)
      }
      if (active === '5') {
        this.initLiveList()
      }
    },
    // 返回列表按钮
    returnList() {
      this.$router.push('/course')
    },
    // 级联菜单组件change事件
    cascaderChange(v) {
      if (v.length) {
        this.formData.category_id = v[(v.length-1)]
        this.formData.category_ids = v
      }
    },
    // 确认保存按钮
    async saveConfirm() {
      if (parseInt(this.activeTab) === 0) {
        // 调用组件的数据验证方法
        this.$refs['basicInfoForm'].validate((valid) => {
          if (valid) {
            this.formSubmit()
          } else {
            this.$message.error('数据验证失败，请检查必填项数据！')
          }
        })
        return true
      }

      if (parseInt(this.activeTab) === 1) {
        // 调用组件的数据验证方法
        this.$refs['courseIntroForm'].validate((valid) => {
          if (valid) {
            this.formSubmit()
          } else {
            this.$message.error('数据验证失败，请检查必填项数据！')
          }
        })
        return true
      }

      this.formSubmit()
    },
    // 课程相关信息的保存处理
    async formSubmit() {
      this.loadingStaus = true
      let id = await this.$api.saveCourse(this.formData)
      if (id) {
        this.$notify.success('保存成功！')
      }
      if (this.courseId === 0 && id) {
        this.$router.push(this.$route.matched[1].path+'/'+id+'/edit')
        this.initCourseInfo()
      }
      this.activeTab = String(parseInt(this.activeTab)+1)
      this.$nextTick(() => {
        this.loadingStaus = false
      }) 
    },
    // 图片上传成功回调
    handleUploadSuccess(file, fileList) {
      this.formData.cover_pc = file.id
      this.uploadFileList = fileList
    },
    // 图片删除回调
    handleUploadRemove(file, fileList) {
      this.formData.cover_pc = 0
      this.uploadFileList = fileList
    },
    // 图片上传自定义验证
    apeUploaderVerify(rule, value, callback) {
      if (rule.required && !this.formData.cover_pc) {
        callback(new Error(rule.message))
      }
      callback()
    },
    // 处理编辑器内容输入
    handleTinymceInput(val) {
      this.formData.pc_detail_intro = val
    },
    // 初始化课程基本信息
    async initCourseInfo() {
      // 获取文章分类列表
      let {list} = await this.$api.getCourseCategoryList()
      this.categoryList = list
      if (this.$route.params.course_id) {
        this.courseId = this.$route.params.course_id
      }
      if (this.courseId) {
        let {info} = await this.$api.getCourseInfo(this.courseId)
        this.formData = info
        this.selectedList = info.category_ids
        this.uploadFileList = [{id:info.image_info.id,name:info.image_info.title,url:info.image_info.full_path}]
      }
    },
    // 处理模态框，确定事件
    dialogConfirm() {
      // 调用组件的数据验证方法
      this.$refs['videoChapterForm'].validate((valid) => {
        if (valid) {
          this.videoChapterFormSubmit()
        } else {
          this.$notify.error('数据验证失败，请检查必填项数据！')
        }
      })
    },
    // 处理模态框，关闭事件
    dialogClose() {
      this.initVideoChapterFormData()
    },
    // 初始化视频章节数据
    initVideoChapterFormData() {
      // 初始化form表单
      this.$nextTick(() => {
        this.dialogData.visible = false
        this.dialogData.loading = true
        this.videoChapterFormData = {}
        this.$refs['videoChapterForm'].resetFields()
      })
    },
    // 初始化视频章节list
    async initVideoChapterList(type) {
      if (!type) {
        this.loadingStaus = true
      }
      let {list}= await this.$api.getCourseTreeVideoChapterList(this.courseId)
      this.videoChapterList=list
      if (!type) {
        this.$nextTick(() => {
          this.loadingStaus = false
        }) 
      }
    },
    // 视频章节信息提交，请求接口
    async videoChapterFormSubmit() {
      let id = await this.$api.saveCourseVideoChapter(this.videoChapterFormData)
      if (id) {
        this.initVideoChapterList()
      }
      this.initVideoChapterFormData()
      this.$notify.success('章节信息保存成功!')
    },
    // 添加视频章
    addVideoChapter() {
      this.dialogData.visible = true
      this.dialogData.title = '添加章节'
      this.videoChapterFormData.course_id = this.courseId
      this.dialogData.loading = false
    },
    // 编辑视频章节
    async editVideoChapter(id) {
      this.dialogData.loading_text = '玩命加载中……'
      this.dialogData.visible = true
      this.dialogData.title = '编辑章节'
      let {info} = await this.$api.getCourseVideoChapterInfo(id)
      this.videoChapterFormData = info
      this.dialogData.loading = false
    },
    // 删除视频章节
    async deleteVideoChapter(data) {
      let info = await this.$api.deleteCourseVideoChapterInfo(data)
      if (info == 'ok') {
        this.$nextTick(() => {
          this.initVideoChapterList()
        })
      } else {
        this.$notify.error(info)
      }
    },
    // 处理抽屉关闭
    drawerClose() {
      this.initVideoFormData()
    },
    // 处理抽屉确认
    async drawerConfirm() {
      // 调用组件的数据验证方法
      this.$refs['videoForm'].validate((valid) => {
        if (valid) {
          if (this.isChangeVideo) {
            this.isStartUpload = true
          } else {
            this.videoFormSubmit()
          }
        } else {
          this.$message.error('数据验证失败，请检查必填项数据！')
          this.$nextTick(() => {
            this.isStartUpload = false
          })
        }
      })
    },
    // 处理视频文件选择回调
    handleVodUploadChange(fileInfo) {
      this.videoFormData.aliyun_video_id = fileInfo.video_id
      this.videoFormData.filename = fileInfo.filename
      this.isChangeVideo = true
      this.$refs['videoForm'].clearValidate(['aliyun_video_id'])
    },
    // 处理视频删除回调
    handleVodUploadRemove() {
      this.videoFormData.aliyun_video_id = ''
      this.videoFormData.filename = ''      
    },
    // 处理视频上传成功回调
    handleVodUploadSuccess() {
      this.videoFormData.status = 0
      this.videoFormSubmit()
    },
    // 视频上传自定义验证
    apeAliyunVodUploaderVerify(rule, value, callback) {
      if (rule.required && !this.videoFormData.aliyun_video_id) {
        callback(new Error(rule.message))
      }
      callback()
    },
    // 视频信息提交，请求接口
    async videoFormSubmit() {
      let id = await this.$api.saveCourseVideo(this.videoFormData)
      if (id) {
        this.initVideoChapterList()
      }
      this.initVideoFormData()
      this.$notify.success('视频信息保存成功!')
    },
    // 初始化视频数据
    initVideoFormData() {
      // 初始化form表单
      this.$nextTick(() => {
        this.drawerData.visible = false
        this.drawerData.loading = true
        this.videoFormData = {}
        this.isStartUpload = false
        this.isChangeVideo = false
        this.$refs['videoForm'].resetFields()
      })
    },
    // 添加视频
    addVideo(chapterId) {
      this.drawerData.loading_text = '玩命加载中……'
      this.drawerData.visible = true
      this.drawerData.title = '添加视频'
      this.videoFormData.course_id = this.courseId
      this.videoFormData.chapter_id = chapterId
      this.uploadVodList =[]
      this.$nextTick(() => {
        this.drawerData.loading = false
      })
    },
    // 编辑视频
    async editVideo(id) {
      this.drawerData.loading_text = '玩命加载中……'
      this.drawerData.visible = true
      this.drawerData.title = '编辑视频'
      let {info} = await this.$api.getCourseVideoInfo(id)
      this.videoFormData = info
      this.uploadVodList =[{name:info.filename}]
      this.drawerData.loading = false
    },
    // 视频预览
    async videoPreview(data) {
      this.isVideoPreview = true
      this.previewVideoTitle = data.title
      let previewInfo = await this.$api.getCourseVideoPreview(data.aliyun_video_id);
      this.$refs.apeAliyunPlayer.replayByVidAndPlayAuth(previewInfo['auth_info']['VideoMeta']['VideoId'],previewInfo['auth_info']['PlayAuth'])
    },
    // 关闭视频预览
    closeVideoPreview() {
      this.isVideoPreview = false
      this.$refs.apeAliyunPlayer.pause()
    },
    // 删除视频章节
    async deleteVideo(data) {
      let info = await this.$api.deleteCourseVideoInfo(data)
      if (info == 'ok') {
        this.$nextTick(() => {
          this.initVideoChapterList()
        })
      } else {
        this.$notify.error(info)
      }
    },
    // 判断是否可以被托转
    handleAllowDrop(dragging, drop) {
      if (dragging.level === drop.level) {
        return true
      }
      return false
    },
    // 处理节点拖拽完成，前端效果结束且成功后执行，不使用组件回调参数，功能判断放到后端
    async handleNodeDrop() {
      let info = await this.$api.orderCourseVideoChapter(this.videoChapterList)
      if (info == 'ok') {
        this.$nextTick(() => {
          this.initVideoChapterList()
        })
        this.$notify.success('排序成功！')
      } else {
        this.initVideoChapterList()
        this.$notify.error(info)
      } 
    },
    // 初始化直播列表
    async initLiveList() {
      this.loadingStaus = true
      let {list}= await this.$api.getCourseTreeLiveList(this.courseId)
      this.liveList=list
      this.$nextTick(() => {
        this.loadingStaus = false
      }) 
    },
    // 初始化直播form数据
    initLiveFormData() {
      // 初始化form表单
      this.$nextTick(() => {
        this.liveDrawerData.visible = false
        this.liveDrawerData.loading = true
        this.liveFormData = {}
        this.$refs['liveForm'].resetFields()
      })
    },
    // 直播封面上传成功回调
    handleLiveUploadSuccess(file, fileList) {
      this.liveFormData.cover = file.id
      this.uploadLiveFileList = fileList
    },
    // 直播封面删除回调
    handleLiveUploadRemove(file, fileList) {
      this.liveFormData.cover = 0
      this.uploadLiveFileList = fileList
    },
    // 搜索用户,qs请求参数，cb回调函数，详见组件用法
    async searchLecturer(qs, cb) {
      let list = await this.$api.courseLiveSearchLecturer(qs)
      cb(list)
    },
    // 处理搜索结果的选中
    handleLecturerSelect(item) {
      this.liveFormData.lecturer_id = item.id
      if (this.liveFormData.lecturer_id) {
        this.$refs['liveForm'].validateField('mobile_alias')
      }
    },
    // 直播初始化选择验证
    initMobileVerify() {
      this.liveFormData.lecturer_id = ''
      this.$refs['liveForm'].validateField('mobile_alias')
    },
    // 搜索用户验证
    searchLecturerVerify(rule, value, callback) {
      if (rule.required && !this.liveFormData.lecturer_id) {
        callback(new Error(rule.message))
      }
      callback()
    },
    // 添加直播
    addLive() {
      this.liveDrawerData.loading_text = '玩命加载中……'
      this.liveDrawerData.visible = true
      this.liveDrawerData.title = '添加直播'
      this.liveFormData.course_id = this.courseId
      this.uploadLiveFileList =[]
      this.$nextTick(() => {
        this.liveDrawerData.loading = false
      })
    },
    // 编辑直播
    async editLive(id) {
      this.liveDrawerData.loading_text = '玩命加载中……'
      this.liveDrawerData.visible = true
      this.liveDrawerData.title = '编辑直播'
      let {info} = await this.$api.getCourseLiveInfo(id)
      this.liveFormData = info
      this.uploadLiveFileList = info.image_info.id?[{id:info.image_info.id,name:info.image_info.title,url:info.image_info.full_path}]:[]
      this.liveDrawerData.loading = false
    },
    // 删除直播
    async deleteLive(id) {
      let info = await this.$api.deleteCourseLiveInfo(id)
      if (info == 'ok') {
        this.$nextTick(() => {
          this.initLiveList()
        })
      } else {
        this.$notify.error(info)
      }
    },
    // 处理直播抽屉关闭
    liveDrawerClose() {
      this.initLiveFormData()
    },
    // 处理直播抽屉确认
    async liveDrawerConfirm() {
      // 调用组件的数据验证方法
      this.$refs['liveForm'].validate((valid) => {
        if (valid) {
          this.liveFormSubmit()
        } else {
          this.$message.error('数据验证失败，请检查必填项数据！')
        }
      })
    },
    // 直播信息提交，请求接口
    async liveFormSubmit() {
      let id = await this.$api.saveCourseLive(this.liveFormData)
      if (id) {
        this.initLiveList()
      }
      this.initLiveFormData()
      this.$notify.success('直播信息保存成功!')
    },
    // 判断是否可以被托转
    handleLiveAllowDrop(dragging, drop) {
      if (dragging.level === drop.level) {
        return true
      }
      return false
    },
    // 处理直播节点拖拽完成，前端效果结束且成功后执行，不使用组件回调参数，功能判断放到后端
    async handleLiveNodeDrop() {
      let info = await this.$api.orderCourseLive(this.liveList)
      if (info == 'ok') {
        this.$nextTick(() => {
          this.initLiveList()
        })
        this.$notify.success('排序成功！')
      } else {
        this.initLiveList()
        this.$notify.error(info)
      }
    },
    // 获取直播信息，推流播放等信息
    async getLiveStreamInfo(id) {
      this.dialogliveStreamData.visible = true
      this.dialogliveStreamData.title = '直播流信息'
      let {info} = await this.$api.getCourseLiveStreamInfo(id)
      this.liveStreamInfo = info
      this.dialogliveStreamData.loading = false
    },
    // 直播信息模态框事件
    dialogliveStreamButton() {
      this.dialogliveStreamData.visible = false
      this.dialogliveStreamData.loading = true
    }
  },
  async mounted() {
    this.loadingStaus = true
    this.initCourseInfo()
    this.$nextTick(() => {
      this.loadingStaus = false
    }) 
  },
}
</script>

<style lang="stylus">
  .el-tabs--left .el-tabs__header.is-left
    margin-left 16px
    margin-right 40px
    min-width 160px
  .el-tabs__nav.is-left
    background-color #ffffff
    border-radius 4px !important
    overflow hidden
  .el-tabs--left.el-tabs--card .el-tabs__nav
    border-right 1px solid #e4e7ed
  .el-tabs--card > .el-tabs__header
    border-bottom none 
  .el-tabs--left.el-tabs--card .el-tabs__item.is-left
    border-right none !important
    text-align center
  .el-tabs--left.el-tabs--card .el-tabs__item.is-left.is-active
      background-color #179aff
      color #ffffff
  .el-tabs__content
    background-color #ffffff
    min-height 640px
    box-shadow 0 1px 4px rgba(0,21,41,.08)
    border-radius 4px
    position relative
    transition all .3s
    font-size 14px
  .tab-pane-header
    border-bottom 1px solid #e8e8e8
    margin-bottom -1px
    min-height 48px
    line-height 48px
    padding 0 16px
    font-size 14px
  .tab-pane-body
    padding 16px
    width 60%
    margin-bottom 48px
  .tab-pane-body-intro
    padding 16px 32px 16px 16px
    margin-bottom 48px
  .tab-pane-body-video
    padding 16px
    .body-header
      min-height 48px
      border-bottom 1px dashed #e8e8e8
    .body-content
      margin 16px 16px 0px 16px
  .tab-pane-body-live
    padding 16px
    .body-header
      min-height 48px
      border-bottom 1px dashed #e8e8e8
    .body-content
      margin 16px 16px 0px 16px
    .el-tree-node__content
      margin-bottom 0px !important

  .tab-pane-footer
    min-height 48px
    line-height 48px
    border-top 1px solid #e8e8e8
    padding-left 128px
    width 100%
    position absolute
    bottom 0px
  .el-radio
    margin-right 0px
  .el-radio.is-bordered
    width 100px
  .el-notification__content
    margin-top 2px
  .return-list
    float right
    margin-top 8px
  .el-tabs .el-button i
    margin-right 4px
  .el-tree-node__expand-icon
    width 0px
    height 0px
    padding 0px !important
    overflow hidden
  .el-tree-node__content
    height 24px
  .el-tree > .el-tree-node.is-focusable > .el-tree-node__content
    border-radius 4px
    margin-bottom 8px
    padding 8px !important
    overflow hidden
    background #d9edf7
    border-left 6px solid #bce8f1
    border-right 6px solid #bce8f1
  .el-tree-node__content:hover
    background-color #d9edf7
  .el-tree > .el-tree-node.is-focusable > .el-tree-node__children
    padding-left 32px
    margin-bottom 12px
  .el-tree-node__children > .el-tree-node.is-focusable 
    margin-bottom 8px
    > .el-tree-node__content
      background #f3f3f4
      border-left 6px solid #e7eaec
      border-right 6px solid #e7eaec
      border-radius 4px
      padding 8px !important
      overflow hidden
  .video-tree-node
    line-height 40px
    width 100%
    span
      margin-right 8px
    .el-button i
      margin-right 0px
    .el-button--mini
      padding 7px 8px
    .chapter-node
      font-size 14px !important
      .right
        float right
        span
          margin-right 0px
    .video-node
      font-size 12px
      .right
        float right
        span
          margin-right 0px
      .video-identify
        border-radius 4px
        padding 1px 5px
        border 1px solid #c2c2c2
  .live-tree-node
    line-height 40px
    width 100%
    span
      margin-right 8px
    .el-button i
      margin-right 0px
    .el-button--mini
      padding 7px 8px
    .live-node
      font-size 14px
      .right
        float right
        span
          margin-right 0px
  .view-video
    background-color #5cdbd3
    border-color #5cdbd3
    color #fff
    &:hover,&:focus
      background #87e8de;
      border-color #87e8de
      color #fff
  .success-status-tag
    background-color #1c84c6
    color #FFFFFF
    border-radius 10px
    padding 2px 8px
  .warning-status-tag
    background-color #e6a23c
    color #FFFFFF
    border-radius 10px
    padding 2px 8px
  .live-time-tag
    font-size 10px
    background-color #1c84c6
    color #FFFFFF
    border-radius 10px
    padding 2px 8px
  .ape-aliyun-player-mask
    top 0
    left 0
    position fixed
    width 100%
    height 0
    z-index 99999
    display none
    transition opacity .3s linear,height 0s ease .3s
  .ape-aliyun-player-mask.is-video-preview
    height 100%
    display block
    background-color rgba(0,0,0,0.85)
    transition opacity ease-in-out 0.38s, visibility ease-in-out 0.38s
    .header-title
      color #ffffff
      text-align center
      margin-top 48px
      margin-bottom 24px
    .close-button
      position absolute
      top 32px
      right 120px
  .live-lecturer-user input,.live-datetimerange
    width 447px !important
  .live-stream
    margin-bottom 24px
    padding 0px 8px
    border-left 4px solid #179aff
    span
      display block
      padding-bottom 2px
      margin-bottom 8px
      border-bottom 1px solid #e8e8e8

</style>
