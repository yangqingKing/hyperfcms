// 跟级别的sate
const state = {
  isCollapse : false,
  menuList : [],
  routePath : null,
  userInfo: {},
  userPermissions : [],
  // 错误响应弹窗标志，避免重复弹出
  responseErrorMessageBoxMark:false
}

export default state