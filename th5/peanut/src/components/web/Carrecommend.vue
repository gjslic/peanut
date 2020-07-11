<template>

    <el-row style="box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1) margin-top:20px; ">
    <el-col :sm="12" :md="24">
      <div class="recommend_box">

        <el-row>
          <el-col  :md="4" :xs="24"><div class="recommend_text">
              为您推荐
          </div></el-col>
        </el-row>

      </div>
    </el-col>

    <el-row>
    
      <el-col :xs="24" :sm="6" :md="24" >
        <div class="recommend_img">
            <el-tabs v-model="activeName" @tab-click="handleClick">

                <el-tab-pane label="省油代步" name="first" class="name_text">
                  <el-row :gutter="5">
                    <el-col :xs="12" :md="6"  v-for="(reccar_item,index) in reccarArr" :key="index">
                      <div class="grid-content bg-purple-light" @click="passReccar(reccar_item.tab_id)">
                        <el-card class="box-card" shadow="hover" >
                            <div class="car_img" >

                               <el-row :gutter="5" >
                                  <el-col :xs="24" :md="24" class="car_img_box" >
                                      <img :src="reccar_item.img" alt="" style="width:100%;height:100%">
                                  </el-col>
                               </el-row>

                                <el-row :gutter="5">
                                  <el-col :xs="12" :md="12" class="car_text_box">
                                      {{reccar_item.vehicle_name}}
                                  </el-col>

                                   <el-col :xs="12" :md="12" class="car_text_box" style="color: #ff5837;">
                                      {{reccar_item.price}}万
                                  </el-col>
                               </el-row>

                            </div>
                        </el-card>
                      </div>
                    </el-col>
                  </el-row>
                </el-tab-pane>

                <el-tab-pane label="超值豪华" name="second" class="name_text">
                    <el-row :gutter="5">
                      <el-col :xs="12" :md="6"  v-for="(reccar_item,index) in reccarArr" :key="index">
                        <div class="grid-content bg-purple-light" @click="passReccar(reccar_item.tab_id)">
                          <el-card class="box-card" shadow="hover">
                              <div class="car_img" >

                                <el-row :gutter="5" >
                                    <el-col :xs="24" :md="24" class="car_img_box" >
                                        <img :src="reccar_item.img" alt="" style="width:100%;height:100%">
                                    </el-col>
                                </el-row>

                                  <el-row :gutter="5">
                                    <el-col :xs="12" :md="12" class="car_text_box">
                                        {{reccar_item.vehicle_name}}
                                    </el-col>

                                    <el-col :xs="12" :md="12" class="car_text_box" style="color: #ff5837;">
                                        {{reccar_item.price}}万
                                    </el-col>
                                </el-row>

                              </div>
                          </el-card>
                        </div>
                      </el-col>
                    </el-row>
                </el-tab-pane>

                <el-tab-pane label="家用首选" name="third" class="name_text">
                  <el-row :gutter="5">
                    <el-col :xs="12" :md="6"  v-for="(reccar_item,index) in reccarArr" :key="index">
                      <div class="grid-content bg-purple-light" @click="passReccar(reccar_item.tab_id)">
                        <el-card class="box-card" shadow="hover">
                            <div class="car_img" >

                               <el-row :gutter="5" >
                                  <el-col :xs="24" :md="24" class="car_img_box">
                                      <img :src="reccar_item.img" alt="" style="width:100%;height:100%">
                                  </el-col>
                               </el-row>

                                <el-row :gutter="5">
                                  <el-col :xs="12" :md="12" class="car_text_box">
                                      {{reccar_item.vehicle_name}}
                                  </el-col>

                                   <el-col :xs="12" :md="12" class="car_text_box" style="color: #ff5837;">
                                     {{reccar_item.price}}万
                                  </el-col>
                               </el-row>

                            </div>
                        </el-card>
                      </div>
                    </el-col>
                  </el-row>
                </el-tab-pane>

                <el-tab-pane label="保值神车" name="fourth" class="name_text">
                  <el-row :gutter="5">
                    <el-col :xs="12" :md="6"  v-for="(reccar_item,index) in reccarArr" :key="index">
                      <div class="grid-content bg-purple-light" @click="passReccar(reccar_item.tab_id)">
                        <el-card class="box-card" shadow="hover">
                            <div class="car_img" >

                               <el-row :gutter="5" >
                                  <el-col :xs="24" :md="24" class="car_img_box">
                                      <img :src="reccar_item.img" alt="" style="width:100%;height:100%">
                                  </el-col>
                               </el-row>

                                <el-row :gutter="5">
                                  <el-col :xs="12" :md="12" class="car_text_box">
                                      {{reccar_item.vehicle_name}}
                                  </el-col>

                                   <el-col :xs="12" :md="12" class="car_text_box" style="color: #ff5837;">
                                      {{reccar_item.price}}万
                                  </el-col>
                               </el-row>

                            </div>
                        </el-card>
                      </div>
                    </el-col>
                  </el-row>
                </el-tab-pane>
         
                <el-tab-pane label="都市白领" name="fifth" class="name_text">
                  <el-row :gutter="5">
                    <el-col :xs="12" :md="6"  v-for="(reccar_item,index) in reccarArr" :key="index">
                      <div class="grid-content bg-purple-light"  @click="passReccar(reccar_item.tab_id)">
                        <el-card class="box-card" shadow="hover">
                            <div class="car_img">

                               <el-row :gutter="5" >
                                  <el-col :xs="24" :md="24" class="car_img_box">
                                      <img :src="reccar_item.img" alt="" style="width:100%;height:100%">
                                  </el-col>
                               </el-row>

                                <el-row :gutter="5">
                                  <el-col :xs="12" :md="12" class="car_text_box">
                                      {{reccar_item.vehicle_name}}
                                  </el-col>

                                   <el-col :xs="12" :md="12" class="car_text_box" style="color: #ff5837;">
                                      {{reccar_item.price}}万
                                  </el-col>
                               </el-row>

                            </div>
                        </el-card>
                      </div>
                    </el-col>
                  </el-row>
                </el-tab-pane>

            </el-tabs>
        </div>
      </el-col>

    </el-row>

  </el-row>







