<template>   
  <div id="Register">
    <el-form :model="ruleForm" status-icon :rules="rules" ref="ruleForm" class="demo-ruleForm">
      <el-row class="margin_bottom_20">
        <el-col :span="24">
          <div class="grid-content bg-purple-dark">
            <div class="demo-image">
              <div class="block" v-for="fit in fits" :key="fit">
                <el-image style="width: 100%; height:100%" :src="url" :fit="fit"></el-image>
              </div>
            </div>
          </div>  
        </el-col>
      </el-row>
      <el-row :gutter="20" class="margin_bottom_20">
        <el-col :span="18" :offset="3" style="text-align: center;">
          <el-link type="primary" :underline="false">
            <i class="el-icon-s-management font_size_30"></i><span class="font_size_30">花生二手车用户注册</span>
          </el-link>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="18" :offset="3">
          <div class="grid-content bg-purple">
            <el-form-item prop="name">
              <el-input type="text" placeholder="请输入名称" v-model="ruleForm.name" maxlength="5" show-word-limit onkeyup="value=value.replace(/[^\w\u4E00-\u9FA5]/g, '')">
                <i slot="prefix" class="el-input__icon el-icon-user"></i>
              </el-input>
            </el-form-item>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="20">
          <el-col :span="18" :offset="3">
              <div class="grid-content bg-purple">
                  <el-form-item prop="phone">
                      <el-input type="text" placeholder="请输入手机号" v-model="ruleForm.phone" maxlength="11" show-word-limit onkeyup="this.value=this.value.replace(/\D/g,'')">
                          <i slot="prefix" class="el-input__icon el-icon-phone-outline"></i>
                      </el-input>
                  </el-form-item>
              </div>
          </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="18" :offset="3">
          <div class="grid-content bg-purple">
            <el-form-item prop="account">
              <el-input type="text" placeholder="请输入账号" v-model="ruleForm.account" maxlength="11" show-word-limit onkeyup="this.value=this.value.replace(/\D/g,'')">
                <i slot="prefix" class="el-input__icon el-icon-key"></i>
              </el-input>
            </el-form-item>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="18" :offset="3">
          <div class="grid-content bg-purple">
            <el-form-item prop="password">
              <el-input type="password" placeholder="请输入密码" v-model="ruleForm.password" autocomplete="off" show-password onKeyUp="value=value.replace(/[\W]/g,'')">
                <i slot="prefix" class="el-input__icon el-icon-lock"></i>
              </el-input>
            </el-form-item>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="18" :offset="3">
          <div class="grid-content bg-purple">
              <el-form-item prop="newPassword">
                <el-input type="password" placeholder="确认密码" v-model="ruleForm.newPassword" autocomplete="off" show-password onKeyUp="value=value.replace(/[\W]/g,'')">
                  <i slot="prefix" class="el-input__icon el-icon-unlock"></i>
                </el-input>
              </el-form-item>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="10" :offset="3">
            <div class="grid-content bg-purple">
                <el-form-item prop="code">
                    <el-input type="text" placeholder="请输入验证码" v-model="ruleForm.code" maxlength="4" show-word-limit onKeyUp="value=value.replace(/[\W]/g,'')">
                        <i slot="prefix" class="el-input__icon el-icon-edit"></i>
                    </el-input>
                </el-form-item>
            </div>
        </el-col>
        <el-col :span="6" :offset="0">
            <template>
                <div @click="refreshCode">
                    <AddCode :identifyCode="identifyCode"></AddCode>
                </div>
            </template>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="18" :offset="3">
            <el-button  type="primary" :plain="true" class="button" @click="register">确认注册</el-button>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="18" :offset="3">
          <el-form-item>
            <router-link to="/Login" class="Jump_Login"><el-link type="primary" :underline="false">已有账号？点我登录</el-link></router-link>
            <router-link to="/" class="Jump_Login margin_r_5"><el-link type="warning" :underline="false">返回首页</el-link></router-link>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
  </div>
</template>
<script>

const AddCode = () => import('./AddCode')
import {getData , sendParam} from "../../network/home"

