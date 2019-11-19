import React from 'react';
import PropTypes from 'prop-types';

export default function NoteList(props) {
    const { loadingNotes, notes } = props;
    if (loadingNotes) {
        return (
            <div className="d-flex justify-content-center mt-5">
                <div className="spinner-border" role="status">
                    <span className="sr-only">Loading...</span>
                </div>
            </div>
        )
    }
    return (
        <div>
            {notes.map((note) => (
                <div key={note.id}>
                    <p className="note">{note.note}</p>
                    <footer className="blockquote-footer">
                        {note.date} &mdash;
                        {note.page ? 'Page ' + note.page : ''}
                        {note.page && note.location ? ' ' : ''}
                        {note.location ? 'Location ' + note.location: ''}
                    </footer>
                </div>
            ))}
        </div>
    )
}

NoteList.propTypes = {
    notes: PropTypes.array,
    loadingNotes: PropTypes.bool
};