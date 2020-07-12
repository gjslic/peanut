<template>
    <div id="Login">
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
                        <i class="el-icon-s-promotion font_size_30"></i><span class="font_size_30">花生二手车用户登录</span>
                    </el-link>
                </el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col :span="18" :offset="3">
                    <div class="grid-content bg-purple">
                        <el-form-item prop="LoginAccount">
                            <el-input type="text" placeholder="请输入账号" v-model="ruleForm.LoginAccount" maxlength="11" show-word-limit onkeyup="this.value=this.value.replace(/\D/g,'')">
                                <i slot="prefix" class="el-input__icon el-icon-key"></i>
                            </el-input>
                        </el-form-item>
                    </div>
                </el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col :span="18" :offset="3">
                    <div class="grid-content bg-purple">
                        <el-form-item prop="Loginphone">
                            <el-input type="text" placeholder="请输入手机号" v-model="ruleForm.Loginphone" maxlength="11" show-word-limit onkeyup="this.value=this.value.replace(/\D/g,'')">
                                <i slot="prefix" class="el-input__icon el-icon-phone-outline"></i>
                            </el-input>
                        </el-form-item>
                    </div>
                </el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col :span="18" :offset="3">
                    <el-form-item prop="LoginPassword">
                        <el-input type="password" placeholder="请输入密码" v-model="ruleForm.LoginPassword" autocomplete="off" show-password onKeyUp="value=value.replace(/[\W]/g,'')">
                            <i slot="prefix" class="el-input__icon el-icon-lock"></i>
                        </el-input>
                    </el-form-item>
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
                    <el-button type="primary" plain class="LoginButton" @click="login">确认登录</el-button>
                </el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col :span="18" :offset="3">
                    <el-form-item>
                        <router-link to="/Register" class="Jump_Login"><el-link type="primary" :underline="false">还没账号？点我注册</el-link></router-link>
                        <router-link to="/Retrieve" class="Jump_Login margin_r_5"><el-link type="danger" :underline="false">忘记密码?</el-link></router-link>
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
// const store = () => import('../../store/index')

var phone = /^1[3456789]\d{9}$/; //手机号
var account=/^\d{11}$/ //账号
var password=/^[0-9a-zA-Z]{10,18}$/; //密码

export default {
    name: 'Login',
    components: {
        AddCode
    },
    data(){
        var validatePhone = (rule, value, callback) => {
            if (value === '') {
                callback(new Error('请输入手机号'));
            }
        };
        var validateAcco = (rule, value, callback) => {
            if (value === '') {
                callback(new Error('请输入登录账号'));
            }
        };
        var validatePass = (rule, value, callback) => {
            if (value === '') {
                callback(new Error('请输入登录密码'));
            }
        };
        var validateCode = (rule, value, callback) => {
            if (value === '') {
                callback(new Error('请输入登录验证码'));
            }
        };
        return {
            fits: ['fill'],
            url: 'https://www.mercedes-benz.com.cn/content/dam/mb-cn/vehicles/amg/amg-s63-s65/new0904-amg-s-front-pc.jpg', 
            identifyCodes: "0123456789qwertyuiopasdfghjklzxcvbnm",
            identifyCode: "",          
            ruleForm: {
                //登录手机号
                Loginphone: '',
                //登录账号
                LoginAccount: '',
                //登录密码
                LoginPassword: '',
                //验证码
                code: ''  
            },
            rules: {
                //登录手机号
                Loginphone: [
                    { validator: validatePhone, trigger: 'blur' }
                ],
                //登录账号
                LoginAccount: [
                    { validator: validateAcco, trigger: 'blur' }
                ],
                //登录密码
                LoginPassword: [
                    { validator: validatePass, trigger: 'blur' }
                ],
                //验证码
                code: [
                    { validator: validateCode, trigger: 'blur' }
                ]
            }
        }
    },
    //开局显示验证码位数 4
    mounted() {
        this.identifyCode = "";
        this.makeCode(this.identifyCodes, 4);
    },
    methods: {
        //登录点击事件
        login(){
            //登录判断
            if(this.ruleForm.Loginphone == '' || this.ruleForm.LoginAccount == '' || this.ruleForm.LoginPassword == ''){
                this.$message.error({
                    message: '未输入登录内容',
                    center: true
                });
            //验证码判断
            }else if(this.ruleForm.code == ''){
                this.$message.error({
                    message: '未输入验证码',
                    center: true
                });
            //登录内容格式判断
            }else if(this.ruleForm.Loginphone.match(phone) == null || this.ruleForm.LoginAccount.match(account) == null || this.ruleForm.LoginPassword.match(password) == null){
                this.$message({
                    message: '登录内容格式错误',
                    type: 'warning',
                    center: true
                });
            //验证码内容判断
            }else if(this.identifyCode != this.ruleForm.code){
                this.$message({
                    message: '验证码不正确',
                    type: 'warning',
                    center: true
                });
            }else{
                //登录
                let url = '/login/Login/login';
                //给php发送内容
                let data = {
                    acc:this.ruleForm.LoginAccount,
                    phone:this.ruleForm.Loginphone,
                    password:this.ruleForm.LoginPassword
                };
                sendParam(url, data).then(res => {
                    if(res.data.code==1){
                        localStorage.setItem('token',res.data.data.token);
                        // this.$store.commit('setUserToken',res.data.data.token);
                        // console.log(this.$store.state.UserToken);
                        //登录成功返回
                        this.$message({
                            message: res.data.msg,
                            type: 'success',
                            center: true
                        });
                    }else{
                        //登录失败返回
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
    /* .el-row {
        &:last-child {
        margin-bottom: 0;
        }
    } */
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
    .LoginButton{
        width: 100% !important;

    }
    .Jump_Login{
        float: right;
    }
</style>
<style>
#Login .el-row{
  width: 100%;
}
</style>