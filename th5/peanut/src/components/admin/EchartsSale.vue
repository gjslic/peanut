<!-- @tlh报表管理 -->
<template>
    <div>
        <el-row :gutter="20">
            <el-col :span="24">
                <div class="grid-content bg-purple">
                    <el-container>
                        <el-header>
                        <div class="block">
                        <!-- <span class="demonstration">月份搜索</span> -->
                        <el-date-picker
                            v-model="value2"
                            type="datetimerange"
                            :picker-options="pickerOptions"
                            @change="getSTime"
                            range-separator="至"
                            start-placeholder="开始日期"
                            end-placeholder="结束日期"
                            value-format="yyyy-MM-dd HH：mm：ss" 
                            align="right">
                        </el-date-picker>
                    </div>

                        </el-header>
                        <el-main>
                            <div id="chart_demo"></div>
                        </el-main>
                    </el-container>
                </div>
            </el-col>
        </el-row>
    </div>
</template>
 

<script>
  import echarts from 'echarts'
  import { request } from "../../network/request"; //引入axios请求
  import { getData } from "../../network/home"; //引入axios封装的函数
  import { sendParam } from "../../network/home"; //引入axios封装的函数
  export default {
    name: 'EchartsSale',
    data() {
      return {
        saleMonth: [],
        order:[],
         pickerOptions: {
          shortcuts: [{
            text: '最近一周',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit('pick', [start, end]);
            }
          }, {
            text: '最近一个月',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
              picker.$emit('pick', [start, end]);
            }
          }, {
            text: '最近三个月',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
              picker.$emit('pick', [start, end]);
            }
          }]
        },
        value1: [new Date(2000, 10, 10, 10, 10), new Date(2000, 10, 11, 10, 10)],
        value2: ''
      }
    },
    mounted() {
      

      this.getInfo()
    },
    methods: {
        getInfo(){
            sendParam('aecharts/index/sale','')
            .then(res => {        
                this.saleMonth = res.data.data.categories
                this.order = res.data.data.data
                this.xuanran();  
            })
            .catch(err => {  
                
            })
        },
        xuanran(){
            let this_ = this;
            let myChart = echarts.init(document.getElementById('chart_demo'));
            let option = {
                color: ['#409EFF'],
                title: {
                    text: '销量表'
                },
                tooltip : {
                trigger: 'axis',
                axisPointer : {
                    type : 'shadow'
                }
                },
                xAxis : [
                {
                    name: '月份',
                    type : 'category',
                    data : this.saleMonth,
                    axisTick: {
                    alignWithLabel: true
                    }
                }
                ],
                yAxis : [
                {
                    name : '订单数',
                    type : 'value'
                }
                ],
                series : [
                {
                    name:'每月订单数',
                    type:'bar',
                    barWidth: '60%',
                    data:this.order
                }
                ]
            };
            myChart.setOption(option);
            //建议加上以下这一行代码，不加的效果图如下（当浏览器窗口缩小的时候）。超过了div的界限（红色边框）
            window.addEventListener('resize',function() {myChart.resize()});
        },
        getSTime(val) {
            this.value2=val;//这个sTime是在data中声明的，也就是v-model绑定的值
            let dataArr = {
                starTime:this.value2[0],
                endTime:this.value2[1]
            }
            sendParam("aecharts/index/saleMonth", dataArr)
            .then(res => {
                if(res.data.code == 1000){
                    this.saleMonth = res.data.data.categories;
                    this.order = res.data.data.data;
                    this.xuanran();   
                }
                if(res.data.code == 1001){
                    this.$message.error("当前时间段暂无数据哦，为您展示默认信息");
                }    
            })
            .catch(err => {

            });    
        },


    },
   
  }
</script>


<style scoped>
  .el-row {
    margin-bottom: 20px;
    &:last-child {
      margin-bottom: 0;
    }
  }
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
  .row-bg {
    padding: 10px 0;
    background-color: #f9fafc;
  }

.el-header, .el-footer {
    background-color: #F2F6FC;
    color: #333;
    text-align: center;
    line-height: 60px;
  }
  
  .el-aside {
    background-color: #D3DCE6;
    color: #333;
    text-align: center;
    line-height: 200px;
  }
  
  .el-main {
    background-color: #F2F6FC;
    color: #333;
    text-align: center;
    line-height: 360px;
  }
  
  body > .el-container {
    margin-bottom: 40px;
  }
  
  .el-container:nth-child(5) .el-aside,
  .el-container:nth-child(6) .el-aside {
    line-height: 260px;
  }
  
  .el-container:nth-child(7) .el-aside {
    line-height: 320px;
  }

#chart_demo {
  width: 100%;
  height: 360px;
  margin: 0 auto;
}
</style>