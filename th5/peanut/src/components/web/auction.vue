<!-- 拍卖页  -->
<template>
  <div>
    <Menu />
    <Breadcrumb :breadcrumb-arr="breadcrumbArr" />
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
    <AuctionVehicle  ref="vehicleSel"/>
    <BackTop />
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
const AuctionVehicle = () => import("@/components/web/AuctionVehicle");
//引入回归顶部显示
const BackTop = () => import("@/components/web/BackTop");
//底部
const Bottom = () => import("@/components/web/Bottom");
import { getData, sendParam } from "../../network/home";
export default {
  name: "auction",
  data() {
    return {
      breadcrumbArr: [
        {
          content: "拍卖页"
        }
      ],
      brandID: 0,
      seriesID: 0,
      priceID: "",
      brandArr: [], //品牌数组
      seriesArr: [], //系列数组
      priceArr: [], //价格范围数组
      citID:1,
      priceNum:0,
    };
  },
  components: {
    Menu,
    QueryCar,
    Breadcrumb,
    AuctionVehicle,
    BackTop,
    Bottom
  },
  mounted() {
    //获取品牌/系类/价格范围数据
    getData("auction/Auction/queryCar")
      .then(res => {
        this.brandArr = res.data.data[0];
        this.seriesArr = res.data.data[1];
        this.priceArr = res.data.data[2];
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
      let url = "buycar/Buycar/seriesSel";
      let data = { brandID: this.brandID };
      sendParam(url, data)
        .then(res => {
          this.seriesArr = res.data.data;
        })
        .catch(err => {
          console.log(err);
        });
      //调用子级组件的函数
      this.$refs.vehicleSel.vehicleSel(
        this.brandID,
        this.seriesID,
        this.priceID,
        this.citID,
        this.timeBaseNum,
        this.priceBaseNum
      );
    },
    // 品牌点击
    seriesFun: function(subscript) {
      this.seriesID = subscript;
      this.$refs.vehicleSel.vehicleSel(
        this.brandID,
        this.seriesID,
        this.priceID,
        this.citID,
        this.timeBaseNum,
        this.priceBaseNum
      );
    },
    //价格范围
    priceFun: function(subscript, price) {
      this.priceID = price;
      this.priceNum = subscript;
      this.$refs.vehicleSel.vehicleSel(
        this.brandID,
        this.seriesID,
        price,
        this.citID,
        this.timeBaseNum,
        this.priceBaseNum
      );
    },
    //排序
    timeBase: function(num) {
      this.timeBaseNum = num;
      this.$refs.vehicleSel.vehicleSel(
        this.brandID,
        this.seriesID,
        this.priceID,
        this.citID,
        this.timeBaseNum
      );
    },
    priceBase: function(num) {
      this.priceBaseNum = num;
      this.$refs.vehicleSel.vehicleSel(
        this.brandID,
        this.seriesID,
        this.priceID,
        this.citID,
        "",
        this.priceBaseNum
      );
    },
    //默认排序
    defaultBase: function() {
      this.priceBaseNum = "";
      this.timeBaseNum = "";
      this.$refs.vehicleSel.vehicleSel(
        this.brandID,
        this.seriesID,
        this.priceID,
        this.citID,
      );
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>