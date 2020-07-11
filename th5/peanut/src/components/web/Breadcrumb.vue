<!-- 面包屑组件 -->
<template>
  <el-row class="breadcrumb">
    <el-col :md="10" :offset="3" class="hidden-xs-only">
      <el-breadcrumb separator-class="el-icon-arrow-right">
        <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item  v-for="(item,index) in breadcrumbArr" :key="index">{{item.content}}</el-breadcrumb-item>
      </el-breadcrumb>
    </el-col>
  </el-row>
</template>

<script>
export default {
  name: "Breadcrumb",
  data() {
    return {};
  },
  props:['breadcrumbArr']
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.breadcrumb {
  margin: 15px 0;
  background-color: white;
}
</style>