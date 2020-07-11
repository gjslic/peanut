<template>
  <el-row style="margin-top:20px;padding: 25px;box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1)" class="hidden-xs-only hidden-sm-and-down">
    <el-col :md="10" :xs="24">
      <div class="car_barnd">
        <div class="brand_text">
          <!-- 品牌下拉框 -->
          <el-dropdown>
            <span class="el-dropdown-link">
              品牌
              <i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item>黄金糕</el-dropdown-item>
              <el-dropdown-item>狮子头</el-dropdown-item>
              <el-dropdown-item>螺蛳粉</el-dropdown-item>
              <el-dropdown-item disabled>双皮奶</el-dropdown-item>
              <el-dropdown-item divided>蚵仔煎</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </div>

        <!-- 品牌图标 -->
        <el-row>
          <!--3, v-for放置在需要打印的元素中 -->
          <el-col :md="4" :xs="8"  v-for="(item,index) in brandArr" :key="index">
            <div class="img_item"  @click="passbrand(item.brand_id)">
              <!-- 4,vue图片渲染需要require文件路径+数据库字段名 -->
              <img :src="item.brand_img" alt class="a_img" />
              <span  class="brand_img_text">{{item.brand_name}}</span>
              
            </div>
          </el-col>
        </el-row>
      </div>
    </el-col>
  <!-- 价格分类 区间 -->
    <el-col :md="7" :xs="7" class="hidden-xs-only">
      <div class="car_barnd">
        <div class="brand_text">价格</div>

          <!-- 价格区间 -->
          <el-row>

            <el-col :md="8" :sm="8">
                <div class="price_text" style="color:#ff5a37">
                    分期购
                </div>
            </el-col>

             <el-col :md="8" :sm="8">
                <div class="price_text" style="color:#ff5a37">
                    低月供
                </div>
            </el-col>

             <el-col :md="8" :sm="8" v-for="(price_item,index) in priceArr" :key="index">
                <div class="price_text"  @click="passprice(price_item.price_id,price_item.price)">
                    {{price_item.price}}万
                </div>
            </el-col>
          </el-row>
      </div>

    </el-col>
    <!-- 车型 -->
    <el-col :md="7" :xs="7" class="hidden-xs-only">
      <div class="car_barnd">
        <div class="brand_text">车型</div>

          <el-row>
            <el-col :md="8" :sm="8" v-for="(style_item,index) in styleArr" :key="index">
                 <div class="style_item" @click="passstyle(style_item.style_id)">
                    <img :src="style_item.style_img"  alt class="style_img" />
                    {{style_item.style_name}}
                  </div>
            </el-col>

            
          </el-row>

      </div>
    </el-col>
  </el-row>


</template>

<script>
//引入网络请求
import {request} from "../../network/request";
import { getData, sendParam} from "../../network/home";
export default {
  name: "Carbrand",
  //2,在data中，定义为空数组，返回出去
  data(){
    return{
      brandArr:[],
      priceArr:[],
      styleArr:[]
    }
  },

mounted(){
    // 获取到车标
    this.getbrand();

    //获取到价格范围
    this.getprice();

     //获取到车辆类型
    this.getstyle();
},

methods:{
  //获取到车标
  getbrand(){
    let url = '/homepage/Homepage/carbran';
    getData(url).then(res => {
      //this获取到数据
        this.brandArr = res.data;
    }).catch(err => {
      console.log(err);
    });
  },
  
  //获取到价格范围
  getprice(){
    let url = '/homepage/Homepage/carprice';
    getData(url).then(res => {
      //this获取到数据
        this.priceArr = res.data;
    }).catch(err => {
      console.log(err);
    });
  },

  //获取到车辆类型
  getstyle(){
    let url = '/homepage/Homepage/carstyle';
   getData(url).then(res => {
      //this获取到数据
        this.styleArr = res.data;
    }).catch(err => {
      console.log(err);
    });
  },

  //传递车标参数
  passbrand(id){
    console.log(id);
    let url = '/homepage/Homepage/passbrand';
    let data ={
      brandid:id
    }
    sendParam(url ,data).then(res => {
      this.passbrandArr = res.data;

      // 路由傳參到買車頁
      this.$router.push({
        name:"BuyCar",
        query:{
          brandid:id,
        }
      })
    }).catch(err => {
      console.log(err);
    });
  },

  //传递价格范围
  passprice(id,price){
    let url = '/homepage/Homepage/passprice';
    let data ={
      price_id:id
    }
    sendParam(url ,data).then(res => {
      this.passpriceArr = res.data;
      // 路由傳參到買車頁
      this.$router.push({
        name:"BuyCar",
        query:{
          price_id:price,
          id:id
        }
      })
    }).catch(err => {
      console.log(err);
    });
  },

  //传递车辆类型
    passstyle(id){
    
    let url = '/homepage/Homepage/passstyle';
    let data ={
      style_id:id
    }
    
    sendParam(url ,data).then(res => {
      this.passstyleArr = res.data;
      // 路由傳參到買車頁
      this.$router.push({
        name:"BuyCar"
      })
    }).catch(err => {
      console.log(err);
    });
  }
}


      


};



</script>

<style scoped>



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

.el-col {
  border-radius: 4px;
}
/* 汽车信息 */
.car_barnd {
  border: 1px solid #ccc;
  height: 300px;
}

/* 品牌，价格，车型 字体*/
.brand_text {
  border-bottom: 1px solid #ccc;
  height: 60px;
  padding-top: 15px;
  text-align: center;
  font-size: 2rem;
}

.brand_img_text{
      display: block;
    padding-right: 4px;
        width: 46px;
    text-align: center;
}
/* 下拉菜单字体 */
.el-dropdown-link {
  cursor: pointer;
  font-size: 2rem;
}
.el-dropdown-link:hover {
  color: #ff5a37;
}
/* 品牌连接 */
.brand_a {
  text-align: center;
  float: left;
  height: 80px;
  cursor: pointer;
}

/* 品牌图片 */
.a_img {
  display: block;
    /* margin: 1rem; */
    width: 85%;
    margin-bottom: 1rem;
    cursor: pointer;
}

/* 品牌图标盒子 */
.img_item {
    padding: 23px;
    text-align: center;
    /* margin-top: 1rem; */
    height: 105px;
}

/* 价格说明 */
.price_text {
  padding: 25px;
  text-align: center;
  cursor: pointer;
  font-size: 1.5rem;
}

.price_text:hover {
  color: #ff5a37;
}
/* 车型图标盒子 */
.style_item {
  padding: 20px;
  text-align: center;
  cursor: pointer;
  margin-top: 1rem;
}

/* 车型图片大小 */
.style_img {
  width: 100%;
  margin-bottom: 1rem;
}
</style>