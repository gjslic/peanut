<template>
  <div class="server_item">
    <p class="server_name">
      <slot name="title_name">
        <span>认证服务</span>
      </slot>
    </p>
    <el-row>
      <el-col :span="8" :xs="24">
        <span class="name_item">
          <i class="el-icon-circle-check"></i>
          <slot name="span_one">
            <span>315项检测</span>
          </slot>
        </span>
      </el-col>
      <el-col :span="8" :xs="24">
        <span class="name_item">
          <i class="el-icon-circle-check"></i>
          <slot name="span_two">
            <span>无重大事故</span>
          </slot>
        </span>
      </el-col>
      <el-col :span="8" :xs="24">
        <span class="name_item">
          <i class="el-icon-circle-check"></i>
          <slot name="span_three">
            <span>无水泡火烧</span>
          </slot>
        </span>
      </el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  name: "ServerItem"
};
</script>

<style scoped>
@media screen and (max-width:992px) {
  .name_item{
    padding: 3px 0 !important;
  }
  
  .server_item .server_name{
    padding-top: 10px !important;
  }
}
.server_item {
  background: #f9f9f9;
  height: 116px;
  padding-left: 20px;
  color: #333;
}
.server_name {
  font-size: 18px;
  padding-top: 20px;
}
.name_item {
  display: inline-block;
  padding: 15px 0;
  font-size: 14px;
}
.name_item i {
  color: #f85d00;
}
</style>

