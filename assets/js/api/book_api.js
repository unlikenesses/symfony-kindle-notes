export function getBooks(category) {
    return fetch('/api/books/' + category)
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

export const apiErrors = {
    10: 'You have exceeded the maximum amount of allowable tags for a user'
};