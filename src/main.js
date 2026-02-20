function calculatePizza() {

    const pizzaType = document.getElementById('pizzaType').value;
    let pizza;
    
    switch(pizzaType) {
        case 'margherita':
            pizza = new Margherita();
            break;
        case 'pepperoni':
            pizza = new Pepperoni();
            break;
        case 'bavarian':
            pizza = new Bavarian();
            break;
    }
    
    const size = document.querySelector('input[name="size"]:checked').value;
    pizza.setSize(size);
    
    if (document.getElementById('mozzarella').checked) {
        pizza.addTopping('MOZZARELLA');
    }
    if (document.getElementById('cheeseBord').checked) {
        pizza.addTopping('CHEESE_BORD');
    }
    if (document.getElementById('cheddar').checked) {
        pizza.addTopping('CHEDDAR_PARMEZAN');
    }
    
    const info = pizza.getInfo();
    
    const result = document.getElementById('result');
    result.innerHTML = `
        <h3>${info.name}</h3>
        <p>Размер: ${info.size}</p>
        <p>Добавки: ${info.toppings.length ? info.toppings.join(', ') : 'нет'}</p>
        <p>--------------------------------</p>
        <p>ИТОГО:</p>
        <p>Цена: ${info.price}  руб.</p>
        <p>Калорийность: ${info.calories} ккал</p>
    `;
}