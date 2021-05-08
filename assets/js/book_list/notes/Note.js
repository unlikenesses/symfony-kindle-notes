import React from 'react';
import PropTypes from 'prop-types';

const Note = (props) => {
    const { note, deleteNote, deletingNote } = props;
    const deletingText = deletingNote === note.id ? 'Deleting...' : 'Delete';
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
            <div className="card-footer">
                <a className="btn btn-link p-0"
                   onClick={(event) => deleteNote(note, event)}>
                    {deletingText}
                </a>
            </div>
        </div>
    )
}

export default Note;

Note.propTypes = {
    note: PropTypes.object.isRequired,
    deleteNote: PropTypes.func.isRequired,
    deletingNote: PropTypes.number.isRequired,
};