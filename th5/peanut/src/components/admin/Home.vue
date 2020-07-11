// 后台主页
<template>
  <el-container style="height: 100vh;">
    <el-header style="color: #fff">
      <span style="float: left; font-size: 24px;">Peanut后台管理系统</span>
      <div style="float: right">
        <i class="el-icon-switch-button layOut" @click="loginOut"></i>
        <el-dropdown>
          <span style="margin-right: 15px; color: #fff; font-size: 16px;">{{staffAcc}}</span>
          <el-dropdown-menu slot="dropdown">
            <router-link to="/AdminInfo">
              <el-dropdown-item>个人中心</el-dropdown-item>
            </router-link>
          </el-dropdown-menu>
        </el-dropdown>
      </div>
    </el-header>
    <el-container style="height: 500px; border: 1px solid #eee">
      <el-aside width="200px" style="background-color: rgb(238, 241, 246)">
        <el-menu v-for="(item,i) in menuData" :key="i" router :default-active="$route.path">
          <el-menu-item :index="item.menu_url" v-if="item.fid == 0 && item.child == 0">
            <i :class="item.menu_class"></i>
            <span slot="title">{{item.menu_name}}</span>
          </el-menu-item>

          <el-submenu index="1" v-else-if="item.fid == 0 && item.child == 1">
            <template slot="title">
              <i :class="item.menu_class"></i>
              <span>{{item.menu_name}}</span>
            </template>
            <div v-for="(val,n) in menuData" :key="n">
              <el-menu-item-group v-if="val.fid == item.id">
                <el-menu-item :index="val.menu_url">{{val.menu_name}}</el-menu-item>
              </el-menu-item-group>
            </div>
          </el-submenu>
        </el-menu>
      </el-aside>
      <el-container>
        <el-main>
          <router-view />
        </el-main>
      </el-container>
    </el-container>
    <el-footer class="foot-style">© Peanut - 所有权归花生</el-footer>
  </el-container>
</template>

<style>
.el-header {
  background-color: #b3c0d1;
  color: #333;
  line-height: 60px;
}
.el-container .el-submenu__title:hover {
  background-color: #b3c0d1;
}
.el-container .el-menu {
  background-color: none;
}
.el-aside {
  color: #333;
}
.foot-style {
  color: #b3c0d1;
  text-align: center;
  line-height: 60px;
}
.layOut {
  margin-right: 15px;
  color: #fff;
  font-size: 16px;
  cursor: pointer;
}
</style>

<script>
import { request } from "../../network/request"; //引入axios请求
import { getData, sendParam } from "../../network/home";
export default {
  name: "Home",
  components: {},
  data() {
    return {
      staffAcc: "",
      rid: "",
      menuData: []
    };
  },
  created() {
    this.getKoken();
  },
  methods: {
    // -------------网络请求--------
    getMenu() {
      let url = "/info/Center/getMenu";
      let data = {
        rid: this.rid
      };
      sendParam(url, data)
        .then(res => {
          if (res.data.code == 1) {
            let mid = res.data.data[0].menu_id;

            var menuID = mid.split(",");
            var newArr = [];
            for (var i = 0; i < menuID.length; i++) {
              for (var j = 0; j < res.data.menu.length; j++) {
                if (menuID[i] == res.data.menu[j].id) {
                  newArr.push(res.data.menu[j]);
                }
              }
            }
            this.menuData = newArr;
          } else {
            this.$message({ message: res.data.data.msg });
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    // 后台登出
    loginOut() {
      this.$confirm("此操作将退出后台登录, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          let token = JSON.parse(localStorage.getItem("token"));
          let url = "alogin/index/delToken";
          request({
            method: "post",
            url: url,
            headers: {
              "Access-Token": token
            }
          })
            .then(res => {
              if (res.data.code == 1) {
                this.$message.success({ message: res.data.msg });
                this.$router.replace("/AdminLogin");
              } else {
                this.$message({ message: "退出失败" });
              }
            })
            .catch(err => {
              console.log(err);
            });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消"
          });
        });
    },
    // 获取token
    getKoken() {
      let token = JSON.parse(localStorage.getItem("token"));
      let url = "alogin/index/validateToken";
      request({
        method: "post",
        url: url,
        headers: {
          "Access-Token": token
        }
      })
        .then(res => {
          if (res.data.code == 1) {
            let userMsg = JSON.parse(res.data.data);
            this.staffAcc = userMsg[0].name;
            this.rid = userMsg[0].role_id;
            this.getMenu();
          } else {
            this.$message({ message: "请先登录" });
            this.$router.push("/AdminLogin");
          }
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
};
</script>