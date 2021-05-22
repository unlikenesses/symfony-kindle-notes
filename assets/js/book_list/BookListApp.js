import React, {useState, useEffect} from 'react';
import { getBooks, getNotesForBook, deleteNote, getTags, updateNoteTags, updateBookCategories } from '../api/book_api';
import Book from "./books/Book";
import Notes from "./notes/Notes";

const BookListApp = () => {
    const [activeBook, setActiveBook] = useState({});
    const [books, setBooks] = useState([]);
    const [notes, setNotes] = useState({});
    const [tags, setTags] = useState([]);
    const [categories, setCategories] = useState([]);
    const [loadingBooks, setLoadingBooks] = useState(true);
    const [loadingNotes, setLoadingNotes] = useState(false);
    const [deletingNote, setDeletingNote] = useState(0);

    useEffect(() => {
        getBooks().
            then((data) => {
                setBooks(data);
                setLoadingBooks(false);
            });
        getTags().
            then((data) => {
                const tags = data.tags.map(tag => tag.name);
                setTags(tags);
            });
    }, []);
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
    const deleteNoteFromBook = (note, event) => {
        if (confirm('Delete this note?')) {
            setDeletingNote(note.id);
            deleteNote(note.id).then((data) => {
                setDeletingNote(0);
                getNotes(activeBook);
            });
        }
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
                updateTagList(tags);
            });
    }
    const handleCategoryChange = (bookId, newCategories) => {
        newCategories = newCategories ? JSON.parse(newCategories) : [];
        const categories = newCategories.map(category => category.value);
        return updateBookCategories(bookId, categories)
            .then((response) => {
                if (! response.ok) {
                    return response.json().then(response => {throw Error(response.error)});
                }
                return response;
            })
            .then(() => {
                updateCategoryList(categories);
            });
    }
    const updateTagList = (newTags) => {
        for (let tag of newTags) {
            if (tags.indexOf(tag) < 0) {
                const newTagList = [...tags];
                newTagList.push(tag);
                setTags(newTagList);
            }
        }
    }
    const updateCategoryList = (newCategories) => {
        for (let category of newCategories) {
            if (categories.indexOf(category) < 0) {
                const newCategoryList = [...categories];
                newCategoryList.push(category);
                setCategories(newCategoryList);
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
                    {books.map((row) => (
                        <Book
                            key={row.id}
                            title={row.title}
                            author={row.author}
                            categories={row.categories}
                            active={activeBook != null && activeBook.id === row.id}
                            onClick={(event) => handleBookClick(row, event)}
                        />
                    ))}
                </div>
                <div className="col p-3">
                    <Notes
                        tags={tags}
                        categories={categories}
                        book={activeBook}
                        notes={notes}
                        loadingNotes={loadingNotes}
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