var phone = /^1[3456789]\d{9}$/; //手机号
var account=/^\d{11}$/ //账号
var password=/^[0-9a-zA-Z]{10,18}$/; //密码
export default {
    name: 'Register',
    components: {
      AddCode
    },
    data() {
        //注册名称判断(给用户看的)
        var validateName = (rule, value, callback) => {
            if (value === '') {
                callback(new Error('请输入注册名称'));
            }
        };
        //注册手机号判断(给用户看的)
        var validatePhone = (rule, value, callback) => {
            if (value === '') {
              callback(new Error('请输入手机号'));
            } else {
              if(value.match(phone)==null){
                callback(new Error('请输入正确的手机号'));
              }
            }
        };
        //注册账号判断(给用户看的)
        var validateAcco = (rule, value, callback) => {
            if (value === '') {
              callback(new Error('请输入注册账号'));
            } else {
              if(value.match(account)==null){
                callback(new Error('请输入正确的注册账号'));
              }
            }
        };
        //密码判断(给用户看的)
        var validatePass = (rule, value, callback) => {
          if (value === '') {
            callback(new Error('请输入注册密码'));
          } else {
            if(value.match(password)==null){
              callback(new Error('请输入10~18位,字母与数字组合'));
            } else {
              if (this.ruleForm.newPassword !== '') {
                this.$refs.ruleForm.validateField('newPassword');
              }
              callback();
            }
          }    
        };
        //相同密码判断(给用户看的)
        var validatePass2 = (rule, value, callback) => {
            if (value === '') {
                callback(new Error('请再次输入密码'));
            } else if (value !== this.ruleForm.password) {
                callback(new Error('两次输入密码不一致!'));
            } else {
                callback();
            }
        };
        //验证码判断(给用户看的)
        var validateCode = (rule, value, callback) => {
            if (value === '') {
                callback(new Error('请输入注册验证码'));
            }
        };
        return {
          fits: ['fill'],
          url: 'https://www.mercedes-benz.com.cn/content/dam/mb-cn/vehicles/sedan/e-class-l/feature1.jpg',
          identifyCodes: "0123456789qwertyuiopasdfghjklzxcvbnm",
          identifyCode: "",
          ruleForm: {
            //名称
            name: '',
            //手机号
            phone: '',
            //账号
            account: '',
            //密码
            password: '',
            //相同密码
            newPassword: '',
            //验证码
            code: ''
          },
          rules: {
            //名称
            name: [
                { validator: validateName, trigger: 'blur' }
            ],
            //手机号
            phone: [
                { validator: validatePhone, trigger: 'blur' }
            ],
            //账号
            account: [
                { validator: validateAcco, trigger: 'blur' }
            ],
            //密码
            password: [
                { validator: validatePass, trigger: 'blur' }
            ],
            //相同密码
            newPassword: [
                { validator: validatePass2, trigger: 'blur' }
            ],
            //验证码
            code: [
                { validator: validateCode, trigger: 'blur' }
            ]
          }
        };
    },
    //开局显示验证码位数 4
    mounted() {
      this.identifyCode = "";
      this.makeCode(this.identifyCodes, 4);
    },
    methods: {
        //注册点击事件
        register () {
          //名称判断
          if(this.ruleForm.name == ''){
            this.$message.error({
              message: '未输入名称',
              center: true
            });
          //手机判断
          }else if(this.ruleForm.phone == ''){
            this.$message.error({
              message: '未输入手机号',
              center: true
            });
          //手机正则判断
          }else if(this.ruleForm.phone.match(phone) == null){
            this.$message({
              message: '手机号格式输入错误',
              type: 'warning',
              center: true
            });
          //账号判断
          }else if(this.ruleForm.account == ''){
            this.$message.error({
              message: '未输入注册账号',
              center: true
            });
          //账号正则判断
          }else if(this.ruleForm.account.match(account) == null){
            this.$message({
              message: '注册账号格式输入错误',
              type: 'warning',
              center: true
            });
          //密码判断
          }else if(this.ruleForm.password == ''){
            this.$message.error({
              message: '未输入注册密码',
              center: true
            });
          //密码正则判断
          }else if(this.ruleForm.password.match(password) == null){
            this.$message({
              message: '注册密码格式输入错误',
              type: 'warning',
              center: true
            });
          //密码相同判断
          }else if(this.ruleForm.password != this.ruleForm.newPassword){
            this.$message({
              message: '俩次密码不相同',
              type: 'warning',
              center: true
            });
          //验证码判断
          }else if(this.ruleForm.code == ''){
            this.$message.error({
              message: '未输入验证码',
              center: true
            });
          //验证码相同判断
          }else if(this.identifyCode != this.ruleForm.code){
            this.$message({
              message: '验证码不正确',
              type: 'warning',
              center: true
            });
          }else{
            //注册
            let url = '/register/Register/register';
            //给php发送内容
            let data = {
                acc:this.ruleForm.account,
                name:this.ruleForm.name,
                phone:this.ruleForm.phone,
                password:this.ruleForm.password
              };
            sendParam(url, data).then(res => {
              if(res.data.code==1){
                //注册成功返回
                this.$message({
                  message: res.data.msg,
                  type: 'success',
                  center: true
                });
              }else{
                //注册失败返回
                this.$message({
                  message: res.data.msg,
                  type: 'warning',
                  center: true
                });
              }
            }).catch(err => {
              //异常返回
              console.log(err)
            })
          }
        },
        submitForm(formName) {
          this.$refs[formName].validate((valid) => {
              if (valid) {
                  alert('submit!');
              } else {
                  console.log('error submit!!');
                  return false;
              }
          });
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },
        randomNum(min, max) {
            return Math.floor(Math.random() * (max - min) + min);
        },
        refreshCode() {
            this.identifyCode = "";
            this.makeCode(this.identifyCodes, 4);
        },
        makeCode(o, l) {
            for (let i = 0; i < l; i++) {
                this.identifyCode += this.identifyCodes[
                this.randomNum(0, this.identifyCodes.length)
                ];
            }
            console.log(this.identifyCode);
        }
    }
}
</script>

<style scoped>
  .el-row {
    &:last-child {
      margin-bottom: 0;
    }
  }
  .font_size_30{
    font-size: 25px !important;
  }
  .margin_bottom_20{
    margin-bottom: 20px;
  }
  .margin_r_5{
        margin: 0 5px 0 0;
    }
  .el-col {
    border-radius: 4px;
  }
  .bg-purple-light {
    background: #e5e9f2;
  }
  .grid-content {
    border-radius: 4px;
    min-height: 36px;
  }
  .row-bg {
    padding: 10px 0;
    background-color: #f9fafc;
  }
  .button{
    width: 100% !important;
  }
  .Jump_Login{
    float: right;
  }
</style>
<style>
#Register .el-row{
  width: 100%;
}
</style>