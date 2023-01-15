import "./bootstrap";

import { createApp } from "vue";
import router from "./router";
import App from "../components/layouts/App.vue";
import naive from "naive-ui/es/preset";
import Store from "./store/index";
// import Vuesax from "vuesa"
// import Antd from 'ant-design-vue';

// import 'ant-design-vue/dist/antd.css';

const app = createApp(App);
app.use(router).use(Store).use(naive).mount("#app");
