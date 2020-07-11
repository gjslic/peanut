<template>

    <div id="SellingCars">
        <!-- 导航栏 -->
        <Menu />
        <!-- 图片 -->
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
        <!-- 卖车表 -->
        <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">

            <el-row :gutter="20">
                <el-col :sm="5" :xs="1" style="height:1px"></el-col>
                <el-col :span="18" >

                    <!-- 选择城市 -->
                    <el-row :gutter="24">
                        
                         <el-form-item label="选择城市" prop="name">
                             
                            <el-input v-model="ruleForm.name" class="form_input" placeholder="请输入所在城市" 
                            onkeyup="value=value.replace(/[^\u4E00-\u9FA5]/g,'')" 
                            >
                            </el-input>
                          
                         </el-form-item>

                    </el-row>
                    <!-- 选择品牌 -->
                    <el-form-item label="车辆品牌" prop="brand">

                        <!-- <div class="block float_left margin_bottom_10 margin_right_4">
                        </div> -->

                            <!-- 只能输入中英文以及数字 -->
                             <el-input v-model="ruleForm.brand" class="form_input" maxlength="4" placeholder="请输入车辆品牌" 

                            @input="brandLimit">
                            </el-input>
                        
                    </el-form-item>

                    <!-- 选择车系 -->
                     <el-form-item label="车辆车系" prop="date1">

                        <div class="block float_left margin_bottom_10 margin_right_4">

                             <el-input v-model="ruleForm.date1" class="form_input" placeholder="请输入该品牌下的车系" 
                               onkeyup="value=value.replace(/[^\w\u4E00-\u9FA5]/g, '')"
                             >

                            </el-input>
                        </div>

                    </el-form-item>


                    <!-- 上牌时间 -->
                    <el-form-item label="上牌时间"  prop="date">
                        <el-date-picker v-model="ruleForm.date" type="date" placeholder="选择时间">
                        </el-date-picker>
                    </el-form-item>


                    <el-form-item label="行驶里程" prop="num">
                        <el-input type="text" v-model="ruleForm.num" placeholder="已万公里记录" maxlength="4" class="width_150" show-word-limit  onkeyup="value=value.replace(/[^\0-9\.]/g,'')" onpaste="value=value.replace(/[^\0-9\.]/g,'')" oncontextmenu = "value=value.replace(/[^\0-9\.]/g,'')"></el-input>
                    </el-form-item>


                    <el-form-item label="车况标签" prop="resource">
                        <el-radio-group v-model="ruleForm.resource">
                            <el-radio :label="1" class="margin_bottom_5">车况极佳</el-radio>
                            <el-radio :label="2" class="margin_bottom_5">稍微刮伤</el-radio>
                            <el-radio :label="3" class="margin_bottom_5">严重刮伤</el-radio>
                            <el-radio :label="4" class="margin_bottom_5">是否泡水</el-radio>
                        </el-radio-group>
                    </el-form-item>

                    <el-form-item label="卖车拍卖">
                        <el-radio-group v-model="radio">
                            <el-checkbox v-model="checked1" label="我要拍卖" border></el-checkbox>
                        </el-radio-group>
                    </el-form-item>
                    
                    <el-form-item label="车辆图片">
                        <el-upload class="upload-demo" action="https://jsonplaceholder.typicode.com/posts/" :on-preview="handlePreview" :on-remove="handleRemove" :file-list="fileList" list-type="picture">
                            <el-button size="small" type="primary" >点击上传车辆信息</el-button>
                            <div slot="tip" class="el-upload__tip ">只能上传jpg/png文件只取第一张</div>
                        </el-upload>
                    </el-form-item>
                    <el-form-item class="margin_left_20">
                        <div class="margin_bottom_10 margin_l_10">
                            <el-checkbox v-model="checked"><el-link type="info" :underline="false">我已阅读同意</el-link><el-link type="primary" :underline="false">《个人信息使用告知书》</el-link></el-checkbox>
                        </div>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('ruleForm')" class="margin_l_10">提交信息</el-button>
                         <el-button @click="resetForm('ruleForm')">重置</el-button>
                    </el-form-item>
                </el-col>
            </el-row>


        </el-form> 


        <Bottom />
    </div>
    
</template>

