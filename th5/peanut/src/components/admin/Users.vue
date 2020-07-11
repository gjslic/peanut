// 用户管理
<template>
  <div>
    <div style="margin: 15px 0;">
      <el-input placeholder="请输入内容" v-model="seachVal" class="input-with-select">
        <el-select v-model="select" slot="prepend" placeholder="请选择">
          <el-option label="手机号" value="phone"></el-option>
          <el-option label="用户名" value="name"></el-option>
        </el-select>
        <el-button slot="append" icon="el-icon-search" @click="handleSearch"></el-button>
      </el-input>
    </div>
    <el-table
      border
      :data="userData.slice((currentPage-1)*pagesize,currentPage*pagesize)"
      style="width: 100%"
    >
      <el-table-column label="Id" width="40">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="注册时间" width="160">
        <template slot-scope="scope">
          <span>{{ scope.row.register_time }}</span>
        </template>
      </el-table-column>
      <el-table-column label="头像">
        <template slot-scope="scope">
          <img :src="scope.row.head_img" class="user_avator" />
        </template>
      </el-table-column>
      <el-table-column label="账号" width="110">
        <template slot-scope="scope">
          <span>{{ scope.row.acc}}</span>
        </template>
      </el-table-column>
      <el-table-column label="用户名">
        <template slot-scope="scope">
          <el-popover trigger="hover" placement="top">
            <p>姓名: {{ scope.row.name }}</p>
            <p>地址: {{ scope.row.add }}</p>
            <div slot="reference" class="name-wrapper">
              <el-tag size="medium">{{ scope.row.name }}</el-tag>
            </div>
          </el-popover>
        </template>
      </el-table-column>
      <el-table-column label="手机号" width="110">
        <template slot-scope="scope">
          <span>{{ scope.row.phone }}</span>
        </template>
      </el-table-column>
      <el-table-column label="年龄" width="70">
        <template slot-scope="scope">
          <span>{{ scope.row.age }}</span>
        </template>
      </el-table-column>
      <el-table-column label="性别" width="70">
        <template slot-scope="scope">
          <span>{{ scope.row.sex }}</span>
        </template>
      </el-table-column>
      <el-table-column label="信誉值" width="70">
        <template slot-scope="scope">
          <span>{{ scope.row.credit }}</span>
        </template>
      </el-table-column>
      <el-table-column label="余额" width="50">
        <template slot-scope="scope">
          <span>{{ scope.row.money }}</span>
        </template>
      </el-table-column>
      <el-table-column label="状态">
        <template slot-scope="scope">
          <span>{{ scope.row.state }}</span>
        </template>
      </el-table-column>
      <el-table-column label="操作" width="180">
        <template slot-scope="scope">
          <el-button
            v-if="scope.row.state == '解锁'"
            size="mini"
            @click="handleEdit(scope.$index, scope.row)"
          >锁定</el-button>
          <el-button v-else size="mini" @click="handleEdit(scope.$index, scope.row)">解锁</el-button>
          <el-button size="mini" type="warning" @click="handleReset(scope.$index, scope.row)">重置密码</el-button>
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
        :total="userData.length"
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
.user_avator {
  width: 50px;
  height: 50px;
  border-radius: 50%;
}
</style>
<script>
// 引入网络请求模块
import { getData, sendParam } from "../../network/home";
export default {
  name: "Users",
  data() {
    return {
      seachVal: "",
      select: "name",
      userData: [],
      currentPage: 1, //初始页
      pagesize: 4 //    每页的数据
    };
  },
  created() {
    this.getData();
  },
  methods: {
    // -------消息提示-----------

    open1(msg) {
      this.$message(msg);
    },
    open2(msg) {
      this.$message({
        message: msg,
        type: "success"
      });
    },

    open4(msg) {
      this.$message.error(msg);
    },
    // -----网络请求---------
    getData() {
      let url = "/user/index/get";
      getData(url)
        .then(res => {
          if (res.data.code == 1) {
            this.userData = res.data.data;
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    // ------操作数据-------
    // 初始页currentPage、初始每页数据数pagesize和数据data
    handleSizeChange: function(size) {
      this.pagesize = size; //每页下拉显示数据
    },
    handleCurrentChange(currentPage) {
      this.currentPage = currentPage; //点击第几页
    },
    // 解锁/锁定
    handleEdit(index, row) {
      let id = row.id;
      let url = "/user/index/state";
      let data = {
        id
      };
      this.$confirm("此操作将改变用户状态, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          sendParam(url, data)
            .then(res => {
              if (res.data.code == 1) {
                this.userData = res.data.data;

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
    // 重置密码
    handleReset(index, row) {
      let id = row.id;
      let url = "/user/index/reset";
      let data = {
        id
      };
      this.$confirm("此操作将重置用户密码, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          sendParam(url, data)
            .then(res => {
              if (res.data.code == 1) {
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
            message: "已取消"
          });
        });
    },
    // 搜索
    handleSearch() {
      let url = "/user/index/search";
      let data = {
        keyword: this.seachVal,
        condition: this.select
      };
      sendParam(url, data)
        .then(res => {
          if (res.data.code == 1) {
            this.userData = res.data.data;
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
