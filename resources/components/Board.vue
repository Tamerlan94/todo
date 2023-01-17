<template>
    <div class="columns" v-show="activeBoard != null">
        <n-space :wrap="false" :wrap-item="false" align="start">
            <column-list @delete-column="onDeleteColumn"
                         @save-card="onSaveCard"
                         @update-card="onUpdateCard"
                         @delete-card="onDeleteCard"
                         @dragging-start="onDraggingStart"
                         @dragging-end="onDraggingEnd"
                         :columns="columns"></column-list>
            <n-button
                type="primary"
                size="medium"
                @click="addColumn"
                v-show="!showColumnBool"
            >add column
            </n-button>
            <n-card hoverable v-show="showColumnBool">
                <template #header>
                    <n-space :vertical="true">
                        <n-input
                            v-model:value="tempTitle"
                            type="text"
                            :autofocus="true"
                            clearable
                        ></n-input>
                        <n-space>
                            <n-button type="primary" size="medium" @click="saveColumn"
                            >save
                            </n-button>
                            <n-button type="primary" size="medium" @click="cancelColumn"
                            >cancel
                            </n-button>
                        </n-space>
                    </n-space>
                </template>
            </n-card>
        </n-space>
    </div>
</template>

<script>
import ColumnList from "./ColumnList.vue";

export default {
    name: "Board",
    components: {ColumnList},
    props: {activeBoard: Object},
    data() {
        return {
            columns: [],

            tempTitle: null,
            showColumnBool: false,
        };
    },
    methods: {
        addColumn() {
            this.showColumnBool = true;
        },
        saveColumn() {
            axios.post('/api/column/create', {
                title: this.tempTitle,
                board_id: this.activeBoard.id
            })
                .then(res => {
                    this.columns.push(res.data);
                    this.tempTitle = null;
                    this.showColumnBool = false;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        cancelColumn() {
            this.tempTitle = null;
            this.showColumnBool = false;
        },
        selectBoard() {
            this.$nextTick(() => {
                axios.get('/api/column/readAll', {params: {board_id: this.activeBoard.id}})
                    .then(res => {
                        this.columns = res.data;
                    })
            })
        },
        onDeleteColumn(id) {
            this.columns.splice(id, 1);
        },
        onSaveCard(card) {
            for (let column of this.columns) {
                if (card.column_id === column.id) {
                    column.cards.push(card);
                }
            }
        },
        onUpdateCard(activeCard) {
            for (let column of this.columns) {
                if (activeCard.columnId === column.id) {
                    for (let card of column.cards) {
                        if (card.id === activeCard.id) {
                            card.description = activeCard.description;
                            card.is_done = activeCard.isDone;
                        }
                    }
                }
            }
        },
        onDeleteCard(card) {
            for (let column of this.columns) {
                if (card.column_id === column.id) {
                    column.cards.splice(column.cards.indexOf(card), 1);
                }
            }
        },
        onDraggingStart(card) {

        },
        onDraggingEnd(card, column) {
            let currentCard;
            for (let col of this.columns) {
                if (col.id === card.column_id) {
                    for (let cardObj of col.cards) {
                        if (cardObj.id === card.id) {
                            currentCard = cardObj;
                        }
                    }
                    col.cards.splice(col.cards.indexOf(currentCard), 1);
                }
            }

            if (card.column_id !== column.id) {
                currentCard.column_id = column.id;
                column.cards.push(currentCard);
            }
        }
    },
};
</script>

<style scoped>
.columns {
    display: flex;
}
</style>
