<template>
  <view class="content">
    <image class="mask-img" mode="widthFix" :src="maskImgSrc"></image>
    <view class="main">
      <image
        :src="logaIcon"
        mode="aspectFit"
        style="width: 228rpx; height: 59rpx"
      ></image>
      <text class="title">AI定妆照</text>
    </view>
    <!-- #ifdef MP-WEIXIN  -->
    <button
      class="main-button main-btn"
      :class="{ 'main-button-disabled': !agreeTerms }"
      :style="$getMediumFontWeight()"
      @tap="$debounceClick(requestAuthLogin)('weixin')"
    >
      微信授权登录
    </button>
    <!-- #endif -->
    <!-- #ifdef MP-TOUTIAO  -->
    <button
      class="main-button main-btn"
      :class="{ 'main-button-disabled': !agreeTerms }"
      :style="$getMediumFontWeight()"
      @tap="$debounceClick(requestAuthLogin)('toutiao')"
      data-eventsync="true"
    >
      抖音授权登录
    </button>
    <!-- #endif -->
    <view class="user-privacy-wrap">
      <checkbox-group @change="handleCheckboxChange">
        <checkbox
          :value="agreeTerms"
          color="#C465FF"
          style="transform: scale(0.8)"
        ></checkbox>
      </checkbox-group>
      <view class="text-span" :style="$getMediumFontWeight()">
        我已阅读并同意
        <view class="text-span-link" @tap="showAgreement(1)">《用户协议》</view>
        和
        <view class="text-span-link" @tap="showAgreement(2)">《隐私协议》</view>
      </view>
    </view>
    <privacy-dialog :title="agreementTitle" ref="agreementDialog">
      <div v-html="agreementContent"></div>
      <template #footer>
        <view class="agreement-footer">
          <button class="agreement-button" @tap="closeDialog">我同意</button>
        </view>
      </template>
    </privacy-dialog>
  </view>
</template>

<script>
import { douyinMnpLogin, mnpLogin } from "../../api/user";
import { getPolicy } from "../../api/lunaDraw";
import { logaIcon } from "../../common/svgBase64.js";
import PrivacyDialog from "../../components/PrivacyDialog.vue";

export default {
  data() {
    return {
      title: "Hello",
      providerList: [],
      agreeTerms: false,
      redirect: "",
      delta: 0,
      redirectTab: "",
      logaIcon,
      agreementContent: "",
      agreementTitle: "",
      maskImgSrc:
        "https://luna-frontend-static-resource.oss-cn-shenzhen.aliyuncs.com/bootstrap-bg.png",
      policy: {
        privacy: "",
        service: "",
      },
    };
  },
  components: {
    PrivacyDialog,
  },
  onLoad(option) {
    this.redirect = decodeURIComponent(option.redirect);
    this.delta = decodeURIComponent(option.delta);
    this.redirectTab = decodeURIComponent(option.redirectTab);
    getPolicy({
      type: "privacy",
    }).then((res) => {
      this.policy.privacy = res?.data;
    });
    getPolicy({
      type: "service",
    }).then((res) => {
      this.policy.service = res?.data;
    });
  },
  methods: {
    handleCheckboxChange(e) {
      if (e.detail.value.length > 0) {
        this.agreeTerms = true;
      } else {
        this.agreeTerms = false;
      }
    },
    showAgreement(type) {
      if (type === 1) {
        this.agreementContent = this.policy.privacy?.content;
        this.agreementTitle = this.policy.privacy?.title;
      } else {
        this.agreementContent = this.policy.service?.content;
        this.agreementTitle = this.policy.service?.title;
      }
      this.$refs.agreementDialog.open();
    },
    closeDialog() {
      this.$refs.agreementDialog.close();
    },
    getUserProfile() {
      // 获取用户资料 TODO
      tt.getUserProfile({
        success(res) {
          console.log("success", res);
        },
        fail(res) {
          console.log("fail", res);
        },
      });
    },
    onLoginSuccess(loginRes) {
      console.log(loginRes, "login res");
      if (loginRes.code !== 1) {
        uni.showToast({
          title: loginRes.msg,
        });
        return;
      }
      uni.setStorageSync("token", loginRes.data.token);
      uni.showToast({
        title: "登录成功",
      });
      if (this.redirectTab) {
        uni.switchTab({
          url: this.redirectTab,
        });
      } else if (this.delta != 0) {
        uni.navigateBack({
          delta: this.delta,
          success: () => {
            uni.$emit("navigating_back:from_page_login");
          },
        });
      } else if (this.redirect) {
        uni.navigateTo({
          url: this.redirect,
        });
      } else {
        // uni.switchTab({
        //     url: "/pages/draw/portrait"
        // })
      }
    },
    requestAuthLogin(provider) {
      if (!this.agreeTerms) return;

      uni.login({
        provider: provider,
        success: async (authRes) => {
          // #ifdef MP-WEIXIN
          mnpLogin({ code: authRes.code })
            .then(this.onLoginSuccess)
            .catch((err) => {
              console.log(err);
            });
          // #endif

          // #ifdef MP-TOUTIAO
          douyinMnpLogin({
            code: authRes.code,
            anonymousCode: authRes.anonymousCode,
          })
            .then(this.onLoginSuccess)
            .catch((err) => {
              console.log(err);
            });
          // #endif

          console.log(authRes);
        },
        fail: (err) => {
          console.log(err);
        },
        complete: () => {},
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.content {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 0;
  position: relative;
  height: 100%;
  box-sizing: border-box;

  .main-btn {
    bottom: 312rpx;
  }
}

.mask-img {
  position: absolute;
  top: 20rpx;
  width: 100%;
  height: 1250rpx;
  z-index: -1;
  opacity: 0.3;
}

.main {
  width: 100%;
  display: flex;
  flex-direction: column;
  position: absolute;
  align-items: center;
  left: 50%;
  transform: translate(-50%, 0);
  top: 510rpx;
  z-index: 2;
}

.title {
  margin-top: 20rpx;
  width: 228rpx;
  white-space: nowrap;
  color: #000;
  text-align: center;
  font-size: 12px;
  line-height: 36rpx;
  letter-spacing: 34rpx;
}

.user-privacy-wrap {
  position: absolute;
  bottom: 200rpx;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10rpx;
  font-weight: 500;

  .text-span {
    font-size: 24rpx;
    display: flex;

    &-link {
      color: #c465ff;
      margin: 0 6rpx;
    }
  }
}

.agreement-content {
  white-space: pre-wrap;
}

.agreement-button {
  background: linear-gradient(171deg, #c465ff 1.8%, #8247ff 100%);
  color: #ffffff !important;
  width: 70%;
  border-radius: 8px;
  padding: 0;
  margin: 0;
}

.agreement-footer {
  display: flex;
  flex-flow: column;
  justify-content: center;
  align-items: center;
  height: 40px;
  margin-top: 20px;
}
</style>
