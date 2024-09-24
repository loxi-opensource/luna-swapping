<template>
  <view class="content">
    <view class="form-item">
      <view v-if="orderID" class="errorOrderWrap">
        <text :style="$getMediumFontWeight()">{{
          $t("user-contact.orderNO-label.error") + orderID
        }}</text>
      </view>
      <!-- #ifdef MP-TOUTIAO  -->
      <button
        open-type="im"
        @im="onImSuccess"
        @error="onImError"
        :data-im-id="customerServiceImId"
      >
        联系在线客服
      </button>
      <!-- #endif  -->
      <view class="label">{{ $t("user-contact.feedback-content-title") }}</view>
      <view>
        <textarea
          placeholder-style="color:#CFCFCF;font-size:12px;"
          :placeholder="$t('user-contact.feedback-textarea-placeholder')"
          class="textarea-input"
          maxlength="200"
          v-model="content"
        />
      </view>
    </view>
    <view class="uploadListWrap">
      <view
        v-for="(img, index) in userFilePath"
        :key="index"
        class="uploadItemWrap"
      >
        <image
          :src="closeIcon"
          class="delete-icon"
          @tap="deleteSelectedImg(img)"
        />
        <image
          :src="img.imgSrc || img"
          mode="aspectFill"
          class="uploadImage"
        ></image>
      </view>
      <view
        v-if="userFilePath.length < maxImageNum"
        class="uploadItemWrap"
        @tap="$debounceClick(chooseImage)()"
      >
        <image
          class="uploadIcon"
          src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIj48cGF0aCBkPSJNMyAxMWMwLTMuNzcxIDAtNS42NTcgMS4xNzItNi44MjhDNS4zNDMgMyA3LjIyOSAzIDExIDNoMmMzLjc3MSAwIDUuNjU3IDAgNi44MjggMS4xNzJDMjEgNS4zNDMgMjEgNy4yMjkgMjEgMTF2MmMwIDMuNzcxIDAgNS42NTctMS4xNzIgNi44MjhDMTguNjU3IDIxIDE2Ljc3MSAyMSAxMyAyMWgtMmMtMy43NzEgMC01LjY1NyAwLTYuODI4LTEuMTcyQzMgMTguNjU3IDMgMTYuNzcxIDMgMTN2LTJ6IiBzdHJva2U9IiNDRkNGQ0YiIHN0cm9rZS13aWR0aD0iMiIvPjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMTkgMTMuNTg1bC0uMDItLjAyYy0uMzktLjM5LS43MjYtLjcyNi0xLjAyNi0uOTc5LS4zMTctLjI2Ny0uNjYyLS41MDItMS4wODgtLjYzYTMgMyAwIDAgMC0xLjczMiAwYy0uNDI2LjEyOS0uNzcuMzYzLTEuMDg4LjYzLS4zLjI1My0uNjM2LjU5LTEuMDI1Ljk4bC0uMDI5LjAyOGMtLjMwNy4zMDYtLjQ4Ny40ODYtLjYyOC42MDFsLS4wMi4wMTctLjAxMi0uMDIzYy0uMDg3LS4xNi0uMTg5LS4zOTMtLjM2LS43OTJsLS4wNTMtLjEyNC0uMDIyLS4wNTJjLS4zNTYtLjgzLS42NTUtMS41MjgtLjk1LTIuMDU0LS4zMDUtLjU0MS0uNjg1LTEuMDUtMS4yNzctMS4zNDZhMyAzIDAgMCAwLTEuNTk3LS4zMDdjLS42Ni4wNTUtMS4yMDEuMzg2LTEuNjg1Ljc3NS0uNDA2LjMyNy0uODYuNzctMS4zODggMS4yOTdWMTNjMCAuNTE3IDAgLjk4NS4wMDMgMS40MTFsMS4xMTQtMS4xMTRjLjY5LS42OSAxLjE1LTEuMTQ3IDEuNTI1LTEuNDUuMzctLjI5OC41My0uMzM1LjYtLjM0YTEgMSAwIDAgMSAuNTMyLjEwMmMuMDYxLjAzMS4xOTYuMTI0LjQzLjU0LjIzNi40Mi40OTMgMS4wMTYuODc3IDEuOTEybC4wNTMuMTI0LjAxNy4wMzhjLjE0OS4zNDguMjg3LjY3LjQyNS45MjQuMTQ1LjI2NS4zNTUuNTgzLjcwOS44MDJhMiAyIDAgMCAwIDEuMzk4LjI3Yy40MS0uMDczLjcyMy0uMjkuOTU2LS40ODIuMjIyLS4xODQuNDctLjQzMi43MzgtLjdsLjAzLS4wM2MuNDI1LS40MjUuNzAxLS43LjkyOC0uODkxLjIxOC0uMTg0LjMyLS4yMjguMzc2LS4yNDVhMSAxIDAgMCAxIC41NzggMGMuMDU2LjAxNy4xNTguMDYxLjM3Ni4yNDUuMjI3LjE5MS41MDMuNDY2LjkyOS44OTJsMS4zNSAxLjM1Yy4wNDYtLjcxOC4wNTQtMS42MS4wNTYtMi43NzN6IiBmaWxsPSIjQ0ZDRkNGIi8+PGNpcmNsZSBjeD0iMTYuNSIgY3k9IjcuNSIgcj0iMS41IiBmaWxsPSIjQ0ZDRkNGIi8+PC9zdmc+"
        ></image>
        <text>{{ userFilePath.length }}/{{ maxImageNum }}</text>
      </view>
    </view>
    <view class="form-item">
      <view class="label">{{ $t("user-contact.contact-label") }}</view>
      <view>
        <textarea
          placeholder-style="color:#CFCFCF;font-size:24rpx;"
          :placeholder="$t('user-contact.contact-textarea-placeholder')"
          class="textarea-input"
          maxlength="200"
          v-model="contact"
        />
      </view>
    </view>
    <button
      @tap="$debounceClick(onClickBtn)()"
      class="main-button"
      :class="inputAll ? '' : 'main-button-disabled'"
    >
      {{ $t("user-contact.submit-feedback-button") }}
    </button>
  </view>
