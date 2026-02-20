class PizzaOrder {
    constructor() {
        this.pizzas = [];
    }

    addPizza(pizza) {
        if (!(pizza instanceof Pizza)) {
            throw new Error('Можно добавить только объект класса Pizza');
        }
        this.pizzas.push(pizza);
    }

    removePizza(index) {
        if (index >= 0 && index < this.pizzas.length) {
            this.pizzas.splice(index, 1);
        }
    }

    calculateTotalPrice() {
        return this.pizzas.reduce((total, pizza) => total + pizza.calculatePrice(), 0);
    }

    calculateTotalCalories() {
        return this.pizzas.reduce((total, pizza) => total + pizza.calculateCalories(), 0);
    }

    getOrderDetails() {
        return {
            pizzas: this.pizzas.map(pizza => pizza.getInfo()),
            totalPrice: this.calculateTotalPrice(),
            totalCalories: this.calculateTotalCalories()
        };
    }
}