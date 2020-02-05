import * as request from '@/utils/request'
import PASSPORT from './passport'
import COMMON from './common'
import STATISTICS from './statistics'
import MENU from './menu'
import PERMISSIONS from './permissions'
import ROLES from './roles'
import USER from './user'
import SETTING from './setting'

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

  // --------------------------------------  通用功能接口 -------------------------------------------
  /**
   * getOssToken
   * @description 获取上传凭证
   * @author YQ
   * @date 2019-01-19
   * @returns
   */
  getOssToken() {
    return request.get('/upload/get_upload_token')
  },
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
  // --------------------------------------  分类管理 -------------------------------------------
  /**
   * getCategoryList
   * @description 分类管理列表
   * @author YQ
   * @date 2019-01-19
   * @param {*} data
   * @returns
   */
  getCategoryList(data) {
    return request.get('/category/list', data)
  },
  /**
   * saveCategory
   * @description 保存分类
   * @author YQ
   * @date 2019-01-19
   * @param {*} data
   * @returns
   */
  saveCategory(data) {
    return request.post('/category/store', data)
  },
  /**
   * getCategoryInfo
   * @description 获取分类详情
   * @author YQ
   * @date 2019-01-19
   * @param {*} data
   * @returns
   */
  getCategoryInfo(id) {
    let data = {id:id}
    return request.post('/category/get_info', data)
  },
  /**
   * deleteCategoryInfo
   * @description 删除分类详情
   * @author YQ
   * @date 2019-01-19
   * @param {*} id
   * @returns
   */
  deleteCategoryInfo(id) {
    let data = {id:id}
    return request.post('/category/delete', data)
  },
  /**
   * @description 分类排序
   * @author YM
   * @date 2019-03-07
   * @param {*} ids
   * @returns 
   */
  orderCategory(ids) {
    let data = {ids:ids}
    return request.post('/category/order',data)
  },
  // --------------------------------------  轮播图管理 -------------------------------------------
  /**
   * @description 获取轮播图列表
   * @author YM
   * @date 2019-02-01
   * @param {*} data
   * @returns 
   */
  getCarouselList(data) {
    return request.get('/carousel/list', data)
  },
  /**
   * @description 轮播图保存
   * @author YM
   * @date 2019-02-06
   * @param {*} data
   * @returns 
   */
  saveCarousel(data) {
    return request.post('/carousel/store', data)
  },
  /**
   * @description 获取轮播图详情
   * @author YM
   * @date 2019-02-11
   * @param {*} id
   * @returns 
   */
  getCarouselInfo(id) {
    let data = {id:id}
    return request.post('/carousel/get_info', data)
  },
  /**
   * @description 根据id删除单条信息
   * @author YM
   * @date 2019-01-19
   * @param {*} data
   * @returns
   */
  deleteCarousel(id) {
    let data = {id:id}
    return request.post('/carousel/delete',data)
  },
  /**
   * @description 轮播图排序
   * @author YM
   * @date 2019-01-19
   * @param {*} data
   * @returns
   */
  orderCarousel(ids) {
    let data = {ids:ids}
    return request.post('/carousel/order',data)
  },
  /**
   * @description 获取轮播的类型列表
   * @author YM
   * @date 2019-12-03
   * @returns 
   */
  getCarouselTypeList() {
    return request.get('/carousel/type_list')
  },

  // --------------------------------------  广告位管理 -------------------------------------------
  /**
   * @description 获取广告位列表
   * @author YM
   * @date 2019-02-01
   * @param {*} data
   * @returns 
   */
  getAdPositionList(data) {
    return request.get('/ad_position/list', data)
  },
  /**
   * @description 广告位保存
   * @author YM
   * @date 2019-02-06
   * @param {*} data
   * @returns 
   */
  saveAdPosition(data) {
    return request.post('/ad_position/store', data)
  },
  /**
   * @description 获取广告位详情
   * @author YM
   * @date 2019-02-11
   * @param {*} id
   * @returns 
   */
  getAdPositionInfo(id) {
    let data = {id:id}
    return request.post('/ad_position/get_info', data)
  },
  /**
   * @description 根据id删除单条信息
   * @author YM
   * @date 2019-01-19
   * @param {*} data
   * @returns
   */
  deleteAdPosition(id) {
    let data = {id:id}
    return request.post('/ad_position/delete',data)
  },
  /**
   * @description 获取广告视频的预览信息
   * @author YM
   * @date 2019-02-28
   * @param {*} data
   * @returns 
   */
  getAdPositionVideoPreview(videoId) {
    let data = {video_id:videoId}
    return request.post('/ad_position/get_video_preview',data)
  },
  /**
   * @description 获取广告位的类型列表
   * @author YM
   * @date 2019-12-03
   * @returns 
   */
  getAdPositionTypeList() {
    return request.get('/ad_position/type_list')
  },

  // --------------------------------------  友情链接管理 -------------------------------------------
  /**
   * @description 获取友情链接列表
   * @author YM
   * @date 2019-02-01
   * @param {*} data
   * @returns 
   */
  getLinkList(data) {
    return request.get('/link/list', data)
  },
  /**
   * @description 友情链接保存
   * @author YM
   * @date 2019-02-06
   * @param {*} data
   * @returns 
   */
  saveLink(data) {
    return request.post('/link/store', data)
  },
  /**
   * @description 获取友情链接详情
   * @author YM
   * @date 2019-02-11
   * @param {*} id
   * @returns 
   */
  getLinkInfo(id) {
    let data = {id:id}
    return request.post('/link/get_info', data)
  },
  /**
   * @description 根据id删除单条信息
   * @author YM
   * @date 2019-01-19
   * @param {*} data
   * @returns
   */
  deleteLink(id) {
    let data = {id:id}
    return request.post('/link/delete',data)
  },
  /**
   * @description 友情链接排序
   * @author YM
   * @date 2019-01-19
   * @param {*} data
   * @returns
   */
  orderLink(ids) {
    let data = {ids:ids}
    return request.post('/link/order',data)
  },
  /**
   * @description 获取友链的类型列表
   * @author YM
   * @date 2019-12-03
   * @returns 
   */
  getLinkTypeList() {
    return request.get('/link/type_list')
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
  // --------------------------------------  文章管理 -------------------------------------------
  /**
   * @description 获取文章列表
   * @author YM
   * @date 2019-03-04
   * @param {*} data
   * @returns 
   */
  getArticleList(data) {
    return request.get('/article/list', data)
  },
  /**
   * @description 获取文章分类列表
   * @author YM
   * @date 2019-03-04
   * @returns 
   */
  getArticleCategoryList() {
    return request.get('/article/category_list')
  },
  /**
   * @description 获取文章分类列表
   * @author YM
   * @date 2019-03-04
   * @returns 
   */
  getArticleCategoryLabelList() {
    return request.get('/article/category_label_list')
  },
  /**
   * @description 文章保存
   * @author YM
   * @date 2019-02-06
   * @param {*} data
   * @returns 
   */
  saveArticle(data) {
    return request.post('/article/store', data)
  },
  /**
   * @description 获取文章详情
   * @author YM
   * @date 2019-02-11
   * @param {*} id
   * @returns 
   */
  getArticleInfo(id) {
    let data = {id:id}
    return request.post('/article/get_info', data)
  },
  /**
   * @description 根据id删除单条信息
   * @author YM
   * @date 2019-01-19
   * @param {*} data
   * @returns
   */
  deleteArticle(id) {
    let data = {id:id}
    return request.post('/article/delete',data)
  },
  /**
   * @description 获取文章列表
   * @author YM
   * @date 2019-12-05
   * @param {*} id
   * @returns 
   */
  getArticleAttachment(id) {
    let data = {id:id}
    return request.post('/article/get_attachment', data)
  },
  /**
   * @description 保存文章附件
   * @author YM
   * @date 2019-12-05
   * @param {*} id
   * @returns 
   */
  saveArticleAttachment(data) {
    return request.post('/article/save_attachment', data)
  },
  /**
   * @description 上
   * @author YM
   * @date 2019-12-05
   * @param {*} id
   * @returns 
   */
  deleteArticleAttachment(id) {
    let data = {id:id}
    return request.post('/article/delete_attachment',data)
  },

  // --------------------------------------  图集管理 -------------------------------------------
  /**
   * @description 获取图集列表
   * @author YM
   * @date 2019-03-04
   * @param {*} data
   * @returns 
   */
  getAtlasList(data) {
    return request.get('/atlas/list', data)
  },
  /**
   * @description 获取图集分类列表
   * @author YM
   * @date 2019-03-04
   * @returns 
   */
  getAtlasCategoryList() {
    return request.get('/atlas/category_list')
  },
  /**
   * @description 获取图集分类列表
   * @author YM
   * @date 2019-03-04
   * @returns 
   */
  getAtlasCategoryLabelList() {
    return request.get('/atlas/category_label_list')
  },
  /**
   * @description 图集保存
   * @author YM
   * @date 2019-02-06
   * @param {*} data
   * @returns 
   */
  saveAtlas(data) {
    return request.post('/atlas/store', data)
  },
  /**
   * @description 获取图集详情
   * @author YM
   * @date 2019-02-11
   * @param {*} id
   * @returns 
   */
  getAtlasInfo(id) {
    let data = {id:id}
    return request.post('/atlas/get_info', data)
  },
  /**
   * @description 根据id删除单条信息
   * @author YM
   * @date 2019-01-19
   * @param {*} data
   * @returns
   */
  deleteAtlas(id) {
    let data = {id:id}
    return request.post('/atlas/delete',data)
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
