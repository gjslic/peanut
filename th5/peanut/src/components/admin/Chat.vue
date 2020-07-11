// 聊天组件
<template>
  <div class="chat_box">
    <div class="chat_title"></div>
    <div class="chat_main">
      <div class="chat_list">
        <ul>
          <li class="list_item">
            <img class="user_img" src="../../assets/images/avator.jpg" alt="">
            <span class="user_name">用户1</span>
          </li>
        </ul>
        <div class="chat_item">
          <div class="info_box">
            <ul>
              <li>
                <div class="img_box">
                  <img class="user_img" src="../../assets/images/avator.jpg" alt="">
                  
                </div>
                <span class="user_chat_name">
                  小兰
                  <span>2020-05-15 22:34:40</span>
                </span>
                <div class="chat_text">你慢慢说，别急……</div>
              </li>
              <li class="info_item_my">
                <div class="img_box">
                  <img class="user_img" src="../../assets/images/avator.jpg" alt="">
                  
                </div>
                <span class="user_chat_name">
                  <span>2020-05-15 22:34:40</span>
                  小兰
                </span>
                <div class="chat_text">你慢慢说，别急……</div>
              </li>
            </ul>
          </div>
          <div class="input_box">
            <div class="input_top">
              <i class="el-icon-picture-outline-round"></i>
            </div>
            <div class="input_info">
              <textarea class="text_info" placeholder="请输入内容" v-model="info"></textarea>
            </div>
            <div class="input_button">
              <input type="button" value="发送" class="chatBtn" @click="websocketsend">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style scoped>
  @import '../../assets/css/chat.css';
  
</style>
<script>
  export default {
    name: 'Chat',
    data() {
      return {
        websock: null,
        info:'',
      }
    },
    created() {
      this.initWebSocket();
    },
    methods: {
      initWebSocket(){ //初始化weosocket
        this.websock = new WebSocket("ws://localhost:8080");
        this.websock.onmessage = this.websocketonmessage;
        this.websock.onopen = this.websocketonopen;
        this.websock.onerror = this.websocketonerror;
        this.websock.onclose = this.websocketclose;
      },
      
      websocketonopen(){ //连接建立之后执行send方法发送数据
        console.log('已经建立连接');
        let actions = {"test":"12345"};
        this.websocketsend(JSON.stringify(actions));
      },
      websocketonerror(){//连接建立失败重连
      console.log('连接建立失败重连');
        this.initWebSocket();
      },
      websocketonmessage(e){ //数据接收
        const redata = JSON.parse(e.data);
      },
      websocketsend(Data){//数据发送
      console.log(this.info);
        this.websock.send(Data);
      },
      websocketclose(e){  //关闭
        console.log('断开连接',e);
      },
    },
  }
</script>
