import React from 'react';
import PropTypes from "prop-types";
import Spinner from "../common/Spinner";

const TrashSection = (props) => {
    const tableHead = () => {
        return (
            <thead className="min-w-full divide-y divide-gray-200">
                <tr>
                    <th colSpan="3" align="left">{props.title}</th>
                </tr>
                <tr>
                    <th colSpan="3" align="left" className="py-2">
                        <button
                            className="rounded-md text-white bg-blue-600 hover:bg-blue-700 px-2 text-xs mr-1"
                            onClick={props.setAllChecked}
                        >All</button>
                        <button
                            className="rounded-md text-white bg-blue-600 hover:bg-blue-700 px-2 text-xs"
                            onClick={props.setNoneChecked}
                        >None</button>
                    </th>
                </tr>
            </thead>
        );
    }
    if (props.loading) {
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
                    {props.data.map((row) => (
                        <tr
                            key={row.id}
                        >
                            <td className="py-2 pr-2">
                                <input
                                    type="checkbox"
                                    onClick={() => props.handleCheckboxClick(row.id)}
                                    onChange={() => {}}
                                    checked={row.checked}
                                />
                            </td>
                            <td className="pr-3">
                                {row.firstCol}
                            </td>
                            <td>
                                {row.secondCol}
                            </td>
                        </tr>
                    ))}
                    </tbody>
                </table>
                
            </div>
        );
    }
}

export default TrashSection;

TrashSection.propTypes = {
    data: PropTypes.array,
    loading: PropTypes.bool,
};