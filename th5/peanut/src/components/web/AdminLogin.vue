<!-- @tlh后台登录 -->
<template>
  <div>
    <div class="outer_label">
      <img class="inner_label login_logo" src="@/assets/logo.png" />
    </div>
    <el-row type="flex" class="row-bg" justify="center">
        <el-col :span="18">
            <div class="grid-content bg-purple-light">花生二手车后台管理系统</div>
        </el-col>
    </el-row>
    <div class="login_form">
      <input type="text" class="qxs-ic_user qxs-icon" placeholder="用户名" v-model="userName" maxlength="11"/>
      <input type="password" class="qxs-ic_password qxs-icon" placeholder="密码" v-model="password" maxlength="20"/>
      <input type="text" class="qxs-code qxs-icon" placeholder="验证码" style="width:50%" maxlength="6"/>
      <img
        id="imgIdentifyingCode"
        @click="getIdentifyingCode(true)"
        style="height:20px; width: 100px; cursor: pointer;"
        placeholder="点击更换"
        title="点击更换"
      />
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
export default {
  data() {
    return {
      userName: "",
      password: "",
      isBtnLoading: false
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
  methods: {
    // 验证码
    getIdentifyingCode: function(bRefresh) {
      let identifyCodeSrc = "https://www.xxx.xxx.xxx/imgCode";
      if (bRefresh) {
        identifyCodeSrc = "https://www.xxx.xxx.xxx/imgCode?" + Math.random();
      }
      let objs = document.getElementById("imgIdentifyingCode");
      objs.src = identifyCodeSrc;
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
    height: 220px;
    background: -webkit-linear-gradient(left, #000099, #2154FA);
    /* Safari 5.1 - 6.0 */
    background: -o-linear-gradient(right, #000099, #2154FA);
    /* Opera 11.1 - 12.0 */
    background: -moz-linear-gradient(right, #000099, #2154FA);
    /* Firefox 3.6 - 15 */
    background: linear-gradient(to right, #000099, #2154FA);
    /* 标准的语法 */
    /*background-color: #000099;*/
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
    padding-left: 12%;
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
  padding-top: 3%;
  padding-left: 10%;
  padding-right: 10%;
}

.qxs-ic_user {
  background: url("../assets/images/adminUser.png") no-repeat;
  background-size: 20px 20px;
  background-position: 3%;
  outline: none;
}

.qxs-ic_password {
  background: url("../assets/images/adminPwd.png") no-repeat;
  background-size: 20px 20px;
  background-position: 3%;
  outline: none;
}

.qxs-code {
  background: url("../assets/images/adminCode.png") no-repeat;
  background-size: 20px 20px;
  background-position: 5%;
  margin-bottom: 20px;
  outline: none;
}

.login_logo {
  height: 100%;
}

.login_btn {
  width: 100%;
  font-size: 16px;
  background: -webkit-linear-gradient(
    left,
    #000099,
    #2154fa
  ); /* Safari 5.1 - 6.0 */
  background: -o-linear-gradient(
    right,
    #000099,
    #2154fa
  ); /* Opera 11.1 - 12.0 */
  background: -moz-linear-gradient(
    right,
    #000099,
    #2154fa
  ); /* Firefox 3.6 - 15 */
  background: linear-gradient(to right, #000099, #2154fa); /* 标准的语法 */
  filter: brightness(1.4);
}

.divIdentifyingCode {
  position: absolute;
  top: 0;
  right: 0;
  z-index: 5;
  width: 102px; /*设置图片显示的宽*/
  height: 40px; /*图片显示的高*/
  background: #e2e2e2;
  margin: 0;
}

.el-row {
    margin-top: 40px;
}

.grid-content {
    border-radius: 4px;
    min-height: 36px;
    text-align: center;
    font-family: "Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;
    font-size: 20px;
}

.row-bg {
    padding: 10px 0;
}
</style>

