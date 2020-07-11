<!-- @tlh后台登录 -->
<template>
  <div>
    <div class="outer_label">
      <img class="inner_label login_logo" src="@/assets/images/adminLoginBg.png" />
    </div>
    <el-row type="flex" class="row-bg" justify="center">
      <el-col :span="18">
        <div class="grid-content bg-purple-light">花生二手车后台管理系统</div>
      </el-col>
    </el-row>
    <div class="login_form">
      <input
        type="text"
        class="qxs-ic_user qxs-icon"
        placeholder="用户名"
        v-model="userName"
        maxlength="11"
        onkeyup="this.value=this.value.replace(/[^\w_]/g,'');"
      />
      <input
        type="password"
        class="qxs-ic_password qxs-icon"
        placeholder="密码"
        v-model="password"
        maxlength="16"
        onkeyup="this.value=this.value.replace(/^ +| +$/g,'')"
      />
      <input
        type="text"
        class="qxs-code qxs-icon"
        placeholder="验证码"
        style="width:50%"
        v-model="codeNum"
        maxlength="4"
        onkeyup="this.value=this.value.replace(/^ +| +$/g,'')"
      />
      <div class="code" @click="refreshCode">
        <SIdentify :identifyCode="identifyCode"></SIdentify>
      </div>
      <el-button
        class="login_btn"
        @click.native="login"
        type="primary"
        round
        :loading="isBtnLoading"
      >后台登录</el-button>
      <div style="margin-top: 10px">
        <span style="float: right;color: #A9A9AB">忘记密码？</span>
      </div>
    </div>
  </div>
</template>
 
 
<script>
import { request } from "../../network/request"; //引入axios请求
import { getData } from "../../network/home"; //引入axios封装的函数
import { sendParam } from "../../network/home"; //引入axios封装的函数
import SIdentify from "./IdentifyCode"; //引入验证码子组件

export default {
  components: {
    SIdentify
  },
  data() {
    return {
      userName: "",
      password: "",
      codeNum: "",
      isBtnLoading: false,
      identifyCodes: "1234567890",
      identifyCode: ""
    };
  },
  created() {
    if (
      JSON.parse(localStorage.getItem("user")) &&
      JSON.parse(localStorage.getItem("user")).userName
    ) {
      this.userName = JSON.parse(localStorage.getItem("user")).userName;
      this.password = JSON.parse(localStorage.getItem("user")).password;
    }
  },
  computed: {
    btnText() {
      if (this.isBtnLoading) return "登录中...";
      return "登录";
    }
  },
  mounted() {
    this.identifyCode = "";
    this.makeCode(this.identifyCodes, 4);
  },
  methods: {
    //验证码随机数
    randomNum(min, max) {
      return Math.floor(Math.random() * (max - min) + min);
    },
    //刷新验证码
    refreshCode() {
      this.identifyCode = "";
      this.makeCode(this.identifyCodes, 4);
    },
    //生成验证码
    makeCode(o, l) {
      for (let i = 0; i < l; i++) {
        this.identifyCode += this.identifyCodes[
          this.randomNum(0, this.identifyCodes.length)
        ];
      }
    },
    // 登录
    login() {
      if (!this.userName) {
        this.$message.error("请输入用户名");
        return;
      }
      if (!this.password) {
        this.$message.error("请输入密码");
        return;
      }
      if (!this.codeNum) {
        this.$message.error("请输入验证码");
        return;
      }
      if (this.codeNum != this.identifyCode) {
        this.$message.error("验证码错误");
      } else {
        let url = "alogin/index/index";
        let data = {
          userName: this.userName,
          password: this.password
        };
        sendParam(url, data)
          .then(res => {
            if (res.data.code == 1) {
              this.$message.success(res.data.msg);
              this.$router.push('/home');
              localStorage.setItem("token",JSON.stringify(res.data.data.token));
              localStorage.setItem("staffAcc",JSON.stringify(this.userName));
            } else {
              this.$message.error(res.data.msg);
            }
          })
          .catch(err => {});
      }
    }
  }
};
</script>


<style scoped>
.outer_label {
  position: relative;
  left: 0;
  top: 0;
  width: 100%;
  height: 517px;
  text-align: center;
  filter: brightness(1.4);
}
.inner_label {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  top: 0;
  margin: auto;
  height: 50px;
}
.qxs-icon {
  height: 40px;
  width: 90%;
  margin-bottom: 5px;
  padding-left: 30%;
  border: 0;
  border-bottom: solid 1px lavender;
}
.text-size12px {
  font-size: 12px;
}
.text-size14px {
  font-size: 14px;
}
.text-size16px {
  font-size: 16px;
}
.float-right {
  float: right;
}
.item-color {
  color: #848487;
}
.login_form {
  padding-top: 1%;
  padding-left: 30%;
  padding-right: 30%;
}
.qxs-ic_user {
  background: url("../../assets/images/adminUser.png") no-repeat;
  background-size: 20px 20px;
  background-position: 15%;
  outline: none;
}
.qxs-ic_password {
  background: url("../../assets/images/adminPwd.png") no-repeat;
  background-size: 20px 20px;
  background-position: 15%;
  outline: none;
}
.qxs-code {
  background: url("../../assets/images/adminCode.png") no-repeat;
  background-size: 20px 20px;
  background-position: 28%;
  margin-bottom: 20px;
  outline: none;
}
.login_logo {
  height: 100%;
  width: 100%;
}
.login_btn {
  width: 90%;
  font-size: 16px;
  background: -webkit-linear-gradient(
    left,
    #2154fa,
    #409eff
  ); /* Safari 5.1 - 6.0 */
  background: -o-linear-gradient(
    right,
    #2154fa,
    #409eff
  ); /* Opera 11.1 - 12.0 */
  background: -moz-linear-gradient(
    right,
    #2154fa,
    #2154fa
  ); /* Firefox 3.6 - 15 */
  background: linear-gradient(to right, #2154fa, #409eff); /* 标准的语法 */
  filter: brightness(1.4);
}
.el-row {
  margin-top: 20px;
}
.grid-content {
  border-radius: 4px;
  min-height: 36px;
  text-align: center;
  font-family: "Helvetica Neue", Helvetica, "PingFang SC", "Hiragino Sans GB",
    "Microsoft YaHei", "微软雅黑", Arial, sans-serif;
  font-size: 20px;
}
.row-bg {
  padding: 10px 0;
}
.code {
  height: 40px;
  float: right;
  margin-right: 100px;
}
</style>

