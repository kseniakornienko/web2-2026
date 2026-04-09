function getPostIdFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id');
}

async function fetchPost(id) {
    try {
        const response = await fetch(`https://jsonplaceholder.typicode.com/posts/${id}`);
        
        if (!response.ok) {
            throw new Error(`Ошибка HTTP: ${response.status}`);
        }
        
        const post = await response.json();
        return post;
    } catch (error) {
        console.error('Ошибка при загрузке поста:', error);
        throw error;
    }
}

async function fetchComments(postId) {
    try {
        const response = await fetch(`https://jsonplaceholder.typicode.com/posts/${postId}/comments`);
        
        if (!response.ok) {
            throw new Error(`Ошибка HTTP: ${response.status}`);
        }
        
        const comments = await response.json();
        return comments;
    } catch (error) {
        console.error('Ошибка при загрузке комментариев:', error);
        throw error;
    }
}

function renderPost(post) {
    return `
        <div class="post-card">
            <h1 class="post-title">${escapeHtml(post.title)}</h1>
            <div class="post-body">
                <p>${escapeHtml(post.body)}</p>
            </div>
            <div class="post-meta">
                <span>Пост #${post.id}</span>
            </div>
        </div>
    `;
}

function renderComments(comments) {
    if (!comments || comments.length === 0) {
        return '<p class="no-comments">Нет комментариев</p>';
    }
    
    let html = '';
    for (const comment of comments) {
        html += `
            <div class="comment-item">
                <div class="comment-header">
                    <strong class="comment-name">${escapeHtml(comment.name)}</strong>
                    <span class="comment-email">${escapeHtml(comment.email)}</span>
                </div>
                <div class="comment-body">
                    <p>${escapeHtml(comment.body)}</p>
                </div>
            </div>
        `;
    }
    return html;
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

async function initPostPage() {
    const postId = getPostIdFromUrl();
    
    if (!postId) {
        document.getElementById('post-detail').innerHTML = '<div class="error">ID поста не указан</div>';
        document.getElementById('comments-list').innerHTML = '';
        return;
    }
    
    try {
        const post = await fetchPost(postId);
        document.getElementById('post-detail').innerHTML = renderPost(post);
        
        const comments = await fetchComments(postId);
        document.getElementById('comments-list').innerHTML = renderComments(comments);
        
    } catch (error) {
        document.getElementById('post-detail').innerHTML = `
            <div class="error">
                <h3>Ошибка загрузки</h3>
                <p>Не удалось загрузить пост. Пожалуйста, попробуйте позже.</p>
                <p>Детали: ${error.message}</p>
            </div>
        `;
        document.getElementById('comments-list').innerHTML = '<div class="error">Не удалось загрузить комментарии</div>';
    }
}

initPostPage();