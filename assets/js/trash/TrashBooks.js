import React from 'react';
import PropTypes from "prop-types";

const TrashBooks = (props) => {
    const tableHead = () => {
        return (
            <thead>
                <tr>
                    <th>Books</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
        );
    }
    if (props.loadingBooks) {
        return (
            <div className="row no-gutters">
                <table className="table table-hover">
                    {tableHead()}
                    <tbody>
                        <div className="d-flex justify-content-center mt-5">
                            <div className="spinner-border" role="status">
                                <span className="sr-only">Loading...</span>
                            </div>
                        </div>
                    </tbody>
                </table>
            </div>
        );
    } else {
        return (
            <div className="row no-gutters">
                <table className="table table-hover">
                    {tableHead()}
                    <tbody>
                    {props.books.map((row) => (
                        <tr
                            key={row.id}
                        >
                            <td>
                                <input type="checkbox"/>
                            </td>
                            <td>
                                {row.title}
                            </td>
                            <td>
                                {row.author}
                            </td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            </div>
        );
    }
}

export default TrashBooks;

TrashBooks.propTypes = {
    books: PropTypes.object,
    loadingBooks: PropTypes.bool,
};