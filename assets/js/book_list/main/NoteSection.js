import React, {useRef} from 'react';
import PropTypes from 'prop-types';
import BookHeader from "./BookHeader";
import Note from './Note';
import Tags from '@yaireo/tagify/dist/react.tagify';
import {apiErrors} from "../../api/book_api";
import Spinner from "../../common/Spinner";

const NoteSection = (props) => {
    const { tagWhitelist, categoryWhitelist, book, loadingNotes, notes, deleteBook, deletingBook, deleteNote, deletingNote, handleTagChange, handleCategoryChange, saveBookTitle, saveBookAuthor } = props;
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
                <BookHeader
                    book={book}
                    saveBookTitle={saveBookTitle}
                    saveBookAuthor={saveBookAuthor}
                />
                <Spinner />
            </div>
        )
    }
    if (notes.data) {
        return (
            <div>
                <BookHeader
                    book={book}
                    saveBookTitle={saveBookTitle}
                    saveBookAuthor={saveBookAuthor}
                />
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
                <p className="mt-2 mb-4 flex justify-between">
                    <span className="text-gray-600">
                        {notes.numHighlights} Highlights | {notes.numNotes} Notes
                    </span>
                    <span>
                        <a className="cursor-pointer hover:underline text-blue-400"
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

export default NoteSection;

NoteSection.propTypes = {
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
    handleCategoryChange: PropTypes.func.isRequired,
    saveBookTitle: PropTypes.func.isRequired,
    saveBookAuthor: PropTypes.func.isRequired
};