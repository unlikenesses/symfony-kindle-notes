import React, {useState} from 'react';
import PropTypes from "prop-types";
import Spinner from "../common/Spinner";

const TrashSection = (props) => {
    const [action, setAction] = useState(null);
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
                <div className="flex">
                    <select
                        name="action"
                        onChange={e => setAction(e.target.value)}
                        className="mt-1 form-select block pl-3 pr-10 py-2 text-base leading-6 border-gray-300 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5 mr-2">
                        <option value="">With selected:</option>
                        <option value="delete">Delete permanently</option>
                        <option value="restore">Restore</option>
                    </select>
                    <button
                        onClick={() => props.handleAction(action)}
                        className="rounded-md text-white text-sm bg-blue-600 hover:bg-blue-700 px-3"
                    >
                        Go
                    </button>
                </div>
            </div>
        );
    }
}

export default TrashSection;

TrashSection.propTypes = {
    data: PropTypes.array,
    loading: PropTypes.bool,
};