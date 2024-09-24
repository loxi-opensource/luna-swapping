<template>
  <view class="dialog" v-if="visible">
    <view class="close-icon"></view>
    <view class="dialog-overlay"></view>
    <view class="dialog-content">
      <!-- 对话框内容 -->
      <view class="dialog-header">{{ title }}</view>
      <view class="content">
        <slot></slot>
      </view>
      <slot name="footer" class="dialog-footer"></slot>
    </view>
  </view>
</template>

<script>
export default {
  data() {
    return {
      visible: false,
    };
  },
  props: ["title"],
  methods: {
    open() {
      this.visible = true;
    },
    close() {
      this.visible = false;
    },
  },
};
</script>

<style scoped lang="scss">
.dialog {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.dialog-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
}

.dialog-content {
  width: 350px;
  max-width: 600px;
  background-color: white;
  padding: 20px;
  border-radius: 5px;
  z-index: 10000;
  .content {
    height: 100%;
    max-height: 60vh;
    overflow: auto;
  }
}

button {
  margin-top: 10px;
}
.dialog-header {
  font-size: 22px;
  margin-bottom: 10px;
}
</style>
