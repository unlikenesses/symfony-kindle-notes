import React, {useRef} from 'react';
import PropTypes from 'prop-types';
import Note from './Note';
import Tags from '@yaireo/tagify/dist/react.tagify';
import {apiErrors} from "../../api/book_api";

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
    const { tagWhitelist, categoryWhitelist, book, loadingNotes, notes, deleteBook, deletingBook, deleteNote, deletingNote, handleTagChange, handleCategoryChange } = props;
    const tagifyRef = useRef();
    const deletingText = deletingBook === book.id ? 'Deleting...' : 'Delete Book';
    const categoryChanged = (e) => {
        handleCategoryChange(book.id, e.detail.value)
            .catch((err) => {
                if (err.message in apiErrors) {
                    tagifyRef.current.removeTags();
                    alert(apiErrors[err.message]);
                } else {
                    console.error('Unknown API error occurred', err.message);
                }
            });
    }
    const invalidTag = (e) => {
        const message = e.detail.message;
        let displayMessage = message;
        if (message === 'pattern mismatch') {
            displayMessage = 'Tags must be between 2 and 20 characters long and may only contain letters and numbers.';
        }
        alert(displayMessage);
    }
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
                <Tags
                    tagifyRef={tagifyRef}
                    value={book.categories.map(category => category.name).join(',')}
                    onChange={e => categoryChanged(e)}
                    settings={{
                        placeholder: 'Categories...',
                        maxTags: 3,
                        backspace: 'edit',
                        pattern: /^[a-zA-Z0-9\s]{3,20}$/
                    }}
                    whitelist={categoryWhitelist}
                    onInvalid={e => invalidTag(e)}
                />
                <p className="mt-2 d-flex justify-content-between">
                    <span>
                        {notes.numHighlights} Highlights | {notes.numNotes} Notes
                    </span>
                    <span>
                        <a className="btn btn-link p-0"
                           onClick={(event) => deleteBook(book, event)}>
                            {deletingText}
                        </a>
                    </span>
                </p>
                {notes.data.map((note) => (
                    <Note
                        tagWhitelist={tagWhitelist}
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
    tagWhitelist: PropTypes.array.isRequired,
    categoryWhitelist: PropTypes.array.isRequired,
    book: PropTypes.object,
    notes: PropTypes.object,
    loadingNotes: PropTypes.bool,
    deleteBook: PropTypes.func.isRequired,
    deletingBook: PropTypes.number.isRequired,
    deleteNote: PropTypes.func.isRequired,
    deletingNote: PropTypes.number.isRequired,
    handleTagChange: PropTypes.func.isRequired,
    handleCategoryChange: PropTypes.func.isRequired
};