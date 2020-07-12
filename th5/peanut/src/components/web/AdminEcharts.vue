<!-- @tlh报表管理 -->
<template>
    <div>
        <el-row :gutter="20">
            <el-col :span="12">
                <div class="grid-content bg-purple">
                    <el-container>
                        <el-header> vue中插入Echarts示例</el-header>
                        <el-main>
                            <div id="chart_example"></div>
                        </el-main>
                    </el-container>
                </div>
            </el-col>
            <el-col :span="12">
                <div class="grid-content bg-purple">
                    <el-container>
                        <el-header>Header</el-header>
                        <el-main>Main</el-main>
                    </el-container>
                </div>
            </el-col>
        </el-row>
        <el-row :gutter="20">
            <el-col :span="12">
                <div class="grid-content bg-purple">
                    <el-container>
                        <el-header>Header</el-header>
                        <el-main>Main</el-main>
                    </el-container>
                </div>
            </el-col>
            <el-col :span="12">
                <div class="grid-content bg-purple">
                    <el-container>
                        <el-header>Header</el-header>
                        <el-main>Main</el-main>
                    </el-container>
                </div>
            </el-col>
        </el-row>
    </div>
</template>
 

<script>
  import echarts from 'echarts'
  export default {
    data() {
      return {}
    },
    mounted() {
      let this_ = this;
      let myChart = echarts.init(document.getElementById('chart_example'));
      let option = {
        color: ['#f44'],
        tooltip : {
          trigger: 'axis',
          axisPointer : {
            type : 'shadow'
          }
        },
        xAxis : [
          {
            type : 'category',
            data : ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月',],
            axisTick: {
              alignWithLabel: true
            }
          }
        ],
        yAxis : [
          {
            type : 'value'
          }
        ],
        series : [
          {
            name:'每月花费',
            type:'bar',
            barWidth: '60%',
            data:[995,666,444,858,654,236,645,546,846,225,547,356]
          }
        ]
      };
      myChart.setOption(option);

      //建议加上以下这一行代码，不加的效果图如下（当浏览器窗口缩小的时候）。超过了div的界限（红色边框）
      window.addEventListener('resize',function() {myChart.resize()});
    },
    methods: {},
    watch: {},
    created() {

    }
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
    background-color: #B3C0D1;
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
    background-color: #E9EEF3;
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

#chart_example {
  width: 100%;
  height: 360px;
  margin: 0 auto;
}
</style>