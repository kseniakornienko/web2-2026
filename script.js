if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

function init() {
    const listItemsEl = document.getElementById('list-items');
    const items = new ListItems(listItemsEl);
    items.init();
}

function ListItems(el) {
    this.el = el;

    this.init = function() {
        this.el.addEventListener('click', (event) => {
            const arrow = event.target.closest('.list-item__arrow');
            if (arrow) {
                const parentItem = arrow.closest('[data-parent]');
                if (parentItem) {
                    this.toggleItems(parentItem);
                }
            }

            const inner = event.target.closest('.list-item__inner');
            if (inner) {
                const parentItem = inner.closest('[data-parent]');
                if (parentItem) {
                    this.toggleItems(parentItem);
                }
            }
        });
    };

    this.toggleItems = function(parent) {
        parent.classList.toggle('list-item_open');
    };
}