<script>
import Menu from "./Menu";
import Bottom from "./Bottom";
import { getData, sendParam} from "../../network/home";
export default {
    name: 'SellingCars',
    components:{
        Menu,
        Bottom,
    },

    data(){
        return {
            checked1: true,
            // 时间选择器
            cityArr:[],
            checked: true,
            input: '',
            input1: '',
            input2: '',
            fits: ['fill'],
            url: '//s5.xinstatic.com/static/newcar-www/widget/c2b_sale_new/c2b_car_sale_top/imgs/salecar-banner_bc02644.jpg',
            // {name: '', url: ''}
            fileList: [],
            value1: '',
            pickerOptions: {
                disabledDate(time) {
                    return time.getTime() > Date.now();
                }
            },
            //单选框
            radio: '',
            
            ruleForm: {
                name: '',
                brand: '',
                date1: '',
                date: '',
                num:'',
                delivery: false,
                type: [],
                resource: '',
                desc: '',
            },

            rules:{
                name: [
                    { required: true, message: '请输入城市名称(只允许输入中文)', trigger: 'blur' },
                    { min: 2, max: 5, message: '长度在 2 到 5 个字符', trigger: 'blur' }
                ],
                 brand: [
                    { required: true, message: '请输入车辆品牌', trigger: 'blur' },
                ],
                date1:[
                    { required: true, message: '请输入品牌系列', trigger: 'blur' },
                ],
                date:[
                     { required: true, message: '请输入上牌时间', trigger: 'blur' },
                ],
                num:[
                     { required: true, message: '请输入行驶里程', trigger: 'blur' },
                ],
                resource:[
                    { required: true, message: '请选择一个车辆信息', trigger: 'change' }
                ],  
            }
          
        }
    },

    methods: {
        //防止输入特殊符号
         brandLimit:function(){
             this.ruleForm.brand = this.ruleForm.brand.replace(/[ `~!@#$%^&*()_\-+=<>?:"{}|,.\/;'\\[\]·~！@#￥%……&*（）——\-+={}|《》？：“”【】、；‘’，。、]/g, '');
        },
        
        //判断是否填写输入框
       submitForm(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
                var cityname = this.ruleForm.name; //城市名称
                var brandname = this.ruleForm.brand; //品牌名称
                var stylename = this.ruleForm.date1; //系列名称
                var starttime = this.ruleForm.date; //上牌时间
                var usetime = this.ruleForm.num; //行驶里程
                var howcar = this.ruleForm.resource; //车辆状况
              let data ={
                    cityname:cityname,
                    brandname:brandname,
                    stylename:stylename,
                    starttime:starttime,
                    usetime:usetime,
                    howcar:howcar,
              }
                console.log(data);
                
            //判断是否阅读个人信息告知书
            if(this.checked == true){
                alert(1);
                // 判断该车是否拍卖
                if(this.checked1 == true){
                    console.log(111111);
                }else{
                    alert(0);
                }
            }else{
                 this.$message({
                    message: '请勾选个人信息告知书',
                    type: 'warning'
                });
            }
            

          } else {
            this.$message({
                message: '请确认是否已填写所有信息',
                type: 'warning'
            });
            return false;
          }
        });
      },

      //重置表单
       resetForm(formName) {
        this.$refs[formName].resetFields();
      },
        


        handleRemove(file, fileList) {
            console.log(file, fileList);
        },
        handlePreview(file) {
            console.log(file);
        },
    }
    
}
</script>

<style scoped>
/* //输入框 */
.form_input{
    width: 200px;
}


.margin_left_20{
    margin-left: -70px !important;
}
.margin_bottom_20{
    margin-bottom: 20px;
}
.margin_bottom_5{
    margin-bottom: 5px;
}
.margin_bottom_10{
    margin-bottom: 10px;
}
.margin_right_4{
    margin-right: 4px;
}
.width_150{
    width: 150px;
}
.width_160{
    width: 160px;
}
.float_left{
    float: left;
}
.margin_0_auto{
    margin: 0 auto;
}
@media (min-width: 768px){
    .margin_l_10{
        margin: 0 0 0 10%;
    }
}
@media (max-width: 768px) {
    .margin_l_10{
        margin: 0 0 0 10%;
    }
}
</style>

<style>
#SellingCars .el-upload__input{
    display: none;
}
#SellingCars .el-row{
    margin-left: 0px !important;
    width: 100%;
}
</style>