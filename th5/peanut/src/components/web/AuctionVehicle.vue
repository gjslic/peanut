<!-- 拍卖车辆信息 -->
<template>
  <div style="margin-bottom:50px" id="auctionBox">
    <el-row class="breadcrumb hidden-xs-only">
      <el-col :md="18" :offset="3" class="hidden-xs-only auctionTitle">
        <el-col :sm="3" class="largeTitle">
          <h3>竞拍中({{vehicleArr.length}})</h3>
        </el-col>
        <el-col :sm="5" :offset="16" class="SortBase">
          <el-col :sm="8">
            <span class="SortCarBase" @click="defaultBase">默认排序</span>
          </el-col>
          <el-col :sm="8">
            <span class="SortCarBase" @click="timeBase">
              最新
              <i class="el-icon-sort"></i>
            </span>
          </el-col>
          <el-col :sm="8">
            <span class="SortCarBase" @click="priceBase">
              价格
              <i class="el-icon-sort"></i>
            </span>
          </el-col>
        </el-col>
      </el-col>
    </el-row>
    <el-row class="outerFrame" :gutter="20">
      <el-col :sm="3" :xs="0" style="height:1px"></el-col>
      <el-col :md="18">
        <div class="infinite-list-wrapper" style="overflow:auto">
          <ul class="list" v-infinite-scroll="load" infinite-scroll-disabled="disabled">
            <li v-for="(item,index) in count" class="list-item" :key="index">
              <el-col :sm="24" :xs="24" class="outerFrameBox">
                <el-card shadow="hover">
                  <div class="vehicleImg">
                    <el-image style="width:100%;height:100%" :src="vehicleArr[index].img"></el-image>
                  </div>
                  <P class="vehicleName">
                    <span class="logo">花生</span>
                    {{vehicleArr[index].vehicle_name}}
                  </P>
                  <div class="minVehicleBox">
                    <span class="vehicleTime">{{vehicleArr[index].vehicle_time}} | {{vehicleArr[index].introduce}}</span>
                    <span class="priceDistance">{{vehicleArr[index].vehicle_distance}}万公里</span>
                    <span class="priceFirst">竞拍金额{{vehicleArr[index].price}}万</span>
                    <p
                      class="vehiclePrice"
                      
                      v-if="vehicleArr[index][0].collection==0"
                    >
                      <i class="el-icon-star-off" @click="collection(vehicleArr[index].vehicle_id,0,vehicleArr[index].sell_id,index)"></i>
                      <span style="font-size: 14px;" @click="collection(vehicleArr[index].vehicle_id,0,vehicleArr[index].sell_id,index)">收藏</span>
                    </p>
                    <p
                      class="vehiclePrice"
                      v-else
                    >
                      <i class="el-icon-star-on" @click="collection(vehicleArr[index].vehicle_id,1,vehicleArr[index].sell_id,index)"></i>
                      <span style="font-size: 14px;" @click="collection(vehicleArr[index].vehicle_id,1,vehicleArr[index].sell_id,index)">已收藏</span>
                    </p>
                  </div>
                  <div class="vehicleBtn">
                    <p class="endTime">
                      距离结束：
                      <span>{{countDownList}}</span>
                    </p>
                    <el-button
                      type="warning"
                      plain
                      class="auctionBtn"
                      @click="open(vehicleArr[index].vehicle_id,vehicleArr[index].price,index,vehicleArr[index].sell_id)"
                      :loading="vehicleArr[index][0].flag"
                      id="offer"
                    >购买</el-button>
                  </div>
                </el-card>
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
import { getData, sendParam } from "../../network/home";
export default {
  name: "AuctionVehicle",
  data() {
    return {
      count: 0,
      loading: false,
      vehicleArr: [], //车辆信息数组
      num: 0,
      frequency: 0,
      timeBaseNum: 0, //排序 最新  2==升  1==降
      priceBaseNum: 0, //排序 最新  2==升  1==降
      countDownList: 0, //倒计时时间
      btnFlag: false
    };
  },
  computed: {
    noMore() {
      if (this.count >= this.vehicleArr.length) {
        return true;
      } else {
        return false;
      }
    },
    disabled() {
      return this.loading || this.noMore;
    }
  },
  mounted() {
    //获取车辆数据
    let data = {
        brandID: "",
        seriesID: "",
        price : "",
        timeBaseNum : "",
        priceBaseNum : "",
        token:localStorage.getItem('token')
      };
    sendParam("auction/Auction/vehicle",data)
      .then(res => {
        this.vehicleArr = res.data.data;
        //判断该时间是否有车辆
        if (this.vehicleArr.length > 0) {
          this.timeFun(res.data.surplusTime);
        }
        this.frequency = parseInt(this.vehicleArr.length / 4);
        this.num = this.vehicleArr.length - frequency;
        this.num = this.vehicleArr.length - frequency;
        if (frequency == 0) {
          this.count = this.num;
        } else {
          this.count = 4;
        }
      })
      .catch(err => {
        console.log(err);
      });
  },
  methods: {
    load() {
      this.loading = true;
      setTimeout(() => {
        if (this.count + 4 >= this.vehicleArr.length) {
          this.count = this.count + (this.vehicleArr.length - this.count);
        } else {
          this.count += 4;
        }

        this.loading = false;
      }, 1000);
    },
    vehicleSel(
      brandID,
      seriesID,
      price = "",
      timeBaseNum = "",
      priceBaseNum = ""
    ) {
      this.count = 0;
      let url = "auction/Auction/vehicle";
      let data = {
        brandID: brandID,
        seriesID: seriesID,
        price: price,
        timeBaseNum: timeBaseNum,
        priceBaseNum: priceBaseNum,
        token:localStorage.getItem('token')
      };
      sendParam(url, data)
        .then(res => {
          this.vehicleArr = res.data.data;
          this.frequency = parseInt(this.vehicleArr.length / 4);
          this.num = this.vehicleArr.length - frequency;
          if (frequency == 0) {
            this.count = this.num;
          } else {
            this.count = 4;
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    //排序
    timeBase() {
      this.priceBaseNum = 0;
      if (this.timeBaseNum == 0) {
        this.timeBaseNum = 1;
      } else {
        this.timeBaseNum = this.timeBaseNum == 1 ? 2 : 1;
      }
      this.$parent.timeBase(this.timeBaseNum);
    },
    //价格
    priceBase() {
      this.timeBaseNum = 0;
      if (this.priceBaseNum == 0) {
        this.priceBaseNum = 1;
      } else {
        this.priceBaseNum = this.priceBaseNum == 1 ? 2 : 1;
      }
      this.$parent.priceBase(this.priceBaseNum);
    },
    // 默认
    defaultBase() {
      this.timeBaseNum = 0;
      this.priceBaseNum = 0;
      this.$parent.defaultBase();
    },
    //收藏
    collection(id, flag) {},
    //判断时间
    timeFormat(param) {
      return param < 10 ? "0" + param : param;
    },
    //倒计时
    timeFun(timeStamp) {
      var interval = setInterval(() => {
        // 如果活动未结束，对时间进行处理
        let obj = null;
        if (timeStamp > 0) {
          // 获取时、分、秒
          let hou = parseInt((timeStamp % (60 * 60 * 24)) / 3600);
          let min = parseInt(((timeStamp % (60 * 60 * 24)) % 3600) / 60);
          let sec = parseInt(((timeStamp % (60 * 60 * 24)) % 3600) % 60);

          obj = {
            hou: this.timeFormat(hou),
            min: this.timeFormat(min),
            sec: this.timeFormat(sec)
          };
          timeStamp--;
        } else {
          // 活动已结束，全部设置为'00'
          obj = {
            hou: "00",
            min: "00",
            sec: "00"
          };
          clearInterval(interval);
          this.$parent.defaultBase();
        }
        this.countDownList = obj.hou + "时" + obj.min + "分" + obj.sec + "秒";
      }, 1000);
    },
    //出价框
    open(id, price,index,sellID) {
      if(localStorage.getItem('token')==null){
        this.$message({
              type: "warning",
              message: '请先登录'
            });
        return;
      }
      this.$confirm("确认好车辆", "确认出价", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(() => {
          
            this.$message({
              type: "success",
              message: "感谢您的出价,订单生成中请稍后"
            });
            offer.innerHTML = "订单生成中";
            this.$set(this.vehicleArr[index][0],'flag',true);
            this.hairPrice(id, price,index,sellID);
          
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消"
          });
        });
    },
    //发送价格
    hairPrice(id, price,index,sellID) {
      let url = "auction/Auction/Price";
      let data = {
        id: id,
        price: price,
        token:localStorage.getItem('token'),
        sellID:sellID
      };
      sendParam(url, data)
        .then(res => {
          if (res.data.code == 1) {
            var resDate = {
              msg: res.data.msg,
              type: "success"
            };
            setTimeout(() => {
            this.$router.push({ name: 'AddPersonalCenter'});
          }, 1000);
          } else {
            var resDate = {
              msg: res.data.msg,
              type: "warning"
            };
          }
          this.$message({
              type: resDate.type,
              message: resDate.msg
          });
          
        })
        .catch(err => {
          console.log(err);
        });
    },
    
    //收藏
    collection(vehicleID, flag, sellID,index ) {
      let url = "auction/Auction/collection";
      let data = {
        vehicleID: vehicleID,
        flag: flag,
        sellID: sellID,
        token:localStorage.getItem('token')
      };
      if (flag == 0) {
        this.$message({
          message: "收藏中，请稍后",
          type: "warning"
        });
      } else {
        this.$message({
          message: "取消中，请稍后",
          type: "warning"
        });
      }
      sendParam(url, data)
        .then(res => {
          if (flag == 0) {
            if (res.data.code == 1) {
              var resDate = {
                msg: "收藏成功",
                type: "success"
              };
              this.$set(this.vehicleArr[index][0],'collection',1);
            } else {
              var resDate = {
                msg: res.data.msg,
                type: "warning"
              };
            }
          } else {
            if (res.data.code == 1) {
              var resDate = {
                msg: "取消成功",
                type: "success"
              };
              this.$set(this.vehicleArr[index][0],'collection',0);
            } else {
              var resDate = {
                msg: "取消失败！请稍后再试",
                type: "warning"
              };
            }
          }
          this.$message({
            type: resDate.type,
            message: resDate.msg
          });
        })
        .catch(err => {
          console.log(err);
        });
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
.breadcrumb {
  margin: 15px 0;
  background-color: white;
  padding: 0;
}
.auctionTitle {
  position: relative;
  text-align: initial;
  color: #ff5837;
  height: 50px;
  border: 1px solid #f4f4f4;
  line-height: 50px;
}
.auctionTitle h3 {
  margin: 10px 0 0 14px;
}
.SortBase {
  font-size: 13px;
  text-align: center;
}
.SortCarBase {
  display: inline-block;
  border-right: 1px solid #eee;
  height: 30px;
  line-height: 30px;
  width: 100%;
  cursor: pointer;
  color: black;
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
  .minVehicleBox {
    margin: 0px;
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
    display: block;
  }
  .priceDistance {
    display: block;
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
    float: left;
    position: absolute;
    top: 9px;
    left: 18px;
  }
  .priceFirst {
    font-size: 12px;
    color: #f85d00;
  }
  .vehicleBtn {
    float: left;
  }
  .endTime {
    margin: 0px 0 0 17px;
    font-size: 13px;
    float: left;
    position: absolute;
    bottom: 50px;
    right: 98px;
  }
  .auctionBtn {
    position: absolute;
    bottom: 19px;
    right: 20px;
  }
  .tiShi {
    position: absolute;
    bottom: -40px;
    left: 45%;
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
    width: 24%;
    height: 195px;
    float: left;
    margin-bottom: 10px;
    margin-right: 32px;
  }
  .vehicleName {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font: 400 14px/16px "Microsoft YaHei";
    text-align: initial;
    font-size: 17px;
  }
  .vehicleTime {
    height: 27px;
    font-size: 12px;
    line-height: 24px;
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
    color: #ff7f34;
    display: block;
    text-align: initial;
    height: 33px;
    line-height: 33px;
    margin: 0px;
  }
  .priceDistance {
    height: 27px;
    font-size: 12px;
    line-height: 24px;
    color: #999;
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
    right: 7%;
    bottom: 40%;
  }
  .tiShi {
    position: absolute;
    bottom: -52px;
    left: 45%;
  }
}
</style>
<style>
@media (max-width: 768px) {
  .el-message-box{
    width: 250px;
    
  }
}

</style>