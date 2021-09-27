import React, {useState, useEffect} from 'react';
import {getDeletedBooks, getDeletedNotes} from "../api/book_api";
import TrashBooks from "./TrashBooks";
import TrashNotes from "./TrashNotes";

const TrashApp = () => {
    const [books, setBooks] = useState([]);
    const [notes, setNotes] = useState({});
    const [loadingBooks, setLoadingBooks] = useState(true);
    const [loadingNotes, setLoadingNotes] = useState(true);
    useEffect(() => {
        getDeletedBooks().
            then((data) => {
                setBooks(data);
                setLoadingBooks(false);
            });
        getDeletedNotes().
            then((data) => {
                setNotes(data);
                setLoadingNotes(false);
            });
    }, []);
    return (
        <div className="p-8">
            <TrashBooks
                books={books}
                loadingBooks={loadingBooks}
            />
            <TrashNotes
                notes={notes}
                loadingNotes={loadingNotes}
            />
        </div>
    );
}

export default TrashApp;