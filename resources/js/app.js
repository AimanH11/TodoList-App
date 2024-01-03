import { createApp } from 'vue';
import TodoList from './components/TodoList.vue';

const app = createApp(TodoList);

app.component('todo-list', TodoList.default); // Use the 'default' property

app.mount('#app');
