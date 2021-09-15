import React from 'react';
import PropTypes from "prop-types";

const TrashNotes = (props) => {
    const tableHead = () => {
        return (
            <thead>
                <tr>
                    <th>Notes</th>
                </tr>
            </thead>
        );
    }
    if (props.loadingNotes) {
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
                    {props.notes.map((row) => (
                        <tr
                            key={row.id}
                        >
                            <td>
                                <input type="checkbox"/>
                            </td>
                            <td>
                                {row.note}
                            </td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            </div>
        );
    }
}

export default TrashNotes;

TrashNotes.propTypes = {
    notes: PropTypes.object,
    loadingNotes: PropTypes.bool,
};