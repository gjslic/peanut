// 商品管理
<template>
  <div>
    <el-row>
      <el-col :span="21">
        <div style="margin: 15px 0;">
          <el-input placeholder="请输入内容" v-model="input3" class="input-with-select">
            <el-select v-model="select" slot="prepend" placeholder="请选择" class="seBox">
              <el-option label="车辆名称" value="vehicle_name"></el-option>
              <el-option label="拍卖者名" value="name"></el-option>
            </el-select>
            <el-button slot="append" icon="el-icon-search" @click="handleSearch"></el-button>
          </el-input>
        </div>
      </el-col>
      <el-col :span="3">
        <div style="margin: 15px 0; float: right;">
          <el-button type="primary" @click="dialogFormVisible = true">
            发布
            <i class="el-icon-upload el-icon--right"></i>
          </el-button>
        </div>
      </el-col>
    </el-row>
    <!-- 弹框 -->

    <el-dialog title="发布拍卖" :visible.sync="dialogFormVisible">
      <el-form
        :model="ruleForm"
        :rules="rules"
        ref="ruleForm"
        label-width="100px"
        class="demo-ruleForm"
      >
        <el-form-item label="车辆名称" prop="name">
          <el-input v-model="ruleForm.name" placeholder="请输入车辆名称"></el-input>
        </el-form-item>
        <el-form-item label="行驶路程" prop="way">
          <el-input v-model="ruleForm.way" placeholder="请输入行驶路程(km)"></el-input>
        </el-form-item>
        <el-form-item label="拍卖价格" prop="salePrice">
          <el-input v-model="ruleForm.salePrice" placeholder="请输入拍卖价格（万）"></el-input>
        </el-form-item>
        <el-form-item label="所属地区" prop="region" class="cityBox">
          <el-select v-model="ruleForm.region" placeholder="请选择所属地区">
            <el-option
              v-for="item in cityData"
              :key="item.city_id"
              :label="item.city_name"
              :value="item.city_id"
            ></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="活动时间" prop="date1">
           <el-select v-model="ruleForm.date1" placeholder="请选择活动时间">
            <el-option
              v-for="item in saleDate"
              :key="item.id"
              :label="item.start_time + '~' + item.end_time"
              :value="item.id"
            ></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="车牌系列" prop="value">
          <el-cascader
            v-model="ruleForm.value"
            :options="brandS"
            :props="{ expandTrigger: 'hover' }"
          ></el-cascader>
        </el-form-item>
        <el-form-item label="车辆标签" prop="resource">
          <el-radio-group v-model="ruleForm.resource">
            <el-radio
              v-for="( item ,index) in tabData"
              :key="index"
              :label="item.tab_id"
            >{{item.tab_name}}</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="车辆介绍" prop="desc">
          <el-input type="textarea" v-model="ruleForm.desc" maxlength="30" show-word-limit></el-input>
        </el-form-item>
        <el-form-item label="车辆图片" prop="imageUrl" class="img_box">
          <el-input v-model="ruleForm.imageUrl" v-if="false"></el-input>
          <el-upload
            action="http://127.0.0.1/peanut/th5/public/goods/index/upload"
            list-type="picture-card"
            :limit="1"
            :on-preview="handlePictureCardPreview"
            :on-remove="handleRemove"
            name="image"
            :on-success="uploadSuccess"
            :on-change="handleimgChange"
          >
            <i class="el-icon-plus"></i>
          </el-upload>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" @click="submitForm('ruleForm')">立即创建</el-button>
          <el-button @click="resetForm('ruleForm')">重置</el-button>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
      </div>
    </el-dialog>
    <el-table
      border
      :data="saleCar.slice((currentPage-1)*pagesize,currentPage*pagesize)"
      style="width: 100%"
      @filter-change="filterChange"
    >
      <el-table-column label="ID" width="50">
        <template slot-scope="scope">
          <span>{{ scope.row.vehicle_id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="开始时间" width="120" sortable :sort-method="sortByDate">
        <template slot-scope="scope">
          <span>{{ scope.row.start_time }}</span>
        </template>
      </el-table-column>
      <el-table-column label="结束时间" width="120">
        <template slot-scope="scope">
          <span>{{ scope.row.end_time }}</span>
        </template>
      </el-table-column>
      <el-table-column label="车辆名称" width="250">
        <template slot-scope="scope">
          <el-popover trigger="hover" placement="top">
            <p>介绍: {{ scope.row.introduce }}</p>
            <div slot="reference" class="name-wrapper">
              <el-tag size="medium">{{ scope.row.vehicle_name }}</el-tag>
            </div>
          </el-popover>
        </template>
      </el-table-column>
      <el-table-column label="价格">
        <template slot-scope="scope">
          <span>{{ scope.row.price}} 万</span>
        </template>
      </el-table-column>
      <el-table-column label="图片">
        <template slot-scope="scope">
          <img :src="scope.row.img" style="width: 60px; height: 50px" />
        </template>
      </el-table-column>
      <el-table-column label="拍卖者">
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
      <el-table-column
        label="状态"
        :filters="[{ text: '已拍卖', value: '已拍卖' }, { text: '未审核', value: '未审核' },{ text: '拍卖中', value: '拍卖中' }]"
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
      <el-table-column label="操作" width="230">
        <template slot-scope="scope">
          <el-button
            v-if="scope.row.vehicle_state === '未审核'"
            size="mini"
            @click="handlePass(scope.$index, scope.row)"
          >审核</el-button>
          <el-button
            v-else
            size="mini"
            :disabled="scope.row.vehicle_state === '已拍卖'"
            @click="handleGetOut(scope.$index, scope.row)"
          >下架</el-button>
          <el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
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
        :total="saleCar.length"
      ></el-pagination>
    </div>
  </div>
</template>
<style>
.cityBox .el-select {
  display: block;
}
.seBox .el-input--suffix {
  width: 130px;
}
.input-with-select .el-input-group__prepend {
  background-color: #fff;
}
.block {
  margin: 10px 0;
}
.img_box .el-upload__input {
  display: none;
}
</style>
<script>
// 引入网络请求模块
import { getData, sendParam } from "../../network/home";
export default {
  name: "OldGoods",
  data() {
    return {
      brandS: [],

      input3: "",
      select: "vehicle_name",
      saleCar: [], //拍卖数据
      tabData: [], //标签
      cityData: [], //城市
      saleDate: [],//拍卖时间
      series: [],
      currentPage: 1, //初始页
      pagesize: 4, //    每页的数据
      dialogFormVisible: false,
      ruleForm: {
        name: "",
        region: "",
        date1: "",
        resource: "",
        desc: "",
        imageUrl: "",
        value: [],
        salePrice: "",
        way: ""
      },
      rules: {
        name: [
          { required: true, message: "请输入车辆名称", trigger: "blur" }
        ],
        way: [{ required: true, message: "请输入行驶路程", trigger: "blur" }],
        salePrice: [{ required: true, message: "请输入价格", trigger: "blur" }],
        region: [
          { required: true, message: "请选择所属地区", trigger: "change" }
        ],
        date1: [
          { required: true, message: "请选择活动时间", trigger: "change" }
        ],
        resource: [
          { required: true, message: "请选择车辆标签", trigger: "change" }
        ],
        desc: [{ required: true, message: "请填写车辆介绍", trigger: "blur" }],
        imageUrl: [{ required: true, message: "请上传图片", trigger: "blur" }],
        value: [{ required: true, message: "请选择车系", trigger: "blur" }]
      },
      dialogImageUrl: "",
      dialogVisible: false,
      lastImgUrl: ""
    };
  },

  created() {
    this.getData();
    this.getCityData();
    this.getTabData();
    this.getSeriesData();
    this.getTimeData();
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
      let url = "/goods/index/getSale";
      getData(url)
        .then(res => {
          if (res.data.code == 1) {
            this.saleCar = res.data.data;
          }
        })
        .catch(err => {
        });
    },

    // 获取城市
    getCityData() {
      let url = "/goods/index/getCity";
      getData(url)
        .then(res => {
          if (res.data.code == 1) {
            this.cityData = res.data.data;
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    //获取标签
    getTabData() {
      let url = "/goods/index/getTab";
      getData(url)
        .then(res => {
          if (res.data.code == 1) { 
            this.tabData = res.data.data;
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    //获取拍卖时间
    getTimeData() {
      let url = "/goods/index/getTime";
      getData(url)
        .then(res => {
          if (res.data.code == 1) {
            this.saleDate = res.data.data;
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    // 获取品牌车系
    getSeriesData() {
      let url = "/goods/index/getSeries";
      getData(url)
        .then(res => {
          // console.log(res);
          //需要渲染的数据
          var renderData = [];
          let series = res.data.series;
          let brand = res.data.brand;
          for (let item of brand) {
            item.children = [];
            renderData.push(item);
            for (let val of series) {
              if (val.brand_id == item.value) {
                item.children.push(val);
              }
            }
          }
          this.brandS = renderData;
        })
        .catch(err => {
          console.log(err);
        });
    },
    // 状态打印
    filterTag(value, row) {
      return row.vehicle_state === value;
    },
    // 初始页currentPage、初始每页数据数pagesize和数据data
    handleSizeChange: function(size) {
      this.pagesize = size; //每页下拉显示数据
    },
    handleCurrentChange(currentPage) {
      this.currentPage = currentPage; //点击第几页
    },
    // 排序
    sortByDate(obj1, obj2) {
      var a = obj1.start_time;
      var b = obj2.start_time;
      return a - b;
    },
    // ------------------数据操作----------
    // 状态颜色
    getColor(state) {
      if (state === "拍卖中") {
        return "primary";
      } else if(state === "未审核"){
        return "danger";
      } else{
        return "success";
      }
    },
    // 过滤状态
    filterChange(filterObj) {
      if (filterObj.vehicle_state.length > 0) {
        let url = "/goods/index/stateFind";
        let data = {
          state: filterObj.vehicle_state[0]
        };
        sendParam(url, data)
          .then(res => {
            if (res.data.code == 1) {
              this.saleCar = res.data.data;
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
    // 审核
    handlePass(index, row) {
      let id = row.vehicle_id;
      let url = "/goods/index/pass";
      let data = {
        id
      };
      this.$confirm("此操作将上传该拍卖商品, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          sendParam(url, data)
            .then(res => {
              if (res.data.code == 1) {
                this.saleCar = res.data.data;
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
            message: "已取消审核"
          });
        });
    },
    // 下架
    handleGetOut(index, row){
      let id = row.vehicle_id;
      let url = "/goods/index/saleOut";
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
                this.saleCar = res.data.data;

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
    // 删除
    handleDelete(index, row) {
      let id = row.vehicle_id;
      let url = "/goods/index/delSale";
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
                for (let i in this.saleCar) {
                  if (this.saleCar[i].vehicle_id === id) {
                    this.saleCar.splice(i, 1);
                  }
                }
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
    // 搜索
    handleSearch() {
      let url = "/goods/index/searchSale";
      let data = {
        keyword: this.input3,
        condition: this.select
      };
      sendParam(url, data)
        .then(res => {
          if (res.data.code == 1) {
            this.saleCar = res.data.data;
          } else {
            this.open4(res.data.msg);
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    // 发布
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let url = "/goods/index/issue";
          let data = {
            imgUrl: this.lastImgUrl,
            ruleForm: this.ruleForm
          };
          sendParam(url, data)
            .then(res => {
              if (res.data.code == 1) {
                this.open2(res.data.msg);
                this.dialogFormVisible = false;
                this.getData();
              } else {
                this.open4(res.data.msg);
              }
            })
            .catch(err => {
              console.log(err);
            });
        } else {
          console.log("error submit!!");
          return false;
        }
      });
    },
    handleRemove(file, fileList) {;
    },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
    // 图片上传成功后取到地址
    uploadSuccess(response, file, fileList) {
      this.lastImgUrl = response;
    },
    handleimgChange(file, fileList) {
      this.ruleForm.imageUrl = URL.createObjectURL(file.raw);
    }
  }
};
</script>
