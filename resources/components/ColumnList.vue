<template>
    <n-card class="base-card" v-for="column in columns" :key="column.id" hoverable
            @drop="onDrop($event, column.cards)"
            @dragover.prevent
            @dragenter.prevent>
        <template #header>
            <div @click="changeName(column)" v-show="!column.showColumnChangeBool">
                {{ column.title }}
            </div>
            <div v-show="column.showColumnChangeBool">
                <n-grid :x-gap="12" :y-gap="12" :cols="2">
                    <n-grid-item>
                        <n-input
                            v-model:value="column.title"
                            type="text"
                            clearable
                        ></n-input>
                    </n-grid-item>
                    <n-grid-item>
                        <n-button type="primary" @click="saveName(column)">save</n-button>
                    </n-grid-item>
                </n-grid>
            </div>
        </template>
        <template #header-extra>
            <n-button text color="#ccc" @click="deleteColumn(column)">delete</n-button>
        </template>
        <template #default>
            <n-grid :x-gap="12" :y-gap="12" :cols="1">
                <n-grid-item v-for="card in column.cards" :key="card.id">
                    <n-card :style="card.is_done === 1 ? {'border-color': '#2cb412!important'} : {}"
                            draggable="true" @dragstart="startDrag($event, card)">
                        <template #header>
                            <div @click="openCard(card)">
                                {{ card.title }}
                            </div>
                        </template>
                        <template #header-extra>
                            <n-button @click="deleteCard(card)">delete</n-button>
                        </template>
                    </n-card>
                </n-grid-item>
            </n-grid>
        </template>
        <template #footer>
            <n-grid :x-gap="10" :y-gap="5" :cols="1" v-show="!column.showAddCard">
                <n-grid-item>
                    <n-button type="primary" size="small" @click="createCard(column)"
                    >Create new card
                    </n-button>
                </n-grid-item>
            </n-grid>
        </template>

        <template #action>
            <div v-show="column.showAddCard">
                <n-grid :x-gap="12" :y-gap="12" :cols="2">
                    <n-grid-item span="2">
                        <n-space>
                            <n-input placeholder="card name" v-model:value="tempCardName"></n-input>
                        </n-space>
                    </n-grid-item>
                    <n-grid-item span="2">
                        <n-space>
                            <n-button @click="saveCard(column)">save</n-button>
                            <n-button @click="column.showAddCard = false">cancel</n-button>
                        </n-space>
                    </n-grid-item>
                </n-grid>
            </div>
        </template>
    </n-card>

    <n-modal v-model:show="showModal" preset="card" :title="activeCard.title" style="width: 600px">
        <n-grid :x-gap="12" :y-gap="12" cols="6">
            <n-grid-item span="6">
                <n-input type="textarea" v-model:value="activeCard.description"></n-input>
            </n-grid-item>
            <n-grid-item span="6">
                <n-space justify="space-between">
                    <n-checkbox v-model:checked="is_done">done</n-checkbox>
                    <n-button @click="saveCardDescription">save</n-button>
                </n-space>
            </n-grid-item>
            <n-grid-item span="6">

            </n-grid-item>
        </n-grid>
    </n-modal>
</template>

<script>
import {reactive, ref} from "vue";

export default {
    name: "ColumnList",
    props: {
        columns: Object,
    },
    data() {
        return {
            showModal: false,
            showChangeName: false,
            draggedItem: null,

            tempCardName: null,
            activeCard: {
                id: null,
                title: null,
                columnId: null,
                description: null,
                isDone: false,
            },
        };
    },
    methods: {
        changeName(item) {
            item.showColumnChangeBool = true;
        },
        createCard(column) {
            column.showAddCard = true;
        },
        saveName(column) {
            axios.post('/api/column/update', {
                id: column.id,
                board_id: column.board_id,
                title: column.title
            })
                .then(res => {
                    if (res.data.status === 1) {
                        column.showColumnChangeBool = false;
                    }
                })
                .catch(err => {
                    console.log(err);
                })
        },
        saveCard(column) {
            axios.post('/api/card/create', {
                title: this.tempCardName,
                description: null,
                column_id: column.id
            })
                .then(res => {
                    this.$emit('saveCard', res.data);
                    column.showAddCard = false;
                    this.tempCardName = null;
                })

        },
        saveCardDescription() {
            axios.post('/api/card/update', {
                id: this.activeCard.id,
                title: this.activeCard.title,
                description: this.activeCard.description,
                column_id: this.activeCard.columnId,
                is_done: this.activeCard.isDone
            })
                .then(res => {
                    if (res.data.status === 1) {
                        this.$emit('updateCard', this.activeCard);
                        this.showModal = false;
                        this.activeCard = {
                            id: null,
                            title: null,
                            description: null,
                            isDone: false,
                            columnId: null,
                        };
                    }
                })
        },
        deleteCard(card) {
            axios.post('/api/card/delete', {
                id: card.id,
                column_id: card.column_id
            })
                .then(res => {
                    if (res.data.status === 1) {
                        this.$emit('deleteCard', card);
                    }
                })
        },
        cancelCard() {

        },
        openCard(card) {
            this.activeCard.id = card.id;
            this.activeCard.title = card.title;
            this.activeCard.description = card.description;
            this.activeCard.isDone = card.is_done;
            this.activeCard.columnId = card.column_id;

            this.showModal = true;
        },
        deleteColumn(column) {
            axios.post('/api/column/delete', {
                id: column.id,
                board_id: column.board_id
            })
                .then(res => {
                    if (res.data.status === 1) {
                        this.$emit('deleteColumn', this.columns.indexOf(column));
                    }
                })
        },
        startDrag(evt, item) {
            evt.dataTransfer.dropEffect = 'move';
            evt.dataTransfer.effectAllowed = 'move';
            evt.dataTransfer.setData('itemID', item);
            this.$emit('draggingStart', item);
        },
        onDrop(evt, list) {
            const itemID = evt.dataTransfer.getData('itemID');
            this.$emit('draggingEnd', itemID, list);
        },
    },
    computed: {
        is_done: {
            get() {
                return (this.activeCard.isDone === 1);
            },
            set(value) {
                this.activeCard.isDone = (value === true) ? 1 : 0;
            }
        }
    },
    emit: ['addColumn', 'deleteColumn'],
};
</script>

<style scoped>
.base-card {
    min-width: 300px;
    max-width: 300px;
}
</style>
