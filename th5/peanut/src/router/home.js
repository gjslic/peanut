
const OldGoods = () => import('../components/admin/OldGoods.vue')
const Goods = () => import('../components/admin/Goods.vue')
const Chat = () => import('../components/admin/Chat')
const Welcome = () => import('../components/admin/Welcome')
const Users = () => import('../components/admin/Users')
const StaffManage = () => import('../components/admin/StaffManage')
const Jurisdiction = () => import('../components/admin/Jurisdiction')
const Order = () => import('../components/admin/Order')
const AdminRole = () => import('../components/admin/AdminRole')
const AdminEcharts = () => import('../components/admin/AdminEcharts')
const AdminNotice = () => import('../components/admin/AdminNotice')
const AdminComplain = () => import('../components/admin/AdminComplain')
const AdminInfo = () => import('../components/admin/AdminInfo')//个人中心




const homeChild = [
  {
    path: '/',
    name: '_Welcome',
    component: Welcome

  },
  {
    path: '/welcome',
    name: 'Welcome',
    component: Welcome

  },
  {
    path: '/AdminInfo',
    name: 'AdminInfo',
    component: AdminInfo
  },
  {
    path: '/goods',
    name: 'Goods',
    component: Goods
  },
  {
    path: '/OldGoods',
    name: 'OldGoods',
    component: OldGoods
  },
  {
    path: '/staffManage',
    name: 'staffManage',
    component: StaffManage
  },
  {
    path: '/Jurisdiction',
    name: 'Jurisdiction',
    component: Jurisdiction
  },
  {
    path: '/goods',
    name: 'Goods',
    component: Goods
  },
  {
    path: '/chat',
    name: 'Chat',
    component: Chat
  },
  {
    path: '/users',
    name: 'Users',
    component: Users
  },
  {
    path: '/AdminRole',
    name: 'AdminRole',
    component: AdminRole
  },
  {
    path: '/AdminEcharts',
    name: 'AdminEcharts',
    component: AdminEcharts
  },
  {
    path: '/AdminNotice',
    name: 'AdminNotice',
    component: AdminNotice
  },
  {
    path: '/AdminComplain',
    name: 'AdminComplain',
    component: AdminComplain
  },
  {
    path: '/order',
    name: 'order',
    component: Order
  }
]
export default homeChild 