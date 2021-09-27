import React from 'react';
import PropTypes from "prop-types";
import Spinner from "../common/Spinner";

const TrashNotes = (props) => {
    const tableHead = () => {
        return (
            <thead className="min-w-full divide-y divide-gray-200">
                <tr>
                    <th>Notes</th>
                    <th></th>
                </tr>
            </thead>
        );
    }
    if (props.loadingNotes) {
        return (
            <div className="">
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
            <div className="">
                <table className="table table-hover">
                    {tableHead()}
                    <tbody className="divide-y divide-gray-200">
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