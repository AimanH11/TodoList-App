<template>
  <div class="todo-list-container">
    <div class="header">
      <h1>Todo List</h1>
      <button @click="showAddForm">Add Todo</button>
    </div>

    <form v-if="showForm" @submit.prevent="addItem" class="add-form">
      <label for="newItem">New Todo:</label>
      <div class="input-group">
        <input type="text" v-model="newItem.title" placeholder="Title" required />
        <input type="text" v-model="newItem.description" placeholder="Description" />
        <button type="submit">Add</button>
      </div>
    </form>

    <div v-if="todoItems.length > 0">
      <h2>Tasks:</h2>
      <table class="todo-table">
        <thead>
          <tr class="todo-item">
            <th>Title</th>
            <th>Description</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in todoItems" :key="item.id" class="todo-item">
            <td :class="{ completed: item.completed }">{{ item.title }}</td>
            <td :class="{ completed: item.completed }">{{ item.description }}</td>
            <td>
              <button class="todo-complete" v-if="!item.completed" @click="completeItem(item.id)">Complete</button>
              <span v-else>Completed</span>
              <button  class="delete-button" @click="deleteItem(item)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    initialTodoItems: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      todoItems: this.initialTodoItems,
      newItem: {
        title: '',
        description: '',
      },
      showForm: false,
    };
  },
  mounted() {
    this.fetchTodoItems();
  },
  methods: {
    fetchTodoItems() {
      axios.get('/').then((response) => {
        this.todoItems = response.data;
      }).catch((error) => {
        console.error('Error fetching todo items:', error);
      });
    },

    addItem() {
    if (this.newItem.title.trim() !== "") {
        axios.post('/todo', this.newItem).then(() => {
        this.newItem.title = "";
        this.newItem.description = "";
        this.fetchTodoItems();
        this.showForm = false;
        }).catch((error) => {
        console.error('Error adding todo item:', error);
        });
    }
    },

    completeItem(itemId) {
      axios.put(`/${itemId}`, {}).then(() => {
        this.fetchTodoItems();
      });
    },

    deleteItem(item) {
        axios.delete(`/${item.id}`).then(() => {
            this.todoItems = this.todoItems.filter(todo => todo.id !== item.id);
        }).catch((error) => {
            console.error('Error deleting todo item:', error);
        });
    },

    showAddForm() {
      this.showForm = true;
    },
  },
};
</script>


<style scoped>
.todo-list-container {
  max-width: 600px;
  margin: auto;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.header button {
  cursor: pointer;
  padding: 8px;
  background-color: #4caf50;
  color: white;
  border: none;
}

.add-form {
  margin-bottom: 16px;
}

.input-group {
  display: flex;
}

.input-group input {
  flex: 1;
  padding: 8px;
}

.input-group button {
  cursor: pointer;
  padding: 8px;
  background-color: #4caf50;
  color: white;
  border: none;
}
.todo-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 16px;
}

.todo-table th, .todo-table td {
  border: none;
  padding: 12px;
  margin: 0;
  text-align: left;
  vertical-align: top;
}

.todo-table th {
  background-color: #f2f2f200;
  font-weight: bold;
}

.todo-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 8px;
}

.todo-item span {
  flex: 1;
}

.todo-item.completed span {
  text-decoration: line-through;
  color: #020202;
}

.todo-item button {
  cursor: pointer;
  padding: 8px; /* Adjust padding as needed */
  margin-left: 8px;
  border: none;
  color: white;
}

.todo-complete {
  background-color: #4caf50;
}

.delete-button {
  background-color: #f44336;
}

.completed-text {
  color: #000000;
}
</style>
