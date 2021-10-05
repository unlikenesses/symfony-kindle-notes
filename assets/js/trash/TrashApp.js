import React, {useState, useEffect} from 'react';
import {getDeletedBooks, getDeletedNotes} from "../api/book_api";
import TrashSection from './TrashSection';

const TrashApp = () => {
    const [books, setBooks] = useState([]);
    const [notes, setNotes] = useState({});
    const [loadingBooks, setLoadingBooks] = useState(true);
    const [loadingNotes, setLoadingNotes] = useState(true);
    useEffect(() => {
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
    }, []);
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
    return (
        <div className="p-8">
            <TrashSection
                data={books}
                title="Books"
                loading={loadingBooks}
                handleCheckboxClick={handleBookCheckboxClick}
                setAllChecked={setBookAllChecked}
                setNoneChecked={setBookNoneChecked}
            />
            <TrashSection
                data={notes}
                title="Notes"
                loading={loadingNotes}
                handleCheckboxClick={handleNoteCheckboxClick}
                setAllChecked={setNoteAllChecked}
                setNoneChecked={setNoteNoneChecked}
            />
        </div>
    );
}

export default TrashApp;