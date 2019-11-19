export function getBooks() {
    return fetch('/api/books')
        .then(response => {
            return response.json().then((data) => data.data);
        });
}
