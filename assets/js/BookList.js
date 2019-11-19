import React from 'react';
import NoteList from './NoteList';
import PropTypes from 'prop-types';

export default function BookList(props) {
    const { books, highlightedRowId, handleRowClick } = props;
    return (
        <table className="book-table">
            <thead>
            <tr>
                <th>Date</th>
                <th>Title</th>
                <th>Author</th>
                <th>Notes</th>
            </tr>
            </thead>
            <tbody>
            {books.map((row) => (
                <tr
                    key={row.id}
                    className={highlightedRowId === row.id ? 'table-active' : ''}
                    onClick={(event) => handleRowClick(row.id, event)}
                >
                    <td>{row.date}</td>
                    <td>{row.title}</td>
                    <td>{row.author}</td>
                    <td>
                        <NoteList notes={row.notes}/>
                    </td>
                </tr>
            ))}
            </tbody>
        </table>
    );
}

BookList.propTypes = {
    books: PropTypes.array.isRequired,
    highlightedRowId: PropTypes.number,
    handleRowClick: PropTypes.func.isRequired
};