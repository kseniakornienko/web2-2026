import Auth from "../services/auth.js";
import location from "../services/location.js";
import loading from "../services/loading.js";
import config from "../services/config.js";

let todos = [];

const init = async () => {
    loading.start();
    
    const { ok: isLogged } = await Auth.me();

    if (!isLogged) {
        loading.stop();
        return location.login();
    }

    await loadTodos();
    loading.stop();
    
    createAddTodoForm();
    renderTodos();
}

async function loadTodos() {
    try {
        const response = await fetch(config.BASE_URL + '/todo', {
            headers: {
                'Authorization': 'Bearer ' + Auth.token
            }
        });
        
        const data = await response.json();
        
        if (Array.isArray(data)) {
            todos = data;
        } else if (data && data.data && Array.isArray(data.data)) {
            todos = data.data;
        } else {
            todos = [];
        }
        renderTodos();
    } catch (error) {
        console.error('Ошибка загрузки todos:', error);
        todos = [];
        renderTodos();
    }
}

function createAddTodoForm() {
    const mainEl = document.querySelector('.main');
    
    const formHtml = `
        <div class="add-todo-form">
            <h2>Добавить новую задачу</h2>
            <form id="add-todo-form" class="form">
                <label class="text-field">
                    <input
                        type="text"
                        class="text-field__input"
                        name="description"
                        placeholder="Описание задачи"
                        required
                    >
                    <span class="text-field__error"></span>
                </label>
                <button type="submit" class="button">Добавить</button>
            </form>
        </div>
        <div class="todos-list" id="todos-list"></div>
    `;
    
    mainEl.innerHTML = formHtml;
    
    const form = document.getElementById('add-todo-form');
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const descInput = form.querySelector('input[name="description"]');
            const description = descInput.value.trim();
            
            if (description) {
                await addTodo(description);
                descInput.value = '';
            }
        });
    }
}

async function addTodo(description) {
    try {
        const response = await fetch(config.BASE_URL + '/todo', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + Auth.token
            },
            body: JSON.stringify({ description })
        });
        
        const result = await response.json();
        console.log('Сервер вернул:', result);
        
        const newTodo = result.data;
        console.log('Новый todo:', newTodo);
        
        todos.unshift(newTodo);
        renderTodos();
    } catch (error) {
        console.error('Ошибка добавления todo:', error);
        alert('Не удалось добавить задачу');
    }
}

async function toggleTodoStatus(id, completed) {
    try {
        const todo = todos.find(t => t.id === id);
        if (!todo) return;
        
        const response = await fetch(config.BASE_URL + '/todo/' + id, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + Auth.token
            },
            body: JSON.stringify({ 
                description: todo.description,
                completed: completed 
            })
        });
        
        const updatedTodo = await response.json();
        
        const index = todos.findIndex(t => t.id === id);
        if (index !== -1) {
            todos[index] = updatedTodo;
            renderTodos();
        }
    } catch (error) {
        console.error('Ошибка обновления статуса:', error);
        alert('Не удалось изменить статус задачи');
        renderTodos();
    }
}

async function deleteTodo(id) {
    if (!confirm('Вы уверены, что хотите удалить задачу?')) return;
    
    try {
        await fetch(config.BASE_URL + '/todo/' + id, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + Auth.token
            }
        });
        
        todos = todos.filter(t => t.id !== id);
        renderTodos();
    } catch (error) {
        console.error('Ошибка удаления todo:', error);
        alert('Не удалось удалить задачу');
    }
}

function renderTodos() {
    const todosList = document.getElementById('todos-list');
    if (!todosList) return;
    
    if (todos.length === 0) {
        todosList.innerHTML = '<div class="empty-list">Нет задач. Добавьте первую!</div>';
        return;
    }
    
    const html = `
        <h2>Мои задачи</h2>
        <div class="todos-items">
            ${todos.map(todo => `
                <div class="todo-item ${todo.completed ? 'completed' : ''}" data-id="${todo.id}">
                    <div class="todo-item__left">
                        <input 
                            type="checkbox" 
                            class="todo-checkbox" 
                            ${todo.completed ? 'checked' : ''}
                            data-id="${todo.id}"
                        >
                        <span class="todo-title">${escapeHtml(todo.description)}</span>
                    </div>
                    <button class="delete-btn" data-id="${todo.id}">🗑️ Удалить</button>
                </div>
            `).join('')}
        </div>
    `;
    
    todosList.innerHTML = html;
    
    document.querySelectorAll('.todo-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', async (e) => {
            e.stopPropagation();
            const id = parseInt(checkbox.dataset.id);
            const completed = checkbox.checked;
            await toggleTodoStatus(id, completed);
        });
    });
    
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const id = parseInt(btn.dataset.id);
            await deleteTodo(id);
        });
    });
}

function escapeHtml(str) {
    if (!str) return '';
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

if (document.readyState === 'loading') {
    document.addEventListener("DOMContentLoaded", init);
} else {
    init();
}