// 欢迎页
<template>
  <div>
    <el-row>
        <el-col :span="8" class="server_box">
          <ServerItem />
        </el-col>
        <el-col :span="8" class="server_box">
          <ServerItem>
            <p slot="title_name">交易服务</p>
            <span slot="span_one">包过户</span>
            <span slot="span_two">上门服务</span>
            <span slot="span_three">车辆整备</span>
          </ServerItem>
        </el-col>
        <el-col :span="8" class="server_box">
          <ServerItem>
            <p slot="title_name">售后服务</p>
            <span slot="span_one">一年包退</span>
            <span slot="span_two">一年保修</span>
            <span slot="span_three">全国联保</span>
          </ServerItem>
        </el-col>
      </el-row>
  </div>
</template>
<style>

</style>
<script>
const ServerItem = () => import("./ServerItem");
  export default {
    name: 'BuyCarServer',
    components: {
      ServerItem
    }
  }
</script>