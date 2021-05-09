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

const Notes = (props) => {
    const { tags, book, loadingNotes, notes, deleteNote, deletingNote, handleTagChange } = props;
    if (loadingNotes) {
        return (
            <div>
                <BookTitle book={book}/>
                <div className="d-flex justify-content-center mt-5">
                    <div className="spinner-border" role="status">
                        <span className="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        )
    }
    if (notes.data) {
        return (
            <div>
                <BookTitle book={book}/>
                <p>
                    {notes.numHighlights} Highlights | {notes.numNotes} Notes
                </p>
                {notes.data.map((note) => (
                    <Note
                        tags={tags}
                        key={note.id}
                        note={note}
                        handleTagChange={handleTagChange}
                        deleteNote={deleteNote}
                        deletingNote={deletingNote}
                    />
                ))}
            </div>
        )
    }
    return <div></div>
}

export default Notes;

Notes.propTypes = {
    book: PropTypes.object,
    notes: PropTypes.object,
    loadingNotes: PropTypes.bool,
    deleteNote: PropTypes.func.isRequired,
    deletingNote: PropTypes.number.isRequired,
    handleTagChange: PropTypes.func.isRequired
};