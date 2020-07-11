<!-- 车辆信息 -->
<template>
  <div style="margin-bottom:50px">
    <el-row class="breadcrumb">
      <el-col :md="18" :offset="3" class="hidden-xs-only SortCarBox">
       <el-col :sm="3" class="largeTitle"><span>全部车源({{vehicleArr.length}})</span></el-col>
       <el-col :sm="5" :offset="16" class="SortBase">
         <el-col :sm="8"><span class="SortCarBase" @click="defaultBase">默认排序</span></el-col>
         <el-col :sm="8"><span class="SortCarBase" @click="timeBase">最新<i class="el-icon-sort"></i></span> </el-col>
         <el-col :sm="8"><span class="SortCarBase" @click="priceBase">价格<i class="el-icon-sort"></i></span></el-col>
       </el-col>
      </el-col>
    </el-row>
    <el-row class="outerFrame" :gutter="20">
      <el-col :sm="3" :xs="0" style="height:1px">
      </el-col>
      <el-col :md="18">
        <div class="infinite-list-wrapper" style="overflow:auto">
          <ul class="list" v-infinite-scroll="load" infinite-scroll-disabled="disabled">
            <li v-for="(itme,index) in count" class="list-item" :key="index">
              <el-col :sm="6" :xs="24" class="outerFrameBox">
                <div @click="detail(vehicleArr[index].vehicle_id)" style="text-decoration:none;">
                  <el-card shadow="hover">
                    <div class="vehicleImg">
                      <img
                        style="width:100%;height:100%"
                        :src="vehicleArr[index].img"
                      />
                    </div>
                    <P class="vehicleName">
                      <span class="logo">花生</span> {{vehicleArr[index].vehicle_name}}
                    </P>
                    <p class="minVehicleBox">
                      <span class="vehicleTime">{{vehicleArr[index].vehicle_time}}年 | {{vehicleArr[index].introduce}}</span>
                      <span class="vehiclePrice">{{vehicleArr[index].price}}万</span>
                      <span class="priceFirst">首付{{(vehicleArr[index].price*0.1).toFixed(2)}}万</span>
                    </p>
                    <div class="vehicleBtn hidden-xs-only">
                      <el-button type="warning" plain>立即购买</el-button>
                    </div>
                  </el-card>
                </div>
              </el-col>
            </li>
          </ul>
          <p v-if="loading" class="tiShi">加载中...</p>
          <p v-if="noMore" class="tiShi">没有更多了</p>
        </div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import { getData,sendParam } from "../../network/home";
