<template>
    <n-layout>
        <n-layout-header>
            <n-space>
                <n-button color="#fff" :strong="true" text @click="exit"
                >Выйти
                </n-button>
            </n-space>
        </n-layout-header>
        <n-layout has-sider>
            <n-layout-sider
                collapse-mode="transform"
                :collapsed-width="5"
                show-trigger="arrow-circle"
                content-style="padding: 24px;"
            >
                <n-list>
                    <n-button color="#ccc" :strong="true" text>
                        Добавить новую доску
                    </n-button>

                    <div style="display: flex">
                        <n-input v-model:value="boardName" clearable></n-input>
                        <n-button @click="addBoard">ok</n-button>
                    </div>

                    <n-list-item v-for="board in boards" :key="board.id">
                        <n-space justify="space-between">
                            <n-button text @click="selectBoard(board)">{{ board.title }}</n-button>
                            <div v-show="activeBoard?.id === board.id">
                                ->>>>>>>>>>>>>
                            </div>
                            <n-button text color="red" @click="deleteBoard(board)">delete</n-button>
                        </n-space>

                    </n-list-item>
                </n-list>
            </n-layout-sider>
            <n-layout-content content-style="padding: 24px;">
                <n-scrollbar x-scrollable>
                    <board ref="boardRef" :active-board="activeBoard"></board>
                </n-scrollbar>
            </n-layout-content>
        </n-layout>
        <n-layout-footer>Footer</n-layout-footer>
    </n-layout>
</template>

<script>
import Board from "../Board.vue";
import axios from "axios";
import {mapActions} from "vuex";

export default {
    name: "Home",
    components: {Board},
    data() {
        return {
            boards: [],
            boardName: null,
            activeBoard: null
        };
    },
    props: {},
    methods: {
        addBoard() {
            axios.post('/api/board/create',
                {
                    title: this.boardName,
                    user_id: this.$store.state.auth.user.id
                })
                .then(res => {
                    this.boards.push(res.data);
                })
        },
        deleteBoard(board) {
            axios.post('/api/board/delete', {
                id: board.id,
                user_id: this.$store.state.auth.user.id
            })
                .then(res => {
                    if (res.data.status === 1) {
                        this.boards.splice(this.boards.indexOf(board), 1);
                        this.activeBoard = null;
                    }
                });
        },
        selectBoard(board){
              this.activeBoard = board;
              this.$refs.boardRef.selectBoard();
        },
        getAll() {
            axios.get('/api/board/readAll', {params: {user_id: this.$store.state.auth.user.id}})
                .then(res => {
                    this.boards = res.data;
                })
        },
        ...mapActions({
            signOut: "auth/logout"
        }),
        exit() {
            axios.post('/api/logout').then(({data}) => {
                this.signOut()
                this.$router.push({name: "loginAndRegister"})
            })
        }
    },
    mounted() {
        this.getAll()
    },
};
</script>

<style scoped>
.n-layout-header,
.n-layout-footer {
    color: #fff;
    background: rgba(0, 15, 98, 0.849);
    padding: 12px;
}

.n-layout-content {
    background: rgba(128, 128, 128, 0.2);
    height: 100vh;
    width: 100%;
}
</style>

