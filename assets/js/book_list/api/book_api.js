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