</template>

<script>
import { getData, sendParam} from "../../network/home";
export default {
    name:'Carrecommend',
    data() {
      return {
        activeName: 'first',
        reccarArr:[]
      };
    },

    mounted(){
      this.getreccar();
    },

    methods: {
      handleClick(tab, event) {
        //点击标签发送参数获取到推荐车辆
        var tab_num = tab.name;
        let url = '/homepage/Homepage/reccar';
        let data = {
          tab_num:tab_num
        }
        sendParam(url,data).then(res => {
        //this获取到数据
        // console.log(res);
          this.reccarArr = res.data;
        }).catch(err => {
          console.log(err);
        });
        
        
      },

      //开始获取到推荐车辆
        getreccar(){
          let url = '/homepage/Homepage/getreccar';
          getData(url).then(res => {
            //this获取到数据
            // console.log(res);
              this.reccarArr = res.data;
          }).catch(err => {
            console.log(err);
          });
        },

        // 传递推荐车辆
        passReccar(id){
          let url = '/homepage/Homepage/passReccar';
          let data = {
            reccarid :id
          }
          console.log(data);
          sendParam(url ,data).then(res => {
            this.passbrandArr = res.data;
             // 路由傳參到買車頁
            this.$router.push({
              name:"BuyCar",
              params:{
                passstyleArr:'passstyleArr',
              }
            })
          }).catch(err => {
            console.log(err);
          });
        }
    
    }

}
</script>

<style scoped>
/* 卡片样式 */
 .text {
    font-size: 14px;
  }

  .item {
    padding: 18px 0;
  }

  .box-card{
    cursor: pointer;
  }



/* 列样式 */
   .el-col {
    border-radius: 4px;
  }
  .bg-purple-dark {
    background: #99a9bf;
  }
  .bg-purple {
    background: #d3dce6;
  }
  .bg-purple-light {
    background: #e5e9f2;
  }
  .grid-content {
    border-radius: 4px;
    min-height: 36px;
  }



  /* 电脑端 */
 @media (min-width: 768px){
    .recommend_box{
    margin-top: 20px;
  }

  .recommend_text{
  font-size: 3rem;
    /* padding: 18px; */
    padding-left: 30px;
    padding-top: 20px;
  }

  /* 车辆图片 */
  .recommend_img{
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
    height: 370px;
    padding-top: 20px;
    padding-left: 30px;
  }

  /* 装车图片 */
  .car_img_box{
    height: 216px;
    /* border: 1px solid; */
  } 

  .car_text_box{
     height: 50px;
    /* border: 1px solid; */
    text-align: center;
    font-size: 2rem;
    padding: 10px;
    overflow:hidden;
    white-space:nowrap;
    text-overflow:ellipsis;
  }
 
 }
 
/* 手机端 */
   @media (max-width: 768px){
      .recommend_text{
        font-size: 2rem;
          /* padding: 18px; */
          padding-left: 10px;
          /* padding-top: 20px; */
      }
      .recommend_img{
        padding-left: 10px;
      }

       /* 装车图片 */
        .car_img_box{
          height: 140px;
          /* border: 1px solid; */
        } 

        .car_text_box{
          height: 30px;
          /* border: 1px solid; */
          text-align: center;
          font-size: 1.3rem;
          padding: 10px;
        }

        .box-card{
          padding: 0;
        }
  }
 
</style>