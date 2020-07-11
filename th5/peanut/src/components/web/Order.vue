<template>
    <div class="contanir">
        <h1>订单管理</h1>
        <el-row type="flex" push="3" class="row-bg">
		  <el-col :span="12">
			<el-select v-model="setInfo.value" placeholder="请选择" style="width:100px;float:left">
				<el-option v-for="item in setInfo" :key="item.name" :label="item.name" :value="item.name"></el-option>
			</el-select>
			<el-input placeholder="请输入内容" v-model="selectInfo" style="width:150px;float:left" class="input-with-select"></el-input>
			<el-button icon="el-icon-search"></el-button>
		  </el-col>
		</el-row>
        <el-table :data="orderData" border style="width: 100%;text-align:center" :default-sort = "{prop: 'order_num', order: 'descending'}">
            <el-table-column label="订单号" sortable>
                <template slot-scope="scope">
                    <span style="margin-left: 10px">{{ scope.row.order_num }}</span>
                </template>
            </el-table-column>
            <el-table-column label="卖家账号"></el-table-column>
            <el-table-column label="买家账号"></el-table-column>
            <el-table-column label="商品名"></el-table-column>
            <el-table-column label="总价" sortable width="80px"></el-table-column>
            <el-table-column label="下单时间" sortable></el-table-column>
            <el-table-column label="操作" sortable>
                <el-button icon="el-icon-search" type="primary" circle></el-button>
                <el-button icon="el-icon-delete" type="danger" circle></el-button>
                <el-button icon="el-icon-search" type="primary" circle></el-button>
            </el-table-column>
        </el-table>
	  <el-pagination background style="text-align:center" layout="prev, pager, next" :total="100"></el-pagination>
    </div>
</template>

<script>
    export default{
        name: 'Order',
        data() {
            return {
                selectInfo: '',
                orderData: [],
                setInfo: [{
                    value: '选项一',
                    name: '买家账号'
                },
                {
                    value: '选项二',
                    name: '订单号'
                }
                ]
            }
        },
        mounted() {
            // getOrderArr(){
                // var that = this;
                // that.axios.post('http://localhost/th5/public/admin/Order/getOrderArr', {
                // }).then(function (res) {
                //     console.log(res);
                //     that.orderData = res.data.list;
                // }).catch(function (error) {
                //     console.log(error)
                // })
            // }
        },
        methods: {
            formatter(row, column) {
                return row.address;
            }
        }
    }
</script>
<style scoped>

</style>