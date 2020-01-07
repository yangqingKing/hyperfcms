<template>
  <div>
    <el-upload
      class="ape-uploader"
      ref="vod-upload"
      name="file"
      action=""
      :http-request="handleUploadFile"
      :on-change="handleOnChange"
      :on-remove="handleOnRemove"
      :file-list="uploadFileList"
      :limit = "limit"
      :auto-upload="false" 
      :on-exceed="handleOnExceed">
      <el-button slot="trigger" size="small" type="primary">选择视频文件</el-button>
      <div slot="tip" class="el-upload__tip">{{'请上传文件，最多上传'+limit+'个。'}}</div>
    </el-upload>
    <div class="ape-aliyun-vod-mask" :class="{'start-upload':isStartUpload}">
      <el-progress :text-inside="true" :stroke-width="24" :percentage="uploadProgress" status="success"></el-progress>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import '@public/plugins/aliyun-upload-sdk/aliyun-oss-sdk-5.3.1.min.js'
import '@public/plugins/aliyun-upload-sdk/aliyun-upload-sdk-1.5.0.min.js'

export default {
  props:{
    uploadFileList:{
      type:Array,
      default:function(){
        return []
      }
    },
    allowTypeList:{
      type: Array,
      default: function(){
        return [
          'video/mp4', 'video/asf', 'video/avi', 'video/dat', 'video/dv', 'video/flv', 'video/f4v', 'video/mkv', 'video/mov', 
          'video/mpe', 'video/mpg', 'video/mpeg', 'video/mts', 'video/ogg', 'video/qt', 'video/rm', 'video/rmvb', 'video/swf', 
          'video/vob', 'video/wmv', 'video/webm', 'video/gif', 'video/mjpeg', 'video/quicktime', 
          'audio/aac', 'audio/ac3', 'audio/acm', 'audio/amr', 'audio/ape', 'audio/caf', 'audio/flac', 'audio/m4a', 'audio/mp3', 
          'audio/ra', 'audio/wav', 'audio/wma'
        ]
      }
    },
    allowSize:{
      type: Number,
      default: 1024*1024*1024 //默认1024M
    },
    // 是否开始上传
    isStartUpload:{
      type: Boolean,
      default:false
    }
  },
  data() {
    return {
      limit:1,
      uploader: null,
      // 阿里云jssdk初始化实例的参数
      timeout: '60000', // 超时
      partSize: '1048576', // 分片大小1M
      parallel: '5', // 并行上传分片个数
      retryCount: '3', // 网络故障重传次数
      retryDuration: '2', // 网络故障，重传间隔
      region: 'cn-shanghai', // 上传到的点播地域,接入区域
      userId: '', // 阿里云的账号ID
      // 阿里云上传凭证信息,后端返回
      uploadTokenInfo:{
        request_id: '',
        upload_address: '',
        upload_auth: '',
        video_id: '',
      },
      // 上传进度
      uploadProgress: 0,
    };
  },
  computed: {
    
  },
  watch: {
    // 监控状态值变化，执行上传
    "isStartUpload": function(val) {
      if (val && this.uploadTokenInfo.video_id) {
        this.$refs['vod-upload'].submit();
      }
    },
  },
  methods: {
    // 选择文件选中后的调用,上传成功和上传失败都会调用
    async handleOnChange(file) {
      // 文件验证是否符合上传要求
      if (this.uploadValidate(file)) {
        return true
      }
      let data = {
        title: file.name,
        filename:file.name,
      }
      this.uploadProgress = 0
      // 获取上传视频的相关配置
      let conf = await this.$api.getVodToken(data)
      this.uploadTokenInfo = conf['upload_token']
      this.handleJsSdkParam(conf['js_config'])
      let vodInfo = {filename:file.name,video_id:conf['upload_token'].video_id}
      this.$emit('handleUploadChange',vodInfo)      
    },
    // 处理超过上传个数
    handleOnExceed(files, fileList){
      this.$message.error('只允许选择一个视频！')
    },
    // 处理移除文件的回调
    handleOnRemove(file, fileList) {
      this.uploadTokenInfo = {}
      this.$emit('handleUploadRemove',file)
    },
    // 自定义上传的实现
    handleUploadFile(params) {
      this.uploader = this.createUploader()
      this.uploader.addFile(params.file, null, null, null)
      this.uploader.startUpload()
    },
    // 上传验证
    uploadValidate(file) {
      let checkSize = file.size > this.allowSize
      if (this.allowTypeList.indexOf(file.raw.type) == -1) {
        this.$message.error('文件类型不合法')
        this.$refs['vod-upload'].clearFiles()
        return true
      }
      if (file.size > this.allowSize) {
        this.$message.error('文件大小超过限制')
        this.$refs['vod-upload'].clearFiles()
        return true
      }
      return false
    },
    // 初始化上传实例
    createUploader (type) {
      let self = this
      let uploader = new AliyunUpload.Vod({
        timeout: self.timeout,
        partSize: self.partSize,
        parallel: self.parallel,
        retryCount: self.retryCount,
        retryDuration: self.retryDuration,
        region: self.region,
        userId: self.userId,
        // 添加文件成功
        addFileSuccess: function (uploadInfo) {

        },
        // 开始上传
        onUploadstarted: function (uploadInfo) {
          // console.log(uploadInfo);
          // 如果 uploadInfo.videoId 有值，调用刷新视频上传凭证接口，否则调用创建视频上传凭证接口
          if (!uploadInfo.videoId) {
            uploader.setUploadAuthAndAddress(uploadInfo, self.uploadTokenInfo.upload_auth, self.uploadTokenInfo.upload_address,self.uploadTokenInfo.video_id) 
          } else {
            // 如果videoId有值，根据videoId刷新上传凭证
            uploader.setUploadAuthAndAddress(uploadInfo, self.uploadTokenInfo.upload_auth, self.uploadTokenInfo.upload_address,self.uploadTokenInfo.video_id)
          }
        },
        // 文件上传成功
        onUploadSucceed: function (uploadInfo) {
          self.$emit('handleUploadSuccess',uploadInfo)
        },
        // 文件上传失败
        onUploadFailed: function (uploadInfo, code, message) {
          let info = "文件" + uploadInfo.file.name + "上传失败，错误code:" + code + ", 错误message:" + message
          self.$message.error(info)
        },
        // 文件上传进度，单位：字节, 可以在这个函数中拿到上传进度并显示在页面上
        onUploadProgress: function (uploadInfo, totalSize, progress) {
          // console.log("onUploadProgress:file:" + uploadInfo.file.name + ", fileSize:" + totalSize + ", percent:" + Math.ceil(progress * 100) + "%")
          self.uploadProgress = Math.ceil(progress * 100)   
        },
        // 上传凭证超时
        onUploadTokenExpired: function (uploadInfo) {
          // 上传大文件超时, 如果是上传方式一即根据 UploadAuth 上传时,需要根据 uploadInfo.videoId重新获取 UploadAuth
          let uploadAuth = ''
          uploader.resumeUploadWithAuth(uploadAuth)
        },
        // 全部文件上传结束,多个文件一起上传
        onUploadEnd: function (uploadInfo) {
      
        }
      })
      return uploader
    },
    // 处理阿里云jssdk上传参数
    handleJsSdkParam(jsConfig) {
      if (jsConfig['account_id']) {
        this.userId = jsConfig['account_id']
      }
      if (jsConfig['region_id']) {
        this.regionId = jsConfig['region_id']
      }
      if (jsConfig['part_size']) {
        this.partSize = jsConfig['part_size']
      }
      if (jsConfig['parallel']) {
        this.parallel = jsConfig['parallel']
      }
      if (jsConfig['retry_count']) {
        this.retryCount = jsConfig['retry_count']
      }
      if (jsConfig['retry_duration']) {
        this.retryDuration = jsConfig['retry_duration']
      }
    }
  },
  mounted() {

  },
}
</script>
<style lang="stylus">
  .ape-aliyun-vod-mask
    top 0
    left 0
    position fixed
    width 100%
    height 0
    opacity 0
    z-index 99999
    background-color rgba(0,0,0,0.65)
    transition opacity .3s linear,height 0s ease .3s
  .ape-aliyun-vod-mask.start-upload
    height 100%
    opacity 0.8
    transition opacity ease-in-out 0.38s, visibility ease-in-out 0.38s
    .el-progress
      margin auto 
      margin-top 15%
      width 70%
</style>
