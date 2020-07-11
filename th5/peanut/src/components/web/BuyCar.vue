<!-- 买车页  -->
<template>
  <div>
    <Menu />
    <Breadcrumb :breadcrumb-arr="breadcrumbArr"/>
    <QueryCar
      :brand-arr="brandArr"
      :series-arr="seriesArr"
      :price-arr="priceArr"
      :brandID="brandID"
      :priceNum="priceNum"
      @brandFun="brandFun"
      @seriesFun="seriesFun"
      @priceFun="priceFun"
    />
    <VehicleInfo ref="vehicleSel" />
    <Bottom />
  </div>
</template>

<script>
//导航栏
const Menu = () => import("@/components/web/Menu");
//引入查询车辆组件
const QueryCar = () => import("@/components/web/QueryCar");
//引入面包屑组件
const Breadcrumb = () => import("@/components/web/Breadcrumb");
//引入车辆信息显示
const VehicleInfo = () => import("@/components/web/VehicleInfo");
//底部
const Bottom = () => import("@/components/web/Bottom");
import { getData,sendParam } from "../../network/home";
export default {
  name: "BuyCar",
  data() {
    return {
      brandArr: [], //品牌数组
      seriesArr: [], //系列数组
      priceArr: [], //价格范围数组
      brandID:0,
      seriesID:0,
      priceID:'',
      timeBaseNum:'',
      priceBaseNum:'',
      breadcrumbArr:[
        {
          'content':'买车页'
        }
      ],
      search:'',
      passCityid:'',
      priceNum:0,

    };
  },
  components: {
    //组件
    QueryCar,
    Breadcrumb,
    VehicleInfo,
    Menu,
    Bottom
  },
  watch:{
    '$route':(function(to,from){
      if(to.query.vehicleName != null){
        this.search = to.query.vehicleName;
           this.$refs.vehicleSel.vehicleSel(this.brandID,this.seriesID,this.priceID,this.timeBaseNum,this.priceBaseNum,this.search,this.passCityid);
      }
      
    }) 
  },
  beforeRouteEnter (to, from, next) {
    next(vm => {
     
    // if(vm.$route.query.vehicleName != null){
       
    //     vm.search = vm.$route.query.vehicleName;
    //     console.log(vm.$route.query);
    //   }
      if(vm.$route.query.cityid != null){
        vm.passCityid = vm.$route.query.cityid;
      }
      if(vm.$route.query.brandid != null){
        vm.brandID = vm.$route.query.brandid;
      }
      if(vm.$route.query.price_id != null){
        vm.priceID = vm.$route.query.price_id;
        vm.priceNum = vm.$route.query.id;
      }

    });

    },
  mounted() {
    //获取品牌/系类/价格范围数据
    
    getData( "buyCar/Buycar/queryCar")
      .then(res => {
        this.brandArr = res.data.data[0];
        this.seriesArr = res.data.data[1];
        this.priceArr = res.data.data[2];
        this.reset();
        this.$refs.vehicleSel.vehicleSel(this.brandID,this.seriesID,this.priceID,this.timeBaseNum,this.priceBaseNum,this.search,this.passCityid);
      })
      .catch(err => {
        console.log(err);
      });
      
  },
  methods: {
    // 品牌点击
    brandFun: function(subscript) {
      this.brandID = subscript;
      this.seriesID = 0;
      //获取品牌下的系类
      let url = "buyCar/Buycar/seriesSel";
      let data = {"brandID":this.brandID};
      sendParam(url,data)
      .then(res => {
        this.seriesArr = res.data.data
      })
      .catch(err => {
        console.log(err);
      });
      //调用子级组件的函数
      this.reset();
      this.$refs.vehicleSel.vehicleSel(this.brandID,this.seriesID,this.priceID,this.timeBaseNum,this.priceBaseNum,this.search,this.passCityid);
    },
    // 品牌点击
    seriesFun: function(subscript) {
      this.seriesID = subscript;
      this.reset();
      this.$refs.vehicleSel.vehicleSel(this.brandID,this.seriesID,this.priceID,this.timeBaseNum,this.priceBaseNum,this.search,this.passCityid);
    },
    reset:function(){
      if(this.search == ''){
          this.search = this.search;
        }else{
          this.search='';
        }
    },
    //价格范围
    priceFun: function(subscript,price) {
      this.priceID = price;
      this.priceNum = subscript;
      this.reset();
      this.$refs.vehicleSel.vehicleSel(this.brandID,this.seriesID,price,this.timeBaseNum,this.priceBaseNum,this.search,this.passCityid);

    },
    //排序
    timeBase:function(num){
      this.timeBaseNum = num;
      console.log(this.search);
      this.$refs.vehicleSel.vehicleSel(this.brandID,this.seriesID,this.priceID,this.timeBaseNum,this.priceBaseNum,this.search,this.passCityid);

    },
    priceBase:function(num){
      this.priceBaseNum = num;
      this.$refs.vehicleSel.vehicleSel(this.brandID,this.seriesID,this.priceID,this.timeBaseNum,this.priceBaseNum,this.search,this.passCityid);
    },
    //默认排序
    defaultBase:function(){
      this.priceBaseNum = '';
      this.timeBaseNum = '';
      this.$refs.vehicleSel.vehicleSel(this.brandID,this.seriesID,this.priceID,this.timeBaseNum,this.priceBaseNum,this.search,this.passCityid);
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>