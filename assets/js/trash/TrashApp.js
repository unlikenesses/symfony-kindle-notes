import React, {useState, useEffect} from 'react';
import {getDeletedBooks, getDeletedNotes, permaDeleteBooks, permaDeleteNotes, restoreBooks, restoreNotes} from "../api/book_api";
import TrashSection from './TrashSection';

const TrashApp = () => {
    const [books, setBooks] = useState([]);
    const [notes, setNotes] = useState({});
    const [loadingBooks, setLoadingBooks] = useState(true);
    const [loadingNotes, setLoadingNotes] = useState(true);
    useEffect(() => {
        getBooks();
        getNotes();
    }, []);
    const getBooks = () => {
        setLoadingBooks(true);
        getDeletedBooks().
            then((data) => {
                setBooks(data.map((book) => {
                    return {
                        'id': book.id,
                        'firstCol': book.title,
                        'secondCol': book.author,
                        'checked': false
                    }
                }))
                setLoadingBooks(false);
            });
    }
    const getNotes = () => {
        setLoadingNotes(true);
        getDeletedNotes().
            then((data) => {
                setNotes(data.map((note) => {
                    return {
                        'id': note.id,
                        'firstCol': note.note,
                        'secondCol': '',
                        'checked': false
                    }
                }));
                setLoadingNotes(false);
            });
    }
    const handleBookCheckboxClick = (rowId) => {
        let index = books.findIndex(b => b.id === rowId);
        if (index === -1) {
            console.error('Error updating checkbox: no book with ID ' + rowId + ' found');
        } else {
            let book = books[index];
            book.checked = ! book.checked;
            setBooks([
                ...books.slice(0, index),
                Object.assign({}, books[index], book),
                ...books.slice(index+1)
            ]);
        }
    }
    const handleNoteCheckboxClick = (rowId) => {
        let index = notes.findIndex(n => n.id === rowId);
        if (index === -1) {
            console.error('Error updating checkbox: no note with ID ' + rowId + ' found');
        } else {
            let note = notes[index];
            note.checked = ! note.checked;
            setNotes([
                ...notes.slice(0, index),
                Object.assign({}, notes[index], note),
                ...notes.slice(index+1)
            ]);
        }
    }
    const setBookAllChecked = () => {
        let checkedBooks = books.map((book) => {
            book.checked = true;
            return book;
        });
        setBooks(checkedBooks);
    }
    const setBookNoneChecked = () => {
        let unCheckedBooks = books.map((book) => {
            book.checked = false;
            return book;
        });
        setBooks(unCheckedBooks);
    }
    const setNoteAllChecked = () => {
        let checkedNotes = notes.map((note) => {
            note.checked = true;
            return note;
        });
        setNotes(checkedNotes);
    }
    const setNoteNoneChecked = () => {
        let unCheckedNotes = notes.map((note) => {
            note.checked = false;
            return note;
        });
        setNotes(unCheckedNotes);
    }
    const handleBooksAction = (action) => {
        let bookIds = books.filter((book) => {
            return book.checked;
        }).map(book => book.id);
        if (bookIds.length < 1) {
            return;
        }
        if (action === 'delete') {
            permaDeleteBooks(bookIds).
                then((data) => {
                    getBooks();
                });
        }
    }
    const handleNotesAction = (action) => {
        console.log('action = ' + action)
    }
    return (
        <div className="p-8">
            <TrashSection
                data={books}
                title="Books"
                loading={loadingBooks}
                handleCheckboxClick={handleBookCheckboxClick}
                setAllChecked={setBookAllChecked}
                setNoneChecked={setBookNoneChecked}
                handleAction={handleBooksAction}
            />
            <TrashSection
                data={notes}
                title="Notes"
                loading={loadingNotes}
                handleCheckboxClick={handleNoteCheckboxClick}
                setAllChecked={setNoteAllChecked}
                setNoneChecked={setNoteNoneChecked}
                handleAction={handleNotesAction}
            />
        </div>
    );
}

export default TrashApp;