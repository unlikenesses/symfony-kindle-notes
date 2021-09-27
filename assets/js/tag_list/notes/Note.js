import React from 'react';
import PropTypes from 'prop-types';

const Note = (props) => {
    const { note } = props;
    return (
        <div className="rounded border border-gray-200 overflow-hidden shadow-md mb-6">
            <div className="bg-gray-50 border-b border-gray-200 p-4 flex justify-between">
                {note.source}
            </div>
            <div className="p-4">
                <p className="note">{note.note}</p>
            </div>
            <div className="p-4 bg-gray-50 border-t border-gray-200 text-gray-600 text-sm flex justify-between">
                <div>
                    {note.page ? 'Page ' + note.page : ''}
                    {note.page && note.location ? ' ' : ''}
                    {note.location ? 'Location ' + note.location: ''}
                </div>
                <div>
                    {note.date}
                </div>
            </div>
        </div>
    )
}

export default Note;

Note.propTypes = {
    note: PropTypes.object.isRequired
};