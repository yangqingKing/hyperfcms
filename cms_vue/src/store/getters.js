// 跟级别的Getters
const getters = {
  menuList: state => state.menuList,
  isCollapse: state => state.isCollapse,
  routePath: state => state.routePath,
  userInfo: state => state.userInfo,
  userPermissions: state => state.userPermissions,
  respErrMsgBoxMark: state => state.responseErrorMessageBoxMark,
}

export default getters