let currentPizza = null;
let order = new PizzaOrder();

// Инициализация
document.addEventListener('DOMContentLoaded', () => {
    // Выбираем пиццу по умолчанию
    selectPizza('margherita');
    
    // Добавляем обработчики событий
    document.querySelectorAll('.pizza-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            selectPizza(btn.dataset.pizza);
            updateUI();
        });
    });
    
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            updateUI();
        });
    });
    
    document.querySelectorAll('[data-topping]').forEach(checkbox => {
        checkbox.addEventListener('change', () => updateUI());
    });
    
    document.getElementById('addToCartBtn').addEventListener('click', addToCart);
    
    updateUI();
});

function selectPizza(pizzaType) {
    document.querySelectorAll('.pizza-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.pizza === pizzaType) {
            btn.classList.add('active');
        }
    });
    
    switch(pizzaType) {
        case 'margherita':
            currentPizza = new Margherita();
            break;
        case 'pepperoni':
            currentPizza = new Pepperoni();
            break;
        case 'bavarian':
            currentPizza = new Bavarian();
            break;
    }
}

function updateUI() {
    if (!currentPizza) return;
    
    // Получаем выбранный размер
    const selectedSize = document.querySelector('.size-btn.active').dataset.size;
    currentPizza.setSize(selectedSize);
    
    // Очищаем текущие топпинги
    currentPizza.toppings = [];
    
    // Добавляем выбранные топпинги
    document.querySelectorAll('[data-topping]:checked').forEach(checkbox => {
        currentPizza.addTopping(checkbox.dataset.topping);
    });
    
    // Обновляем кнопку
    const price = currentPizza.calculatePrice();
    const calories = currentPizza.calculateCalories();
    
    document.getElementById('totalPrice').textContent = price;
    document.getElementById('totalCalories').textContent = calories;
}

function addToCart() {
    if (!currentPizza) return;
    
    // Создаем копию пиццы для заказа
    const pizzaCopy = createPizzaCopy(currentPizza);
    order.addPizza(pizzaCopy);
    
    updateOrderDisplay();
    updateUI();
}

function createPizzaCopy(pizza) {
    let copy;
    
    switch(pizza.name) {
        case 'Маргарита':
            copy = new Margherita();
            break;
        case 'Пепперони':
            copy = new Pepperoni();
            break;
        case 'Баварская':
            copy = new Bavarian();
            break;
    }
    
    copy.setSize(pizza.size);
    pizza.toppings.forEach(topping => {
        copy.addTopping(topping);
    });
    
    return copy;
}

function updateOrderDisplay() {
    const orderList = document.getElementById('orderList');
    const details = order.getOrderDetails();
    
    if (details.pizzas.length === 0) {
        orderList.innerHTML = '<p style="color: #6c757d; text-align: center;">Корзина пуста</p>';
        document.getElementById('orderTotalPrice').textContent = '0';
        document.getElementById('orderTotalCalories').textContent = '0';
        return;
    }
    
    let html = '';
    details.pizzas.forEach((pizza, index) => {
        html += `
            <div class="order-item">
                <div class="order-item-info">
                    <div class="order-item-name">${pizza.name} (${pizza.size})</div>
                    <div class="order-item-details">Добавки: ${pizza.toppings.length ? pizza.toppings.join(', ') : 'нет'}</div>
                </div>
                <div>
                    <span class="order-item-price">${pizza.price} руб. | ${pizza.calories} ккал</span>
                    <button class="remove-btn" onclick="removePizza(${index})">Удалить</button>
                </div>
            </div>
        `;
    });
    
    orderList.innerHTML = html;
    document.getElementById('orderTotalPrice').textContent = details.totalPrice;
    document.getElementById('orderTotalCalories').textContent = details.totalCalories;
}

function removePizza(index) {
    order.removePizza(index);
    updateOrderDisplay();
}