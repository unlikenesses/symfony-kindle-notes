import React from 'react';
import PropTypes from 'prop-types';

export default function Note(props) {
    const { note } = props;
    return (
        <div className="card mb-3">
            <div className="card-header d-flex justify-content-between">
                <div>
                    {note.page ? 'Page ' + note.page : ''}
                    {note.page && note.location ? ' ' : ''}
                    {note.location ? 'Location ' + note.location: ''}
                </div>
                <div>
                    {note.date}
                </div>
            </div>
            <div className="card-body">
                <p className="note">{note.note}</p>
            </div>
        </div>
    )
}

Note.propTypes = {
    note: PropTypes.object
};