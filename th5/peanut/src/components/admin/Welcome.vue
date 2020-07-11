// 欢迎页
<template>
  <div>
    <div class="dv">
      欢迎来到后台管理系统
    </div>
    <canvas></canvas>
  </div>
</template>
<style>
.dv {
	width:600px;
	margin:0 auto;
	margin-top:100px;
	font-size:80px;
	text-align:center;
	background:-webkit-linear-gradient(left,#147B96,#E6D205 25%,#147B96 50%,#E6D205 75%,#147B96);
	color:transparent;
	-webkit-background-clip:text;
	background-size:200% 100%;
	animation:masked-animation 1s infinite linear;
}
@-webkit-keyframes masked-animation {
	0% {
	background-position:0 0;
}
100% {
	background-position:-100% 0;
}
}
</style>
<script>
  export default {
    name: 'Welcome',
    
  }
</script>