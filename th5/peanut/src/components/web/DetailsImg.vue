<!-- 轮播图 信息  -->
<template>
  <el-row>
    <el-col :sm="3" :xs="0">
      <p></p>
    </el-col>
    <el-col :sm="8" :xs="24">
      <div class="ImgBox">
        <el-carousel :interval="5000" arrow="always">
          <el-carousel-item>
            <el-image class="imgSize" src="https://c3.xinstatic.com/o/20200526/1700/5eccdab3a5ede926355_20.jpg" fit="scale-down"></el-image>
          </el-carousel-item>
          <el-carousel-item>
            <el-image class="imgSize" src="https://c3.xinstatic.com/o/20200526/1700/5eccdaa5d03e6120994_20.jpg" fit="scale-down"></el-image>
          </el-carousel-item>
          <el-carousel-item>
            <el-image class="imgSize" src="https://c3.xinstatic.com/o/20200526/1700/5eccdab7832f6650709_20.jpg" fit="scale-down"></el-image>
          </el-carousel-item>
          <el-carousel-item>
            <el-image class="imgSize" src="https://c3.xinstatic.com/o/20200526/1700/5eccdac0cfe25782810_20.jpg" fit="scale-down"></el-image>
          </el-carousel-item>
        </el-carousel>
      </div>
    </el-col>
    <el-col :sm="1" :xs="0">
      <p></p>
    </el-col>
    <el-col :span="8" :xs="24">
      <div class="DetailsInfo"></div>
    </el-col>
  </el-row>
</template>

<script>
export default {
  name: "DetailsImg",
  data() {
    return {};
  },
  components: {}
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.imgSize{
    height: 100%;
}
/* 手机 */
@media (max-width: 768px) {
  .ImgBox {
    height: 250px;
  }
  .DetailsInfo {
    height: 313px;
    background-color: red;
  }
}
/* 电脑 */
@media (min-width: 768px) {
  .ImgBox {
    height: 23.5em;
  }
  .DetailsInfo {
    height: 23.5em;
    background-color: red;
  }
}
</style>