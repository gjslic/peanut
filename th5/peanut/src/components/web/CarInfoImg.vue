// 欢迎页
<template>
  <div>
    <el-row>
        <el-col :md="3" :sm="6" :xs="6">
          <div>
            <span class="carInfoItem">HUD显示</span>
          </div>
        </el-col>
        <el-col :md="3" :sm="6" :xs="6">
          <div>
            <span class="carInfoItem ci_1">自动驻车</span>
          </div>
        </el-col>
        <el-col :md="3" :sm="6" :xs="6">
          <div>
            <span class="carInfoItem ci_2">并线辅助</span>
          </div>
        </el-col>
        <el-col :md="3" :sm="6" :xs="6">
          <div>
            <span class="carInfoItem ci_3">方向盘换挡</span>
          </div>
        </el-col>
        <el-col :md="3" class="hidden-sm-only hidden-xs-only">
          <div>
            <span class="carInfoItem ci_4">随动转向大灯</span>
          </div>
        </el-col>
        <el-col :md="3" class="hidden-sm-only hidden-xs-only">
          <div>
            <span class="carInfoItem ci_5">主动自动</span>
          </div>
        </el-col>
        <el-col :md="3" class="hidden-sm-only hidden-xs-only">
          <div>
            <span class="carInfoItem ci_6">自动灯头</span>
          </div>
        </el-col>
        <el-col :md="3" class="hidden-sm-only hidden-xs-only">
          <div>
            <span class="carInfoItem ci_7">电动座椅</span>
          </div>
        </el-col>
      </el-row>
  </div>
</template>
<style>
@import "../../assets/css/detail.css";
</style>
<script>
  export default {
    name: 'CarInfoImg',
    
  }
</script>