export default {
  name: "VehicleInfo",
  data() {
    return {
      count: 0,
      loading: false,
      vehicleArr :[],//车辆信息数组
      num:0,
      frequency:0,
      timeBaseNum:0, //排序 最新  2==升  1==降
      priceBaseNum:0 //排序 最新  2==升  1==降
    };
  },
  computed: {
    noMore() {
      if(this.count>=this.vehicleArr.length){
        return true;
      }else{
        return false;
      }
      
    },
    disabled() {
      return this.loading || this.noMore;
    }
  },
   mounted() {
    //获取车辆数据
  },
  
  methods: {
    load() {
      this.loading = true;
      setTimeout(() => {
        if(this.count + 4 >= this.vehicleArr.length){
          this.count = this.count+(this.vehicleArr.length-this.count);
        }else{
          this.count += 4;
        }
        
        this.loading = false;
      }, 1000);
    },
    vehicleSel(brandID,seriesID,price='',timeBaseNum='',priceBaseNum='',search='',citID=''){
      
      this.count = 0;
      let url = "buyCar/Buycar/vehicle";
      let data = {"brandID":brandID,"seriesID":seriesID,"price":price,"timeBaseNum":timeBaseNum,"priceBaseNum":priceBaseNum,"search":search,"citID":citID};
      sendParam(url,data)
      .then(res => {
        this.vehicleArr = res.data.data;
        this.frequency = parseInt(this.vehicleArr.length/4);
        this.num = this.vehicleArr.length-frequency;
        if(frequency==0){
          this.count = this.num;
        }else{
          this.count = 4;
        }
      })
      .catch(err => {
        console.log(err);
      });
    },
    //排序
    timeBase(){
      this.priceBaseNum=0;
      if(this.timeBaseNum==0){
        this.timeBaseNum=1;
      }else{
        this.timeBaseNum= this.timeBaseNum==1 ? 2 : 1;
      }
      this.$parent.timeBase(this.timeBaseNum);
    },
    //价格
    priceBase(){
      this.timeBaseNum=0;
      if(this.priceBaseNum==0){
        this.priceBaseNum=1;
      }else{
        this.priceBaseNum= this.priceBaseNum==1 ? 2 : 1;
      }
      this.$parent.priceBase(this.priceBaseNum);
    },
    // 默认
    defaultBase(){
      this.timeBaseNum=0;
      this.priceBaseNum=0;
       this.$parent.defaultBase();
    },
    detail(id){
      this.$router.push({ name: 'Detail', query: { vehicleID: id }});
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.outerFrame {
  margin: 15px 0;
}
.list {
  list-style: none;
  margin: 0;
  padding: 0;
}
.tiShi {
  position: absolute;
  bottom: -52px;
  left: 45%;
}
.breadcrumb {
  margin: 15px 0;
  background-color: white;
  padding: 0;
}
.SortCarBox{
  height: 50px;
  border: 1px solid #f4f4f4;
  line-height: 50px;
}
.largeTitle{
  color: #ff5837;
  border-bottom: 1px solid #ff5837;
  height: 49px;
  text-align: center;
    font-size: 17px;
}
.SortBase{
  font-size: 13px;
  text-align: center;
}
.SortCarBase{
  display: inline-block;
  border-right: 1px solid #eee;
  height: 30px;
  line-height: 30px;
  width: 100%;
}
.SortCarBase{
  cursor: pointer;
}
@media (max-width: 768px) {
  /* 手机端 */
  .outerFrameBox {
    padding: 10px 5px;
    position: relative;
    border-bottom: 1px solid #f1f1f1;
    margin-bottom: 20px;
  }
  .vehicleImg {
    width: 8.5rem;
    height: 5.5rem;
    float: left;
    margin-right: 10px;
  }
  .logo {
    padding: 0 3px;
    background-color: #ff7f34;
    color: white;
  }
  .vehicleName {
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    margin: 0px;
    overflow: hidden;
    text-align: initial;
  }
  .minVehicleStateBox {
    text-align: initial;
    margin: 3px;
    font-size: 14px;
  }
  .minVehicleState {
    background: #f5f6f8;
    color: #8a99b5;
    padding: 0 3px;
  }
  .minVehicleBox {
    margin: 0px;
    text-align: initial;
  }
  .vehicleTime {
    font-size: 12px;
    color: #999;
    display: block;
  }
  .vehiclePrice {
    font-size: 16px;
    color: #f85d00;
  }
  .priceFirst {
    font-size: 12px;
    color: #f85d00;
  }
}
/* 电脑 */
@media (min-width: 768px) {
  .outerFrameBox {
    margin-top: 10px;
    padding: 0px 5px;
    position: relative;
  }
  .vehicleImg {
    width: 100%;
    height: 195px;
  }
  .vehicleName {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font: 400 14px/16px "Microsoft YaHei";
    height: 27px;
    line-height: 27px;
  }
  .vehicleTime {
    height: 14px;
    font-size: 12px;
    line-height: 14px;
    color: #999;
    display: block;
    text-align: initial;
  }
  .logo {
    padding: 0 3px;
    background-color: #ff7f34;
    color: white;
    position: absolute;
    top: 10px;
    left: 24px;
    font-size: 13px;
  }
  .vehiclePrice {
    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    color: #333;
    display: block;
    text-align: initial;
  }
  .priceFirst {
    padding: 8px 0 10px;
    display: block;
    font-size: 16px;
    color: #f85d00;
    text-align: initial;
  }
  .vehicleBtn {
    position: absolute;
    right: 23px;
    bottom: 10px;
  }
}
</style>