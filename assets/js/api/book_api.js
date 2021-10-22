export function getBooks(category) {
    return fetch('/api/books/category/' + category)
        .then(response => {
            return response.json().then((data) => data.data);
        });
}

export function getSingleBook(bookId) {
    return fetch('/api/books/book/' + bookId)
        .then(response => {
            return response.json().then((data) => data.data);
        });
}

export function getDeletedBooks() {
    return fetch('/api/deleted/books')
        .then(response => {
            return response.json().then((data) => data.data);
        });
}

export function getNotesForBook(bookId) {
    return fetch('/api/books/' + bookId + '/notes')
        .then(response => {
            return response.json().then((data) => data);
        });
}

export function getDeletedNotes() {
    return fetch('/api/deleted/notes')
        .then(response => {
            return response.json().then((data) => data.data);
        });
}

export function updateBookCategories(bookId, categories) {
    return fetch('/api/books/' + bookId + '/categories', {
        method: 'PUT',
        body: JSON.stringify(categories)
    });
}

export function getNotesForTag(tagId) {
    return fetch('/api/tags/' + tagId + '/notes')
        .then(response => {
            return response.json().then((data) => data);
        });
}

export function deleteBook(bookId) {
    return fetch('/api/books/' + bookId, {
        method: 'DELETE'
    })
        .then(response => {
            return response.json().then((data) => data);
        });
}

export function updateBookTitle(bookId, title) {
    return fetch('/api/books/' + bookId + '/title', {
        method: 'PUT',
        body: JSON.stringify(title)
    })
        .then(response => {
            return response.json().then((data) => data);
        });
}

export function deleteNote(noteId) {
    return fetch('/api/notes/' + noteId, {
        method: 'DELETE'
    })
        .then(response => {
            return response.json().then((data) => data);
        });
}

export function getCategories() {
    return fetch('/api/categories')
        .then(response => {
            return response.json().then((data) => data);
        });
}

export function getTags() {
    return fetch('/api/tags')
        .then(response => {
            return response.json().then((data) => data);
        });
}

export function updateNoteTags(noteId, tags) {
    return fetch('/api/notes/' + noteId + '/tags', {
        method: 'PUT',
        body: JSON.stringify(tags)
    });
}

export function permaDeleteBooks(ids) {
    return fetch('/api/books/permaDelete', {
        method: 'PUT',
        body: JSON.stringify(ids)
    });
}

export function permaDeleteNotes(ids) {
    return fetch('/api/notes/permaDelete', {
        method: 'PUT',
        body: JSON.stringify(ids)
    });
}

export function restoreBooks(ids) {
    return fetch('/api/books/restore', {
        method: 'PUT',
        body: JSON.stringify(ids)
    });
}

export function restoreNotes(ids) {
    return fetch('/api/notes/restore', {
        method: 'PUT',
        body: JSON.stringify(ids)
    });
}


export const apiErrors = {
    10: 'You have exceeded the maximum amount of allowable tags for a user',
    20: 'You have exceeded the maximum amount of allowable categories for a user'
};