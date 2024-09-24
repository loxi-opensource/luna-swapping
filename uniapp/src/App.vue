<script>
export default {
  onLaunch: function () {
    console.log("App Launch");
  },
  onShow: function () {
    console.log("App Show");
  },
  onHide: function () {
    console.log("App Hide");
  },
};
</script>

<style lang="scss">
@import "@tuniao/tn-icon/dist/https/index.css";
@import "@tuniao/tn-icon/dist/index.css";
@import "@/common/main.scss";

/*每个页面公共css */
body {
  color: #000;
  text-align: center;
  font-size: 30rpx;
  font-style: normal;
  font-weight: 400;
  line-height: 30rpx; /* 128.571% */
}

page {
  height: 100%;
  background-color: #fff;
}
.uni-page-head-title {
  font-size: 20px; /* 设置字体大小为20px */
}
</style>
