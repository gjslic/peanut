<!-- 查询车辆组件 -->
<template>
  <div>
    <div class="hidden-xs-only">
      <el-row class="keyword">
        <el-col :md="1" :sm="3" :offset="3">
          <div class="titleBox">
            <span>品牌:</span>
          </div>
        </el-col>
        
        <el-col :md="17" :sm="3">
          <div class="keyword-content">
            <el-row>
              <el-col :sm="1" :xs="1" class="content-mar">
                <div :class="{queryHei:true,brand:brandID==0}" @click="brandFun(0)">不限</div>
              </el-col>
              <el-col :sm="1" :xs="1" class="content-mar" v-for="(item,index) in brandArr" :key="index">
                <div :class="{queryHei:true,brand:brandID==item.brand_id}" @click="brandFun(item.brand_id)">{{item.brand_name}}</div>
              </el-col>
            </el-row>
          </div>
        </el-col>
      </el-row>
      <el-row class="keyword">
        <el-col :md="1" :sm="3" :offset="3">
          <div class="titleBox" style="border-top:0px">
            <span>车系:</span>
          </div>
        </el-col>
        <el-col :md="17" :sm="3">
          <div class="keyword-content" style="border-top:0px">
            <el-row>
              <el-col :sm="1" :xs="1" class="content-mar" >
                <div :class="{queryHei:true,series:seriesNum==0}" @click="seriesFun(0)">不限</div>
              </el-col>
              <el-col :sm="1" :xs="1" class="content-mar" v-for="(item,index) in seriesArr" :key="index">
                <div :class="{queryHei:true,series:seriesNum==item.series_id}" @click="seriesFun(item.series_id)">{{item.series_name}}</div>
              </el-col>
            </el-row>
          </div>
        </el-col>
      </el-row>
      <el-row class="keyword">
        <el-col :md="1" :sm="3" :offset="3">
          <div class="titleBox" style="border-top:0px">
            <span>价格:</span>
          </div>
        </el-col>
        <el-col :md="17" :sm="3">
          <div class="keyword-content" style="border-top:0px">
            <el-row>
              <el-col :sm="1" :xs="1" class="content-mar">
                <div :class="{queryHei:true,price:true,priceBox:priceNum==0}" @click="priceFun(0)">不限</div>
              </el-col>
              <el-col :sm="2" :xs="1" class="content-mar" v-for="(item,index) in priceArr" :key="index">
                <div :class="{queryHei:true,price:true,priceBox:priceNum==item.price_id}" @click="priceFun(item.price_id)" v-if="item.price==50">{{item.price}}万以上</div>
                <div :class="{queryHei:true,price:true,priceBox:priceNum==item.price_id}" @click="priceFun(item.price_id,item.price)" v-else>{{item.price}}万</div>
              </el-col>
            </el-row>
          </div>
        </el-col>
      </el-row>
    </div>
    <div class="hidden-sm-and-up">
      <el-row style="text-align: center;">
        <el-col :span="6">
          <el-dropdown trigger="click" @command="handleCommand">
            <span class="el-dropdown-link">
              智能排序
              <i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item command="defaultBase">默认排序</el-dropdown-item>
              <el-dropdown-item icon="el-icon-sort" command="timeBase">最新</el-dropdown-item>
              <el-dropdown-item icon="el-icon-sort" command="priceBase">价格</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </el-col>
        <el-col :span="6">
          <el-dropdown trigger="click" @command="brandFun">
            <span class="el-dropdown-link">
              品牌
              <i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item v-for="(item,index) in brandArr" :key="index"  :command="item.brand_id">{{item.brand_name}}</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </el-col>
        <el-col :span="6">
          <el-dropdown trigger="click" @command="seriesFun">
            <span class="el-dropdown-link">
              车系
              <i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item  :command="item.series_id" v-for="(item,index) in seriesArr" :key="index">{{item.series_name}}</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </el-col>
        <el-col :span="6">
          <el-dropdown trigger="click" @command="minPriceFun">
            <span class="el-dropdown-link">
              价格
              <i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item icon="el-icon-plus" :command="{'id':item.price_id,'price':item.price}" v-for="(item,index) in priceArr" :key="index">
                 <span  v-if="item.price==50">{{item.price}}万以上</span> 
                 <span  v-else>{{item.price}}万</span>
                 </el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </el-col>
      </el-row>
    </div>
  </div>
</template>

<script>
export default {
  name: "QueryCar",
  data() {
    return {
      seriesNum:0,
      timeBaseNum:0, //排序 最新  2==升  1==降
      priceBaseNum:0 //排序 最新  2==升  1==降
    };
  },
  methods:{
    // 品牌点击
    brandFun:function(subscript){
      this.seriesNum = 0;
      this.$emit('brandID',subscript);
      this.$emit('brandFun',subscript);
    },
    // 系类点击
    seriesFun:function(subscript){
      this.seriesNum = subscript;
      this.$emit('seriesFun',subscript);
    },
    //价格范围
    priceFun:function(subscript,price){
      this.$emit('priceFun',subscript,price);
      this.$emit('priceNum',subscript);
    },
    minPriceFun(da){
      this.priceFun(da.id,da.price);
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
    handleCommand(command){
      switch(command){
        case "defaultBase":
          this.defaultBase();
          break;
        case "timeBase":
          this.timeBase();
          break;
        case "priceBase":
          this.priceBase();
          break;
      }
    }
  },
  props:['brandArr','seriesArr','priceArr','priceNum','brandID']
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.keyword {
  font-size: 13px;
}
.titleBox {
  min-height: 30px;
  line-height: 30px;
  text-align: center;
  background-color: #fafafa;
  border: 1px solid #f4f4f4;
  border-right: 1px solid white;
}
.keyword-content {
  min-height: 30px;
  text-align: center;
  border: 1px solid #f4f4f4;
  border-left: 1px solid white;
  height: 100%;
}
.content-mar {
  margin-left: 10px;
  cursor: pointer;
}
.content-mar:hover .queryHei {
  color: #ff5a37;
}
.queryHei{
  height: 30px;
  line-height: 30px;
}
.brand{
  background-color: #ff7f34!important;
  color: white!important;
}
.series{
  background-color: #ff7f34!important;
  color: white!important;
}
.price{
  width: 56px;
  
}
.priceBox{
background-color: #ff7f34!important;
  color: white!important;
}
</style>