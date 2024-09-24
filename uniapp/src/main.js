import { createSSRApp } from "vue";
import { createI18n } from "vue-i18n"; // v9.x
import { debounceClick } from "./utils/common.js";
import { getMediumFontWeight } from "./utils/common.js";
import App from "./App.vue";
import store from "./store/index.js";

import messages from "./locale/index";

const i18nConfig = {
  locale: "zh-Hans",
  // locale: uni.getLocale(),
  messages,
};

const i18n = createI18n(i18nConfig);

export function createApp() {
  const res = uni.getSystemInfoSync() || {};
  const { osName } = res;
  const app = createSSRApp(App);
  app.use(store);
  app.use(i18n);
  app.config.globalProperties.$debounceClick = debounceClick;
  app.config.globalProperties.$getMediumFontWeight = getMediumFontWeight.bind(
    null,
    osName
  );
  return {
    app,
  };
}
