import React, {useState, useEffect} from 'react';
import Books from './books/Books';
import { getBooks, getNotesForBook, deleteNote, getTags, updateNoteTags } from './api/book_api';

const BookListApp = () => {
    const [activeBook, setActiveBook] = useState({});
    const [books, setBooks] = useState([]);
    const [notes, setNotes] = useState({});
    const [tags, setTags] = useState([]);
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
        updateNoteTags(noteId, tags);
        updateTagList(tags);
    }
    const updateTagList = (newTags) => {
        for (let tag of newTags) {
            if (tags.indexOf(tag) < 0) {
                console.log('adding tag ' + tag);
                const newTagList = [...tags];
                newTagList.push(tag);
                console.log('setting', newTagList);
                setTags(newTagList);
            }
        }
    }
    return (
        <Books
            tags={tags}
            books={books}
            notes={notes}
            activeBook={activeBook}
            loadingBooks={loadingBooks}
            loadingNotes={loadingNotes}
            deletingNote={deletingNote}
            handleTagChange={handleTagChange}
            handleBookClick={handleBookClick}
            deleteNote={deleteNoteFromBook}
        />
    );
}

export default BookListApp;