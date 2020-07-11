
<template>
  <el-carousel :interval="5000" arrow="always" class="d_jump" :height="imgHeight">
    <el-carousel-item v-for="item in imgList" :key="item.id" class="branner_item">
      <el-row>
        <el-col :span="24">
          <img ref="imgHeight" :src="item.idView" class="banner_img" />
        </el-col>
      </el-row>
    </el-carousel-item>
  </el-carousel>
</template>

<script>
export default {
  name: "Banner",

  data() {
    return {
      imgHeight:'400px',


      // 图片需要引入, 否则无法显示
      imgList: [
        { id: 0, idView: require("../../assets/images/banner.png") },
        {
          id: 1,
          name: "详情",
          idView: require("../../assets/images/banner.png")
        },
        {
          id: 2,
          name: "推荐",
          idView: require("../../assets/images/banner02.jpeg")
        }
      ]
    };
  },
  methods: {}
};
</script>

<style scoped>
.el-carousel__item h3 {
  color: #475669;
  font-size: 18px;
  opacity: 0.75;
  line-height: 300px;
  margin: 0;
}



@media (min-width: 768px) {
    .banner_img{
      height:100%;
      width:100%;
    }
    .d_jump{
      height:400px;
    }
    .branner_item{
      height:400px;
    }
}

@media (max-width: 768px) {
    .banner_img{
      height:204px;
      width:100%;
    }
    .d_jump{
      height:204px;
    }
    .branner_item{
      height:200px;
    }
}
</style>