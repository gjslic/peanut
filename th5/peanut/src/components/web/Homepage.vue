<template>
<div>
      <Menu />
      <Banner />
      <Carbrand />
      <Carrecommend />
      <Bottom />
</div>


</template>

<script>
// 首页  轮播  品牌  推荐  底部
import Menu from '../web/Menu';
import Banner from '../web/Banner';
import Carbrand from '../web/Carbrand';
import Carrecommend from '../web/Carrecommend';
import Bottom from '../web/Bottom';
export default {
    name:'Homepage',
    components:{
        Menu,
        Banner,
        Carbrand,
        Carrecommend,
        Bottom
    }

}
</script>

<style scoped>


</style>