</template>

<script>
import { submitFeedback, uploadCommonImage } from "../../api/user";
import { requireLogin } from "../../utils/request";
import { closeIcon } from "../../common/svgBase64.js";

export default {
  data() {
    return {
      content: "",
      contact: "",
      userFilePath: [],
      maxImageNum: 5,
      orderID: "",
      closeIcon,
      customerServiceImId: "freddyzhou", // TODO 客服抖音号 需要在后台绑定
    };
  },
  onLoad(option) {
    this.orderID = option.orderID;
  },
  methods: {
    onImError(res) {
      console.log("open im error", JSON.stringify(res.detail));
    },
    onImSuccess(res) {
      console.log("open im success", res.detail);
    },
    deleteSelectedImg(img) {
      this.userFilePath = this.userFilePath.filter((el) => el != img);
    },
    onClickBtn() {
      if (!this.inputAll) return;

      const that = this;

      submitFeedback({
        content: this.preContent + this.content,
        mobile: this.contact,
        images: this.userFilePath.map((item) => item.id),
        type: 1,
      }).then((res) => {
        if (res.code != 1) {
          uni.showToast({
            title: res.msg || that.$t("api-toast.server-error"),
            icon: "none",
          });
          return;
        }
        uni.showToast({
          title: that.$t("api-toast.submit-success"),
          success() {
            setTimeout(() => {
              uni.navigateBack();
            }, 300);
          },
        });
      });
    },
    uploadImg(userFilePath) {
      if (!userFilePath) {
        return;
      }
      uni.showLoading({
        title: this.$t("api-toast.upload-ing"),
      });
      const that = this;
      uploadCommonImage(userFilePath)
        .then((res) => {
          uni.hideLoading();
          res.data = JSON.parse(res.data);
          if (res.data.code === -1) {
            requireLogin({
              toastMsg: that.$t("api-toast.login-status-expired"),
            });
          }
          if (res.data.code != 1) {
            uni.showToast({
              title: res.data.msg || that.$t("api-toast.server-error"),
              icon: "none",
            });
            return;
          }
          that.userFilePath = that.userFilePath.concat({
            id: res.data.data.id,
            imgSrc: userFilePath,
          });
        })
        .catch((err) => {
          console.log(err);
          uni.showToast({
            title: that.$t("api-toast.upload-fail"),
            icon: "none",
          });
          uni.hideLoading();
        });
    },
    chooseImage() {
      let that = this;
      uni.chooseImage({
        count: that.maxImageNum,
        sizeType: ["original", "compressed"], //可以指定是原图还是压缩图，默认二者都有
        sourceType: ["album"], //从相册选择
        success: function (res) {
          console.log(JSON.stringify(res.tempFilePaths));
          that.uploadImg(res.tempFilePaths[0]);
        },
      });
    },
  },
  computed: {
    inputAll() {
      return this.content && this.contact;
    },
    preContent() {
      return this.orderID ? `订单号: ${this.orderID} 异常\n` : "";
    },
  },
};
</script>

<style lang="scss" scoped>
.content {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 48rpx 40rpx;
  position: relative;
  height: 100%;
  box-sizing: border-box;
}

.errorOrderWrap {
  margin: 0 0 30rpx;
  color: #ffb5cf;

  text {
    font-weight: 500;
  }
}

.form-item {
  width: 100%;
}

.label {
  text-align: left;
  width: 100%;
  font-weight: 700;
  font-size: 14px;
  line-height: 36rpx;
  margin-bottom: 24rpx;
}

.uploadListWrap {
  margin-bottom: 34rpx;
  display: flex;
  justify-content: flex-start;
  width: 100%;
  gap: 24rpx;
  flex-wrap: wrap;
}

.uploadItemWrap {
  height: 128rpx;
  width: 128rpx;
  border-radius: 8px;
  background: #f5f6f7;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  gap: 1rpx;
  position: relative;

  text {
    line-height: 18px;
    font-size: 12px;
    color: #dedede;
  }
}

.delete-icon {
  width: 30rpx;
  height: 30rpx;
  position: absolute;
  top: -12rpx;
  right: -12rpx;
  z-index: 10;
}

.uploadImage {
  height: 128rpx;
  width: 128rpx;
  border-radius: 8px;
}

.uploadIcon {
  height: 48rpx;
  width: 48rpx;
}

.textarea-input {
  box-sizing: border-box;
  width: 100%;
  height: 236rpx;
  background: #f5f6f7;
  text-align: left;
  padding: 24rpx;
  border-radius: 8px;
  margin-bottom: 20rpx;
}
</style>
