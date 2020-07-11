<!-- 排序 -->
<template>
    <el-row class="breadcrumb">
      <el-col :md="18" :offset="3" class="hidden-xs-only SortCarBox">
       <el-col :sm="3" class="largeTitle"><span>全部车源(696)</span></el-col>
       <el-col :sm="5" :offset="16" class="SortBase">
         <el-col :sm="8"><span class="SortCarBase">默认排序</span></el-col>
         <el-col :sm="8"><span class="SortCarBase">最新</span></el-col>
         <el-col :sm="8"><span class="SortCarBase">价格</span></el-col>
       </el-col>
      </el-col>
    </el-row>
</template>

<script>
export default {
  name: "SortCar",
  data() {
    return {};
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.breadcrumb {
  margin: 15px 0;
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
}
.SortBase{
  font-size: 13px;
}
.SortCarBase{
  display: inline-block;
  border-right: 1px solid #eee;
  height: 30px;
  line-height: 30px;
  width: 100%;
}
</style>