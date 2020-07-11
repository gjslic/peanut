<template>
    <div> 
        <Menu />
        <SellingCars/>
        <Bottom />
    </div>
</template>

<script>
//导航栏
const Menu = () => import("@/components/web/Menu");
//底部
const Bottom = () => import("@/components/web/Bottom");
import SellingCars from './SellingCars'

export default {
    name: 'AddSellingCars',
    components: {
        SellingCars,
        Menu,
        Bottom
    }
}
</script>

<style>

</style>