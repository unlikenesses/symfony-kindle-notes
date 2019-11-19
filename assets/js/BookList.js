import React from 'react';
import Book from './Book';
import NoteList from './NoteList';
import PropTypes from 'prop-types';

export default function BookList(props) {
    const { books, notes, activeBook, handleBookClick, loadingBooks, loadingNotes } = props;
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
                            active={activeBook === row.id}
                            onClick={(event) => handleBookClick(row.id, event)}
                        />
                    ))}
                </div>
                <div className="col p-3">
                    <NoteList notes={notes} loadingNotes={loadingNotes}/>
                </div>
            </div>
        </div>
    );
}

BookList.propTypes = {
    books: PropTypes.array.isRequired,
    notes: PropTypes.array,
    activeBook: PropTypes.number,
    handleBookClick: PropTypes.func.isRequired,
    loadingBooks: PropTypes.bool.isRequired,
    loadingNotes: PropTypes.bool.isRequired
};