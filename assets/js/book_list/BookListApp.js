import React, {useState, useEffect} from 'react';
import { getBooks, getNotesForBook, deleteBook, deleteNote, getTags, updateNoteTags, getCategories, updateBookCategories } from '../api/book_api';
import Book from "./books/Book";
import Notes from "./notes/Notes";


const BookListApp = () => {
    const [activeBook, setActiveBook] = useState({});
    const [books, setBooks] = useState([]);
    const [notes, setNotes] = useState({});
    const [tagWhitelist, setTagWhitelist] = useState([]);
    const [activeCategory, setActiveCategory] = useState('');
    const [categoryWhitelist, setCategoryWhitelist] = useState([]);
    const [loadingBooks, setLoadingBooks] = useState(true);
    const [loadingNotes, setLoadingNotes] = useState(false);
    const [deletingNote, setDeletingNote] = useState(0);
    const [deletingBook, setDeletingBook] = useState(0);
    useEffect(() => {
        getBooks(getQueryStringCategory()).
            then((data) => {
                setBooks(data);
                setLoadingBooks(false);
            });
        getTags().
            then((data) => {
                const tags = data.tags.map(tag => tag.name);
                setTagWhitelist(tags);
            });
        getCategories().
            then((data) => {
                setCategoryWhitelist(data.categories);
            });
    }, []);
    const categoryHeader = () => {
        if (activeCategory !== '') {
            return <p className="border-bottom p-3">
                Category: <mark>{decodeURIComponent(activeCategory)}</mark>
            </p>;
        }
    }
    const getQueryStringCategory = () => {
        let category = null;
        let path = window.location.pathname.split('/');
        if (path.length > 2 && path[2] === 'category') {
            category = path[3];
            setActiveCategory(category);
        }

        return category;
    }
    const getNotes = (book) => {
        setLoadingNotes(true);
        getNotesForBook(book.id).then((data) => {
            setNotes(data);
            setLoadingNotes(false);
            window.scrollTo({
                top: 0,
                left: 0,
                behavior: 'smooth'
            });
        });
    }
    const handleBookClick = (book, event) => {
        setActiveBook(book);
        getNotes(book);
    }
    const deleteSelectedBook = (book, event) => {
        if (confirm('Delete this book?')) {
            setDeletingBook(book.id);
            deleteBook(book.id).then((data) => {
                setDeletingBook(0);
                window.location = '/books';
            });
        }
    }
    const deleteNoteFromBook = (note, event) => {
        if (confirm('Delete this note?')) {
            setDeletingNote(note.id);
            deleteNote(note.id).then((data) => {
                setDeletingNote(0);
                getNotes(activeBook);
            });
        }
    }
    const onCategoryPillClick = (category) => {
        setCategoryURL(category);
        setActiveCategory(category);
        setLoadingBooks(true);
        getBooks(category).
            then((data) => {
                setBooks(data);
                setLoadingBooks(false);
            });
    }
    const setCategoryURL = (category) => {
        let path = window.location.pathname.split('/');
        category = encodeURIComponent(category);
        window.history.pushState({}, '', '/' + path[1] + '/category/' + category);
    }
    const handleTagChange = (noteId, newTags) => {
        newTags = newTags ? JSON.parse(newTags) : [];
        const tags = newTags.map(tag => tag.value);
        return updateNoteTags(noteId, tags)
            .then((response) => {
                if (! response.ok) {
                    return response.json().then(response => {throw Error(response.error)});
                }
                return response;
            })
            .then(() => {
                updateTagWhitelist(tags);
            });
    }
    const updateTagWhitelist = (newTags) => {
        for (let tag of newTags) {
            if (tagWhitelist.indexOf(tag) < 0) {
                const newTagList = [...tagWhitelist];
                newTagList.push(tag);
                setTagWhitelist(newTagList);
            }
        }
    }
    const handleCategoryChange = (bookId, newCategories) => {
        newCategories = newCategories ? JSON.parse(newCategories) : [];
        const categories = newCategories.map(category => category.value);
        return updateBookCategories(bookId, categories)
            .then((response) => {
                if (! response.ok) {
                    return response.json().then(response => {throw Error(response.error)});
                }
                return response.json();
            })
            .then((data) => {
                console.log('categories', data.categories);
                updateBookCategory(bookId, data.categories);
                updateCategoryWhitelist(categories);
            });
    }
    const updateBookCategory = (bookId, categories) => {
        const book = books.find(book => book.id === bookId);
        // let newCategories = [];
        // categories.forEach((category, idx) => {
        //     newCategories.push({
        //         'id': idx,
        //         'name': category
        //     });
        // })
        console.log('updating book with categories', categories)
        book.categories = categories;
    }
    const updateCategoryWhitelist = (newCategories) => {
        for (let category of newCategories) {
            if (categoryWhitelist.indexOf(category) < 0) {
                const newCategoryList = [...categoryWhitelist];
                newCategoryList.push(category);
                setCategoryWhitelist(newCategoryList);
            }
        }
    }
    if (loadingBooks) {
        return (
            <div className="d-flex justify-content-center mt-5">
                <div className="spinner-border" role="status">
                    <span className="sr-only">Loading...</span>
                </div>
            </div>
        )
    }
    return (
        <div className="container-fluid p-0">
            <div className="row no-gutters">
                <div className="col-2 border-right">
                    {categoryHeader()}
                    {books.map((row) => (
                        <Book
                            key={row.id}
                            title={row.title}
                            author={row.author}
                            categories={row.categories}
                            active={activeBook != null && activeBook.id === row.id}
                            onClick={(event) => handleBookClick(row, event)}
                            onCategoryPillClick={onCategoryPillClick}
                        />
                    ))}
                </div>
                <div className="col p-3">
                    <Notes
                        tagWhitelist={tagWhitelist}
                        categoryWhitelist={categoryWhitelist}
                        book={activeBook}
                        notes={notes}
                        loadingNotes={loadingNotes}
                        deleteBook={deleteSelectedBook}
                        deletingBook={deletingBook}
                        deleteNote={deleteNoteFromBook}
                        deletingNote={deletingNote}
                        handleTagChange={handleTagChange}
                        handleCategoryChange={handleCategoryChange}
                    />
                </div>
            </div>
        </div>
    );
}

export default BookListApp;