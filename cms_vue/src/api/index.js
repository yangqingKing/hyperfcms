import * as request from '@/utils/request'
import PASSPORT from './passport'
import COMMON from './common'
import STATISTICS from './statistics'
import MENU from './menu'
import PERMISSIONS from './permissions'
import ROLES from './roles'
import USER from './user'
import SETTING from './setting'
import CAROUSEL from './carousel'
import ADPOSITION from './ad_position'
import LINK from './link'
import CATEGORY from './category'
import ARTICLE from './article';

export default {
  // 通行证
  ...PASSPORT,
  // 通用
  ...COMMON,
  // 统计数据
  ...STATISTICS,
  // 菜单
  ...MENU,
  // 权限
  ...PERMISSIONS,
  // 角色
  ...ROLES,
  // 用户
  ...USER,
  // 基础配置
  ...SETTING,
  // 轮播图
  ...CAROUSEL,
  // 广告位
  ...ADPOSITION,
  // 友情链接
  ...LINK,
  // 分类管理
  ...CATEGORY,
  // 文章管理
  ...ARTICLE,

  // --------------------------------------  通用功能接口 -------------------------------------------
  /**
   * editorUploadFile
   * @description 富文本上传文件
   * @author YQ
   * @date 2019-01-31
   * @returns
   */
  editorUploadFile(data, ossUrl) {
    if(ossUrl){
      return request.post(ossUrl, data)
    }else{
      return request.post('/upload/editor', data)
    }
  },
  /**
   * @description 获取阿里云vod视频上传令牌信息
   * @author YM
   * @date 2019-02-25
   * @returns 
   */
  getVodToken(data) {
    return request.post('/upload/get_aliyun_vod_token',data)
  },
  
  // --------------------------------------  课程管理 -------------------------------------------
  /**
   * @description 获取课程分类列表
   * @author YM
   * @date 2019-03-07
   */
  getCourseCategoryList() {
    return request.get('/course/category_list')
  },
  /**
   * @description 获取课程列表
   * @author YM
   * @date 2019-02-01
   * @param {*} data
   * @returns 
   */
  getCourseList(data) {
    return request.get('/course/list', data)
  },
  /**
   * @description 课程保存
   * @author YM
   * @date 2019-02-06
   * @param {*} data
   * @returns 
   */
  saveCourse(data) {
    return request.post('/course/store', data)
  },
  /**
   * @description 获取课程详情
   * @author YM
   * @date 2019-02-11
   * @param {*} id
   * @returns 
   */
  getCourseInfo(id) {
    let data = {id:id}
    return request.post('/course/get_info', data)
  },
  /**
   * @description 根据id删除单条信息
   * @author YM
   * @date 2019-01-19
   * @param {*} data
   * @returns
   */
  deleteCourse(id) {
    let data = {id:id}
    return request.post('/course/delete',data)
  },
  /**
   * @description 获取课程视频章节list
   * @author YM
   * @date 2019-02-23
   * @param {*} id
   * @returns 
   */
  getCourseTreeVideoChapterList(id) {
    let data = {course_id:id}
    return request.post('/course/get_tree_video_chapter_list', data)
  },
  /**
   * @description 保存课程章节信息
   * @author YM
   * @date 2019-02-23
   * @param {*} data
   * @returns 
   */
  saveCourseVideoChapter(data) {
    return request.post('/course/save_video_chapter', data)
  },
  /**
   * @description 获取视频章节详情
   * @author YM
   * @date 2019-02-24
   * @param {*} id
   * @returns 
   */
  getCourseVideoChapterInfo(id) {
    let data = {id:id}
    return request.post('/course/get_video_chapter', data)
  },
  /**
   * @description 删除视频章节信息
   * @author YM
   * @date 2019-02-24
   * @param {*} id
   * @returns 
   */
  deleteCourseVideoChapterInfo(data) {
    return request.post('/course/delete_video_chapter',data)
  },
  /**
   * @description 保存视频信息
   * @author YM
   * @date 2019-02-24
   * @param {*} data
   * @returns 
   */
  saveCourseVideo(data) {
    return request.post('/course/save_video', data)
  },
  /**
   * @description 获取视频详情
   * @author YM
   * @date 2019-02-24
   * @param {*} id
   * @returns 
   */
  getCourseVideoInfo(id) {
    let data = {id:id}
    return request.post('/course/get_video', data)
  },
  /**
   * @description 删除视频信息
   * @author YM
   * @date 2019-02-24
   * @param {*} id
   * @returns 
   */
  deleteCourseVideoInfo(data) {
    return request.post('/course/delete_video',data)
  },
  /**
   * @description 排序课程视频章节
   * @author YM
   * @date 2019-02-24
   * @param {*} data
   * @returns 
   */
  orderCourseVideoChapter(data) {
    return request.post('/course/order_video_chapter',data)
  },
  /**
   * @description 获取课程视频的预览信息
   * @author YM
   * @date 2019-02-28
   * @param {*} data
   * @returns 
   */
  getCourseVideoPreview(videoId) {
    let data = {video_id:videoId}
    return request.post('/course/get_video_preview',data)
  },
  /**
   * @description 获取课程值直播list
   * @author YM
   * @date 2019-03-13
   * @param {*} id
   * @returns 
   */
  getCourseTreeLiveList(id) {
    let data = {course_id:id}
    return request.post('/course/get_tree_live_list', data)
  },
  /**
   * @description 课程直播搜索讲师
   * @author YM
   * @date 2019-03-13
   * @param {*} qs
   * @returns 
   */
  courseLiveSearchLecturer(qs){
    let data = {search:qs}
    return request.post('/course/live_search_lecturer',data)
  },
  /**
   * @description 保存课程直播信息
   * @author YM
   * @date 2019-03-14
   * @param {*} data
   * @returns 
   */
  saveCourseLive(data) {
    return request.post('/course/save_live', data)
  },
  /**
   * @description 获取直播详情
   * @author YM
   * @date 2019-03-14
   * @param {*} id
   * @returns 
   */
  getCourseLiveInfo(id) {
    let data = {id:id}
    return request.post('/course/get_live', data)
  },
  /**
   * @description 删除直播
   * @author YM
   * @date 2019-03-14
   * @param {*} id
   * @returns 
   */
  deleteCourseLiveInfo(id) {
    let data = {id:id}
    return request.post('/course/delete_live', data)
  },
  /**
   * @description 排序课程视频章节
   * @author YM
   * @date 2019-02-24
   * @param {*} data
   * @returns 
   */
  orderCourseLive(data) {
    return request.post('/course/order_live',data)
  },
  /**
   * @description 获取课程直播流相关信息
   * @author YM
   * @date 2019-03-14
   * @param {*} id
   * @returns 
   */
  getCourseLiveStreamInfo(id) {
    let data = {id:id}
    return request.post('/course/get_live_stream_info', data)
  },
  // --------------------------------------  视频集管理 -------------------------------------------
  /**
   * @description 获取视频集列表
   * @author YM
   * @date 2019-03-04
   * @param {*} data
   * @returns 
   */
  getVideosList(data) {
    return request.get('/videos/list', data)
  },
  /**
   * @description 获取视频集分类列表
   * @author YM
   * @date 2019-03-04
   * @returns 
   */
  getVideosCategoryList() {
    return request.get('/videos/category_list')
  },
  /**
   * @description 获取视频集分类列表
   * @author YM
   * @date 2019-03-04
   * @returns 
   */
  getVideosCategoryLabelList() {
    return request.get('/videos/category_label_list')
  },
  /**
   * @description 视频集保存
   * @author YM
   * @date 2019-02-06
   * @param {*} data
   * @returns 
   */
  saveVideos(data) {
    return request.post('/videos/store', data)
  },
  /**
   * @description 获取视频集详情
   * @author YM
   * @date 2019-02-11
   * @param {*} id
   * @returns 
   */
  getVideosInfo(id) {
    let data = {id:id}
    return request.post('/videos/get_info', data)
  },
  /**
   * @description 根据id删除单条信息
   * @author YM
   * @date 2019-01-19
   * @param {*} data
   * @returns
   */
  deleteVideos(id) {
    let data = {id:id}
    return request.post('/videos/delete',data)
  },
  /**
   * @description 获取视频集视频预览信息
   * @author YM
   * @date 2019-02-28
   * @param {*} data
   * @returns 
   */
  getVideosVideoPreview(videoId) {
    let data = {video_id:videoId}
    return request.post('/videos/get_video_preview',data)
  },
  // --------------------------------------  讲师管理 -------------------------------------------
  /**
   * @description 获取讲师列表
   * @author YM
   * @date 2019-03-04
   * @param {*} data
   * @returns 
   */
  getLecturerList(data) {
    return request.get('/lecturer/list', data)
  },
  /**
   * @description 课程直播搜索用户
   * @author YM
   * @date 2019-03-13
   * @param {*} qs
   * @returns 
   */
  lecturerSearchUser(qs){
    let data = {search:qs}
    return request.post('/lecturer/search_user',data)
  },
  /**
   * @description 讲师保存
   * @author YM
   * @date 2019-02-06
   * @param {*} data
   * @returns 
   */
  saveLecturer(data) {
    return request.post('/lecturer/store', data)
  },
  /**
   * @description 获取讲师详情
   * @author YM
   * @date 2019-02-11
   * @param {*} id
   * @returns 
   */
  getLecturerInfo(id) {
    let data = {id:id}
    return request.post('/lecturer/get_info', data)
  },
  /**
   * @description 根据id删除单条信息
   * @author YM
   * @date 2019-01-19
   * @param {*} data
   * @returns
   */
  deleteLecturer(id) {
    let data = {id:id}
    return request.post('/lecturer/delete',data)
  },
  /**
   * @description 获取讲师人才类型
   * @author YM
   * @date 2019-03-27
   * @returns 
   */
  getLecturerTalentType() {
    let data = {}
    return request.post('/lecturer/get_talent_type',data)
  },
  // --------------------------------------  直播管理 -------------------------------------------
  /**
   * @description 获取直播列表
   * @author YM
   * @date 2019-03-04
   * @param {*} data
   * @returns 
   */
  getLiveList(data) {
    return request.get('/live/list', data)
  },
  /**
   * @description 直播保存
   * @author YM
   * @date 2019-02-06
   * @param {*} data
   * @returns 
   */
  saveLive(data) {
    return request.post('/live/store', data)
  },
  /**
   * @description 获取直播详情
   * @author YM
   * @date 2019-02-11
   * @param {*} id
   * @returns 
   */
  getLiveInfo(id) {
    let data = {id:id}
    return request.post('/live/get_info', data)
  },
  /**
   * @description 根据id删除单条信息
   * @author YM
   * @date 2019-01-19
   * @param {*} data
   * @returns
   */
  deleteLive(id) {
    let data = {id:id}
    return request.post('/live/delete',data)
  },
  /**
   * @description 获取课程直播流相关信息
   * @author YM
   * @date 2019-03-14
   * @param {*} id
   * @returns 
   */
  getLiveStreamInfo(id) {
    let data = {id:id}
    return request.post('/live/get_stream_info', data)
  },
  /**
   * @description 直播搜索讲师
   * @author YM
   * @date 2019-03-13
   * @param {*} qs
   * @returns 
   */
  getSearchLecturer(qs){
    let data = {search:qs}
    return request.post('/live/search_lecturer',data)
  },
  // --------------------------------------  授权管理 -------------------------------------------
  /**
   * @description 获取授权列表
   * @author YM
   * @date 2019-03-04
   * @param {*} data
   * @returns 
   */
  getAuthorizationList(data) {
    return request.get('/authorization/list', data)
  },
  /**
   * @description 授权管理搜索用户
   * @author YM
   * @date 2019-03-13
   * @param {*} qs
   * @returns 
   */
  authorizationSearchUser(qs){
    let data = {search:qs}
    return request.post('/authorization/search_user',data)
  },
  /**
   * @description 授权管理搜索课程
   * @author YM
   * @date 2019-03-15
   * @param {*} qs
   * @returns 
   */
  authorizationSearchCourse(qs) {
    let data = {search:qs}
    return request.post('/authorization/search_course',data)
  },
  /**
   * @description 保存授权信息
   * @author YM
   * @date 2019-03-15
   * @param {*} data
   * @returns 
   */
  saveAuthorizationInfo(data) {
    return request.post('/authorization/store', data)
  },
}
