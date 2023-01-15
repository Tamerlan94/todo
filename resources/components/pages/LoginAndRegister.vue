<template>
  <n-grid :x-gap="1" :cols="3">
    <n-grid-item :offset="1">
      <div>
        <n-card>
          <n-tabs
            class="card-tabs"
            default-value="signin"
            size="large"
            animated
            style="margin: 0 -4px"
            pane-style="padding-left: 4px; padding-right: 4px; box-sizing: border-box;"
          >
            <n-tab-pane name="signin" tab="Вход">
              <n-form>
                <n-form-item-row label="Login">
                  <n-input v-model:value="loginInput.login" />
                </n-form-item-row>
                <n-form-item-row label="Пароль">
                  <n-input
                    v-model:value="loginInput.password"
                    type="password"
                  />
                </n-form-item-row>
              </n-form>
              <n-button type="primary" block secondary strong @click="login">
                Вход
              </n-button>
            </n-tab-pane>
            <n-tab-pane name="signup" tab="Регистрация">
              <n-form>
                <n-form-item-row label="Логин">
                  <n-input v-model:value="registerInput.login" />
                </n-form-item-row>
                <n-form-item-row label="Пароль">
                  <n-input
                    v-model:value="registerInput.password"
                    type="password"
                  />
                </n-form-item-row>
                <n-form-item-row label="Повторить пароль">
                  <n-input
                    v-model:value="registerInput.rePassword"
                    type="password"
                  />
                </n-form-item-row>
              </n-form>
              <n-button type="primary" block secondary strong @click="register">
                Зарегистрироваться
              </n-button>
            </n-tab-pane>
          </n-tabs>
        </n-card>
      </div>
    </n-grid-item>
  </n-grid>
</template>

<script>
import { mapActions } from "vuex";

export default {
  name: "LoginAndRegister",
  data() {
    return {
      loginInput: {
        login: "",
        password: "",
      },
      registerInput: {
        login: "",
        password: "",
        rePassword: "",
      },
    };
  },
  methods: {
    ...mapActions({
      singIn: "auth/login",
    }),
    async login() {
      console.log("login");
      await axios.get("/sanctum/csrf-cookie");
      axios
        .post("api/login", {
          login: this.loginInput.login,
          password: this.loginInput.password,
        })
        .then((result) => {
          console.log(result.data);
          this.singIn();
          // this.$router.push({name:"home"});`
        })
        .catch((err) => {});
    },
    async register() {
      axios
        .post("api/register", {
          login: this.registerInput.login,
          password: this.registerInput.password,
        })
        .then((result) => {
          console.log(result.data);
          this.singIn();
        })
        .catch((err) => {});
    },
  },
};
</script>

<style scoped>
.card-tabs .n-tabs-nav--bar-type {
  padding-left: 4px;
}
.forma {
  width: 350px;
}
</style>