import React from 'react';
import PropTypes from 'prop-types';
import Note from './Note';

function BookTitle(props) {
    const { book } = props;
    if (book) {
        return (
            <div>
                <h2>{book.title}</h2>
                <h3>{book.author}</h3>
            </div>
        )
    } else {
        return null;
    }
}

export default function NoteList(props) {
    const { book, loadingNotes, notes } = props;
    if (loadingNotes) {
        return (
            <div className="d-flex justify-content-center mt-5">
                <div className="spinner-border" role="status">
                    <span className="sr-only">Loading...</span>
                </div>
            </div>
        )
    }
    return (
        <div>
            <BookTitle book={book} />
            {notes.map((note) => (
                <Note
                    key={note.id}
                    note={note}
                />
            ))}
        </div>
    )
}

NoteList.propTypes = {
    book: PropTypes.object,
    notes: PropTypes.array,
    loadingNotes: PropTypes.bool
};