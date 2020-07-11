<!-- 回到顶部 -->
<template>
  <div class="BackTopBox">
    <el-backtop style="right: 3%;bottom: 3%;">
      <i class="el-icon-arrow-up"></i>
    </el-backtop>
  </div>
</template>

<script>
export default {
  name: "BackTop",
  data() {
    return {};
  },
  components: {}
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
@media (max-width: 768px) {
.BackTopBox {
  width: 40px;
  height: 40px;
  position: fixed;
  right: 3%;
  bottom: 3%;
  z-index: 10;
}
}

@media (min-width: 768px) {
.BackTopBox {
  width: 40px;
  height: 40px;
  position: fixed;
  right: 3%;
  bottom: 3%;
  z-index: 10;
}
}
</style>