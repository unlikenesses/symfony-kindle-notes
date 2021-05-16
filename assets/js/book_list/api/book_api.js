export function getBooks() {
    return fetch('/api/books')
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

export function deleteNote(noteId) {
    return fetch('/api/notes/' + noteId, {
        method: 'DELETE'
    })
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