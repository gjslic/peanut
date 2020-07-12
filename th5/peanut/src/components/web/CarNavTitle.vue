// 购车详情页导航标题组件
<template>
  <div class="nav_title">
    <slot>购车服务</slot>
  </div>
</template>

<script>
export default {
  name: 'CarNavTitle'
}
</script>

<style>
 
  .nav_title{
    font-size: 18px;
    color: #333;
    border-left: 5px solid #ff5a37;
    margin-bottom: 20px;
    padding-left: 10px;
    margin-top: 20px;
  }
</style>
