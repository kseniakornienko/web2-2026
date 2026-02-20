class Pizza {
    static SIZES = {
        LARGE: { name: 'Большая', price: 200, calories: 200 },
        SMALL: { name: 'Маленькая', price: 100, calories: 100 }
    };

    static TOPPINGS = {
        MOZZARELLA: { name: 'Сливочная моцарелла', price: 50, calories: 20 },
        CHEESE_BORD: { name: 'Сырный борт', price: { LARGE: 300, SMALL: 150 }, calories: 50 },
        CHEDDAR_PARMEZAN: { name: 'Чедер и пармезан', price: { LARGE: 300, SMALL: 150 }, calories: 50 }
    };

    constructor(name, basePrice, baseCalories) {
        this.name = name;
        this.basePrice = basePrice;
        this.baseCalories = baseCalories;
        this.size = null;
        this.toppings = [];
    }

    setSize(size) {
        if (!Pizza.SIZES[size]) {
            throw new Error('Неверный размер пиццы');
        }
        this.size = size;
    }

    getSize() {
        return this.size ? Pizza.SIZES[this.size] : null;
    }

    addTopping(topping) {
        if (!Pizza.TOPPINGS[topping]) {
            throw new Error('Неверная добавка');
        }
        if (!this.toppings.includes(topping)) {
            this.toppings.push(topping);
        }
    }

    removeTopping(topping) {
        const index = this.toppings.indexOf(topping);
        if (index !== -1) {
            this.toppings.splice(index, 1);
        }
    }

    getToppings() {
        return this.toppings.map(topping => Pizza.TOPPINGS[topping].name);
    }

    calculatePrice() {
        let totalPrice = this.basePrice;
        
        if (this.size) {
            totalPrice += Pizza.SIZES[this.size].price;
        }

        this.toppings.forEach(topping => {
            const toppingData = Pizza.TOPPINGS[topping];
            if (typeof toppingData.price === 'object') {
                totalPrice += toppingData.price[this.size];
            } else {
                totalPrice += toppingData.price;
            }
        });

        return totalPrice;
    }

    calculateCalories() {
        let totalCalories = this.baseCalories;
        
        if (this.size) {
            totalCalories += Pizza.SIZES[this.size].calories;
        }

        this.toppings.forEach(topping => {
            totalCalories += Pizza.TOPPINGS[topping].calories;
        });

        return totalCalories;
    }

    getInfo() {
        return {
            name: this.name,
            size: this.size ? Pizza.SIZES[this.size].name : 'Не выбран',
            toppings: this.getToppings(),
            price: this.calculatePrice(),
            calories: this.calculateCalories()
        };
    }
}