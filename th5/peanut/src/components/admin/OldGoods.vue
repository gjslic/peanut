// 商品管理
<template>
  <div>
    <div style="margin: 15px 0;">
      <el-input placeholder="请输入内容" v-model="input3" class="input-with-select">
        <el-select v-model="select" slot="prepend" placeholder="请选择">
          <el-option label="车辆名称" value="vehicle_name"></el-option>
          <el-option label="品牌" value="brand_name"></el-option>
        </el-select>
        <el-button slot="append" icon="el-icon-search" @click="handleSearch"></el-button>
      </el-input>
    </div>
    <el-table
      border
      :data="oldCar.slice((currentPage-1)*pagesize,currentPage*pagesize)"
      style="width: 100%"
      :default-sort="{prop: 'price', order: 'descending'}"
      @filter-change="filterChange"
    >
      <el-table-column label="ID" width="50">
        <template slot-scope="scope">
          <span>{{ scope.row.vehicle_id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="车辆名称" width="250">
        <template slot-scope="scope">
          <el-popover trigger="hover" placement="top">
            <p>介绍: {{ scope.row.introduce }}</p>
            <p>上架时间: {{ scope.row.vehicle_time }}</p>
            <div slot="reference" class="name-wrapper">
              <el-tag size="medium">{{ scope.row.vehicle_name }}</el-tag>
            </div>
          </el-popover>
        </template>
      </el-table-column>
      <el-table-column label="品牌">
        <template slot-scope="scope">
          <span>{{ scope.row.brand_name}}</span>
        </template>
      </el-table-column>
      <el-table-column label="系列">
        <template slot-scope="scope">
          <span>{{ scope.row.series_name}}</span>
        </template>
      </el-table-column>
      <el-table-column label="价格" sortable :sort-method="sortByPurchase">
        <template slot-scope="scope">
          <span>{{ scope.row.price}} 万</span>
        </template>
      </el-table-column>
      <el-table-column label="图片">
        <template slot-scope="scope">
          <img :src="scope.row.img" style="width: 60px; height: 50px" />
        </template>
      </el-table-column>
      <el-table-column
        label="状态"
        :filters="[{ text: '已上架', value: '已上架' }, { text: '已下架', value: '已下架' }]"
        prop="vehicle_state"
        align="center"
        column-key="vehicle_state"
        :filter-method="filterTag"
        filter-placement="bottom-end"
        :filter-multiple="false"
      >
        <template slot-scope="scope">
          <el-tag
            :type="getColor(scope.row.vehicle_state)"
            disable-transitions
          >{{scope.row.vehicle_state}}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="卖家名称">
        <template slot-scope="scope">
          <el-popover trigger="hover" placement="top">
            <p>手机号: {{ scope.row.phone }}</p>
            <p>账号: {{ scope.row.acc }}</p>
            <div slot="reference" class="name-wrapper">
              <el-tag size="medium">{{ scope.row.name }}</el-tag>
            </div>
          </el-popover>
        </template>
      </el-table-column>
      <el-table-column label="操作" width="160">
        <template slot-scope="scope">
          <el-button
            size="mini"
            :disabled="scope.row.vehicle_state === '已下架'"
            @click="handleOut(scope.$index, scope.row)"
          >下架</el-button>
          <el-button size="mini" type="warning" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="block">
      <el-pagination
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        :current-page="currentPage"
        :page-size="pagesize"
        :page-sizes="[4, 8, 16, 32]"
        layout="total, sizes, prev, pager, next, jumper"
        :total="oldCar.length"
      ></el-pagination>
    </div>
  </div>
</template>
<style>
.el-select .el-input {
  width: 130px;
}
.input-with-select .el-input-group__prepend {
  background-color: #fff;
}
.block {
  margin: 10px 0;
}
</style>
<script>
// 引入网络请求模块
import { getData, sendParam } from "../../network/home";
export default {
  name: "Goods",
  data() {
    return {
      input3: "",
      select: "vehicle_name",
      oldCar: [],
      currentPage: 1, //初始页
      pagesize: 4 //    每页的数据
    };
  },
  created() {
    this.getData();
  },
  methods: {
    // -----------消息提示----------
    open2(msg) {
      this.$message({
        message: msg,
        type: "success"
      });
    },

    open4(msg) {
      this.$message.error(msg);
    },
    // ---------网络请求------------
    getData() {
      let url = "/goods/index/get";
      getData(url)
        .then(res => {
          if (res.data.code == 1) {
            this.oldCar = res.data.data;
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    // ----------数据操作----------
    // 初始页currentPage、初始每页数据数pagesize和数据data
    handleSizeChange: function(size) {
      this.pagesize = size; //每页下拉显示数据
    },
    handleCurrentChange(currentPage) {
      this.currentPage = currentPage; //点击第几页
    },
    // 下架
    handleOut(index, row) {
      let id = row.vehicle_id;
      let url = "/goods/index/out";
      let data = {
        id
      };
      this.$confirm("此操作将下架商品, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          sendParam(url, data)
            .then(res => {
              if (res.data.code == 1) {
                this.oldCar = res.data.data;

                this.open2(res.data.msg);
              } else {
                this.open4(res.data.msg);
              }
            })
            .catch(err => {
              console.log(err);
            });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消修改"
          });
        });
    },
    // 删除
    handleDelete(index, row) {
      console.log(index, row);
      let id = row.vehicle_id;
      let url = "/goods/index/del";
      let data = {
        id
      };
      this.$confirm("此操作将永久删除该商品, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          sendParam(url, data)
            .then(res => {
              if (res.data.code == 1) {
                // this.oldCar = res.data.data;
                for (let i in this.oldCar) {
                  if (this.oldCar[i].vehicle_id === id) {
                    this.oldCar.splice(i, 1);
                  }
                }
                // this.oldCar.splice(index,1);
                this.open2(res.data.msg);
              } else {
                this.open4(res.data.msg);
              }
            })
            .catch(err => {
              console.log(err);
            });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消删除"
          });
        });
    },
    //根据价格排序
    sortByPurchase(obj1, obj2) {
      var a = obj1.price;
      var b = obj2.price;
      return a - b;
    },
    // 状态打印
    filterTag(value, row) {
      return row.vehicle_state === value;
    },
    // 清除过滤器
    clearFilter() {
      this.$refs.filterTable.clearFilter();
    },
    // 状态颜色
    getColor(state) {
      if (state === "已上架") {
        return "primary";
      } else {
        return "success";
      }
    },
    // 过滤状态
    filterChange(filterObj) {
      if (filterObj.vehicle_state.length > 0) {
        let url = "/goods/index/stateClass";
        let data = {
          state: filterObj.vehicle_state[0]
        };
        sendParam(url, data)
          .then(res => {
            if (res.data.code == 1) {
              this.oldCar = res.data.data;
            } else {
              this.open4(res.data.msg);
            }
          })
          .catch(err => {
            console.log(err);
          });
      } else {
        this.getData();
      }
    },
    // 搜索
    handleSearch() {
      let url = "/goods/index/search";
      let data = {
        keyword: this.input3,
        condition: this.select
      };
      sendParam(url, data)
        .then(res => {
          if (res.data.code == 1) {
            this.oldCar = res.data.data;
          } else {
            this.open4(res.data.msg);
          }
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
};
</script>
