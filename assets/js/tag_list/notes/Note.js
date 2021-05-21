import React from 'react';
import PropTypes from 'prop-types';

const Note = (props) => {
    const { note } = props;
    return (
        <div className="card mb-3">
            <div className="card-header">
                {note.source}
            </div>
            <div className="card-body">
                <p className="note">{note.note}</p>
            </div>
            <div className="card-footer d-flex justify-content-between">
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