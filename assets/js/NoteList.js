import React from 'react';
import PropTypes from 'prop-types';

export default function NoteList(props) {
    return (
        <div>
            {props.notes.map((note) => (
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
    notes: PropTypes.array
};