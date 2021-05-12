import React from 'react';
import Book from './Book';
import Notes from '../notes/Notes';
import PropTypes from 'prop-types';

const Books = (props) => {
    const { tags, books, notes, activeBook, handleBookClick, loadingBooks, loadingNotes, deleteNote, deletingNote, handleTagChange } = props;
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
                            active={activeBook != null && activeBook.id === row.id}
                            onClick={(event) => handleBookClick(row, event)}
                        />
                    ))}
                </div>
                <div className="col p-3">
                    <Notes
                        tags={tags}
                        book={activeBook}
                        notes={notes}
                        loadingNotes={loadingNotes}
                        deleteNote={deleteNote}
                        deletingNote={deletingNote}
                        handleTagChange={handleTagChange}
                    />
                </div>
            </div>
        </div>
    );
}

export default Books;

Books.propTypes = {
    tags: PropTypes.array.isRequired,
    books: PropTypes.array.isRequired,
    notes: PropTypes.object,
    activeBook: PropTypes.object,
    handleBookClick: PropTypes.func.isRequired,
    loadingBooks: PropTypes.bool.isRequired,
    loadingNotes: PropTypes.bool.isRequired,
    deleteNote: PropTypes.func.isRequired,
    deletingNote: PropTypes.number.isRequired,
    handleTagChange: PropTypes.func.isRequired
};