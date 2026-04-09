import { Catalog } from "./src/components/catalog.js"

const renderPostItem = item => `
    <a href="post.html?id=${item.id}" class="post-item">
        <span class="post-item__title">
            ${escapeHtml(item.title)}
        </span>
        <span class="post-item__body">
            ${escapeHtml(item.body.substring(0, 100))}...
        </span>
    </a>
`

async function getPostItems({ limit, page }) {
    try {
        const response = await fetch(`https://jsonplaceholder.typicode.com/posts?_limit=${limit}&_page=${page}`);
        
        if (!response.ok) {
            throw new Error(`Ошибка HTTP: ${response.status}`);
        }
        
        const total = +response.headers.get('x-total-count');
        const items = await response.json();
        
        return { items, total };
    } catch (error) {
        console.error('Ошибка при загрузке постов:', error);
        return { items: [], total: 0 };
    }
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

const init = () => {
    const catalog = document.getElementById('catalog');
    if (catalog) {
        new Catalog(catalog, { 
            renderItem: renderPostItem,
            getItems: getPostItems
        }).init();
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}