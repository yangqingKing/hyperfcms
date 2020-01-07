<template>
  <div  class="prism-player" :id="playerId" :style="playStyle"></div>
</template>

<script>

export default {
  name: 'ApeAliyunPlayer',
  props:{
    playStyle: {
      type: String,
      default: ''
    },
    aliplayerSdkPath: {
      // Aliplayer 代码的路径
      type: String,
      default: 'https://g.alicdn.com/de/prismplayer/2.8.1/aliplayer-min.js'
    },
    autoPlay: {
      type: Boolean,
      default: false
    },
    isLive: {
      type: Boolean,
      default: false
    },
    // H5是否内置播放
    playsInline: {
      type: Boolean,
      default: false
    },
    width: {
      type: String,
      default: '100%'
    },
    height: {
      type: String,
      default: '320px'
    },
    controlBarVisibility: {
      type: String,
      default: 'always'
    },
    useH5Prism: {
      type: Boolean,
      default: false
    },
    useFlashPrism: {
      type: Boolean,
      default: false
    },
    videoId: {
      type: String,
      default: ''
    },
    playAuth: {
      type: String,
      default: ''
    },
    source: {
      type: String,
      default: ''
    },
    cover: {
      type: String,
      default: ''
    },
    format: {
      type: String,
      default: 'm3u8'
    },
    x5VideoPosition: {
      type: String,
      default: 'top'
    },
    x5Type: {
      type: String,
      default: 'h5'
    },
    x5Fullscreen: {
      type: Boolean,
      default: false
    },
    x5Orientation: {
      type: String,
      default: 'landscape'
    },
    autoPlayDelay: {
      type: Number,
      default: 0
    },
    autoPlayDelayDisplayText: {
      type: String
    },
  
  },
  data() {
    return {
      playerId: 'aliplayer_' + (Math.random() * 100000000000000000),
      scriptTagStatus: 0,
      aliPlayerInstance:null,

    };
  },
  methods: {
    // 加载阿里js播放器插件
    insertScriptTag () {
      const _this = this
      let aliPlayerScriptTag = document.getElementById('aliPlayerScriptTag')
      // 如果这个tag不存在，则生成相关代码tag以加载代码
      if (aliPlayerScriptTag === null) {
        aliPlayerScriptTag = document.createElement('script')
        aliPlayerScriptTag.type = 'text/javascript'
        aliPlayerScriptTag.src = this.aliplayerSdkPath
        aliPlayerScriptTag.id = 'aliPlayerScriptTag'
        let s = document.getElementsByTagName('head')[0]
        s.appendChild(aliPlayerScriptTag)
      }
      if (aliPlayerScriptTag.loaded) {
        _this.scriptTagStatus++
      } else {
        aliPlayerScriptTag.addEventListener('load', () => {
          _this.scriptTagStatus++
          aliPlayerScriptTag.loaded = true
          _this.initAliplayer()
        })
      }
      _this.initAliplayer()
    },
    // 初始化阿里云播放器
    initAliplayer(videoId,playAuth,source) {
      const _this = this
      // scriptTagStatus 为 2 的时候，说明两个必需引入的 js 文件都已经被引入，且加载完成
      if (_this.scriptTagStatus === 2 && _this.aliPlayerInstance === null && (_this.videoId || _this.playAuth || _this.source || videoId || playAuth || source)) {
        // Vue 异步执行 DOM 更新，这样一来代码执行到这里的时候可能 template 里面的 script 标签还没真正创建
        // 所以，我们只能在 nextTick 里面初始化 Aliplayer
        _this.$nextTick(() => {
          _this.aliPlayerInstance = new Aliplayer({
            id: _this.playerId,
            autoplay: _this.autoPlay,
            isLive: _this.isLive,
            playsinline: _this.playsInline,
            format: _this.format,
            width: _this.width,
            height: _this.height,
            controlBarVisibility: _this.controlBarVisibility,
            useH5Prism: _this.useH5Prism,
            useFlashPrism: _this.useFlashPrism,
            vid: _this.videoId || videoId,
            playauth: _this.playAuth || playAuth,
            source: _this.source || source,
            cover: _this.cover,
            x5_video_position: _this.x5VideoPosition,
            x5_type: _this.x5_type,
            x5_fullscreen: _this.x5Fullscreen,
            x5_orientation: _this.x5Orientation,
            autoPlayDelay: _this.autoPlayDelay,
            autoPlayDelayDisplayText: _this.autoPlayDelayDisplayText
          })
          // 绑定事件，当 AliPlayer 初始化完成后，将播放器实例通过自定义的 ready 事件交出去
          _this.aliPlayerInstance.on('ready', () => {
            this.$emit('ready', _this.aliPlayerInstance)
          })
          _this.aliPlayerInstance.on('play', () => {
            this.$emit('play', _this.aliPlayerInstance)
          })
          _this.aliPlayerInstance.on('pause', () => {
            this.$emit('pause', _this.aliPlayerInstance)
          })
          _this.aliPlayerInstance.on('ended', () => {
            this.$emit('ended', _this.aliPlayerInstance)
          })
          _this.aliPlayerInstance.on('liveStreamStop', () => {
            this.$emit('liveStreamStop', _this.aliPlayerInstance)
          })
          _this.aliPlayerInstance.on('m3u8Retry', () => {
            this.$emit('m3u8Retry', _this.aliPlayerInstance)
          })
          _this.aliPlayerInstance.on('hideBar', () => {
            this.$emit('hideBar', _this.aliPlayerInstance)
          })
          _this.aliPlayerInstance.on('waiting', () => {
            this.$emit('waiting', _this.aliPlayerInstance)
          })
          _this.aliPlayerInstance.on('snapshoted', () => {
            this.$emit('snapshoted', _this.aliPlayerInstance)
          })
        })
      }
    },
    // 播放视频
    play () {
      this.aliPlayerInstance.play()
    },
    // 暂停视频
    pause () {
      this.aliPlayerInstance.pause()
    },
    // 重播视频
    replay () {
      this.aliPlayerInstance.replay()
    },
    // 跳转到某个时刻进行播放，秒
    seek (time) {
      this.aliPlayerInstance.seek(time)
    },
    // 获取当前时间
    getCurrentTime () {
      return this.aliPlayerInstance.getCurrentTime()
    },
    // 获取视频总时长，返回的单位为秒
    getDuration () {
      this.aliPlayerInstance.getDuration()
    },
    // 获取当前的音量，返回值为0-1的实数ios和部分android会失效
    getVolume () {
      this.aliPlayerInstance.getVolume()
    },
    // 设置音量，vol为0-1的实数，ios和部分android会失效
    setVolume (vol) {
      this.aliPlayerInstance.setVolume(vol)
    },
    // 直接播放视频url，time为可选值（单位秒）目前只支持同种格式（mp4/flv/m3u8）之间切换暂不支持直播rtmp流切换
    loadByUrl (url, time) {
      this.aliPlayerInstance.loadByUrl(url, time)
    },
    // 设置播放速度
    setSpeed (speed) {
      this.aliPlayerInstance.setSpeed(speed)
    },
    // 设置播放器大小w,h可分别为400px像素或60%百分比chrome浏览器下flash播放器分别不能小于397x297
    setPlayerSize (w, h) {
      this.aliPlayerInstance.setPlayerSize(w, h)
    },
    // 目前只支持HTML5界面上的重载功能,暂不支持直播rtmp流切换m3u8）之间切换,暂不支持直播rtmp流切换
    replayByVidAndPlayAuth (videoId, playAuth) {
      if (this.aliPlayerInstance === null) {
        this.initAliplayer(videoId, playAuth)
      } else {
        this.aliPlayerInstance.replayByVidAndPlayAuth (videoId, playAuth) 
      }
      
    }
  },
  created () {
    if (window.Aliplayer !== undefined) {
      // 如果全局对象存在，说明编辑器代码已经初始化完成，直接加载编辑器
      this.scriptTagStatus = 2
      this.initAliplayer()
    } else {
      // 如果全局对象不存在，说明编辑器代码还没有加载完成，需要加载编辑器代码
      this.insertScriptTag()
    }
  },
  mounted() {
    if (window.Aliplayer !== undefined) {
        // 如果全局对象存在，说明编辑器代码已经初始化完成，直接加载编辑器
        this.scriptTagStatus = 2
        this.initAliplayer()
      } else {
        // 如果全局对象不存在，说明编辑器代码还没有加载完成，需要加载编辑器代码
        this.insertScriptTag()
      }
  },
}
</script>
<style>
  @import url(https://g.alicdn.com/de/prismplayer/2.8.1/skins/default/aliplayer-min.css)
</style>

<style lang="stylus">
  .prism-player
    margin 0 auto 
</style>
