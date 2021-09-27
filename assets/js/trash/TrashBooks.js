import React from 'react';
import PropTypes from "prop-types";
import Spinner from "../common/Spinner";

const TrashBooks = (props) => {
    const tableHead = () => {
        return (
            <thead className="min-w-full divide-y divide-gray-200">
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
            <div className="mb-4">
                <table className="table table-hover">
                    {tableHead()}
                    <tbody className="divide-y divide-gray-200">
                        <Spinner marginTop={2} />
                    </tbody>
                </table>
            </div>
        );
    } else {
        return (
            <div className="mb-4">
                <table className="table table-hover">
                    {tableHead()}
                    <tbody className="divide-y divide-gray-200">
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