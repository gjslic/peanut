<template>
  <div class="contanir">
    <h1>订单管理</h1>
    <!-- 头部菜单区 -->
    <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" @select="handleSelect">
      <el-menu-item index="全部订单">全部订单</el-menu-item>
      <el-menu-item index="未完成">未完成</el-menu-item>
      <el-menu-item index="待审核">待审核</el-menu-item>
      <el-menu-item index="已完成">已完成</el-menu-item>
      <!-- 搜索框 -->
      <div class="searchBox">
        <el-input placeholder="请输入订单号进行搜索" v-model="selectInfo" class="input-with-select">
          <el-button slot="append" icon="el-icon-search" @click="getOrderList(activeIndex,nowPage,selectInfo)"></el-button>
        </el-input>
      </div>
    </el-menu>
    <!-- 订单展示区 -->
    <el-table :data="orderData" border :header-cell-style="{background:'skyblue',color:'white',fontSize:'18px'}" style="width: 100%;text-align:center" :default-sort = "{prop: 'order_num', order: 'descending'}">
      <el-table-column align="center" prop="id" label="ID" width="80px" sortable></el-table-column>
      <el-table-column align="center" prop="orderNum" label="订单号" sortable>
      </el-table-column>
      <el-table-column align="center" label="下单账号">
        <template slot-scope="scope">
          <el-popover trigger="hover" placement="top">
            <p>用户名: {{ scope.row.uName }}</p>
            <p>账号: {{ scope.row.buyer }}</p>
            <p>联系方式: {{ scope.row.phone }}</p>
            <div slot="reference" class="name-wrapper">
              <img :src="scope.row.head_img" style="width:80px;height:80px">
            </div>
          </el-popover>
        </template>
      </el-table-column>
      <el-table-column align="center" label="商品名">
        <template slot-scope="scope">
          <el-popover trigger="click" placement="top">
            <img :src="scope.row.carImg" style="max-width:200px;max-height:200px">
            <div slot="reference" class="name-wrapper">
              <span>{{ scope.row.carName }}</span>
            </div>
          </el-popover>
        </template>
      </el-table-column>
      <el-table-column align="center" prop="price" label="总价/万"  sortable ></el-table-column>
      <el-table-column align="center" prop="orderTime" width="180px" label="下单时间" sortable></el-table-column>
      <el-table-column align="center" prop="state" label="状态" sortable></el-table-column>
      <el-table-column label="操作" align="center">
        <template slot-scope="scope">
          <el-button icon="el-icon-finished" v-if="scope.row.state == '退款审核中'" type="warning" circle @click="enterOption(scope.row)"></el-button>
          <el-button icon="el-icon-view" type="primary" circle @click="getNowOrder(scope.row)"></el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog title="当前订单展示" :visible.sync="dialogFormVisible">
      <el-form :model="nowData">
          <el-image :src="nowData.carImg" ></el-image>
        <el-form-item label="下单用户：" :label-width="formLabelWidth">
          <span>{{nowData.uName}}</span>
        </el-form-item>
        <el-form-item label="联系方式：" :label-width="formLabelWidth">
          <span>{{nowData.phone}}</span>
        </el-form-item>
        <el-form-item label="卖家信息：" :label-width="formLabelWidth"  v-model="sellerInfo">
          <template>
            <el-popover trigger="hover" placement="top" >
              <p>账号: {{ sellerInfo.seller }}</p>
              <p>联系方式: {{ sellerInfo.sellerTel }}</p>
              <div slot="reference" style="width:120px" class="name-wrapper">
                <span>{{sellerInfo.sName}}</span>
              </div>
            </el-popover>
          </template>
        </el-form-item>
        <el-form-item label="商品名称：" :label-width="formLabelWidth">
          <span>{{nowData.carName}}</span>
        </el-form-item>
        <el-form-item label="商品价格：" :label-width="formLabelWidth">
          <span>{{nowData.price}}万</span>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="dialogFormVisible = false">确 定</el-button>
      </div>
    </el-dialog>
    <el-pagination
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
      :current-page="nowPage"
      :page-sizes="[5, 10, 15, 100]"
      :page-size="pageSize"
      layout="total, sizes, prev, pager, next, jumper"
      :total="orderData.length">
    </el-pagination>
  </div>
</template>

<script>
  export default{
    name: 'Order',
    data() {
      return {
        url: 'http://localhost/th5/public/admin_order/Backorder/',
        selectInfo: '', //搜索关键字
        orderData: [], // 所有订单显示
        nowData: [], // 当前订单显示
        sellerInfo: {},
        // menuDisabled: false,
        dialogFormVisible: false, //显示框
        activeIndex: '全部订单', //默认显示订单列表
        formLabelWidth: '120px',
        searchArr: [],
        nowPage: 1,
        pageSize: 5,
      }
    },
    mounted() {
      this.getOrderList('全部订单',this.nowPage,'');
    },
    methods: {
      /**
       * [getOrderList 获取当前类别订单列表]
       */
      getOrderList(type,num,searchInfo){
        let that = this;
        let pageSize = that.pageSize;
        that.orderData = [];
        that.$post(that.url+'getOrderArr', {
          'showType': type,
          'searchInfo': searchInfo,
        }).then(function (res) {
          that.orderData = res.data;
        }).catch(function (error) {
            console.log(error)
        })
      },
      /**
       * [getNowOrder 获取当前订单信息]
       */
      getNowOrder(row){
        this.nowData = row;
        this.$post(this.url+'getNowOrder',{nowId:row.id})
        .then(res => {
          if(res.code == 1){
            this.sellerInfo = res.data;
            this.dialogFormVisible = true;
          }else{
            this.$message({
              showClose: true,
              message: res.msg,
              type: 'error'
            });
          }
        }).catch(err => {
          console.log(err)
        })
      },
      /**
       * [enterOption 审核操作]
       */
      enterOption(row){
        console.log(row)
        this.$post(this.url+'editState',{
          'nowId': row.id,
          'buyId': row.buyer,
          'price': row.price 
        }).then(res => {
          let msgType = '';
          if(res.code == 1){
            row.state = '退款成功';
            msgType = 'success'
          }else{
            row.state = '退款失败';
            msgType = 'error'
          }
          this.$message({
            showClose: true,
            message: res.msg,
            type: msgType
          });
        }).catch(err => { 
          console.log(err)
        })
      },
      /**
       * [handleSelect 选择]
       */
      handleSelect(key, keyPath) {
        console.log(key);
        this.getOrderList(key);
      },
      handleSizeChange(page_size) {
        this.pageSize = page_size;
        this.getOrderList();
      },
      handleCurrentChange(currentPage) {
        this.nowPage = currentPage;
        this.getOrderList();

      }
    }
  }
</script>
<style scoped>
  .el-row {
    margin-bottom: 20px;
  }
  .el-col {
    border-radius: 4px;
  }
.name-wrapper:hover{
  color:red;
  cursor:pointer;
}
.searchBox{
  width: 400px;
  float: right;
  margin-right: 100px;
}
</style>