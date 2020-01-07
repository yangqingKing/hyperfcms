import * as request from '@/utils/request'

export default {
  /**
   * getFlowDataForDay
   * @description 流量统计
   * @author YQ
   * @date 2019-12-19
   * @returns
   */
  getFlowDataForDay(data) {
    return request.get('/statistics/flow', data)
  },
  /**
   * getRefererDataForDay
   * @description 请求来源
   * @author YQ
   * @date 2019-12-19
   * @returns
   */
  getRefererDataForDay(data) {
    return request.get('/statistics/referer', data)
  },
  /**
   * getBrowserDataForDay
   * @description 请求浏览器
   * @author YQ
   * @date 2019-12-19
   * @returns
   */
  getBrowserDataForDay(data) {
    return request.get('/statistics/browser', data)
  },
  /**
   * getPlatformData
   * @description 请求系统
   * @author YQ
   * @date 2019-12-19
   * @returns
   */
  getPlatformData(data) {
    return request.get('/statistics/platform', data)
  },
  /**
   * getRegionData
   * @description 地域分析
   * @author YQ
   * @date 2019-12-19
   * @returns
   */
  getRegionData(data) {
    return request.get('/statistics/region', data)
  },
  /**
   * getUrlData
   * @description 请求地址
   * @author YQ
   * @date 2019-12-19
   * @returns
   */
  getUrlData(data) {
    return request.get('/statistics/url', data)
  },
}
