import React from 'react';
import PropTypes from 'prop-types';
import Note from './Note';

const Notes = (props) => {
    const { tag, loadingNotes, notes } = props;
    if (loadingNotes) {
        return (
            <div>
                <h2>{tag.name}</h2>
                <div className="d-flex justify-content-center mt-5">
                    <div className="spinner-border" role="status">
                        <span className="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        )
    }
    if (notes.data) {
        console.log(notes.data);
        return (
            <div>
                <h2>{tag.name}</h2>
                <p>
                    {notes.numHighlights} Highlights | {notes.numNotes} Notes
                </p>
                {notes.data.map((note) => (
                    <Note
                        key={note.id}
                        note={note}
                    />
                ))}
            </div>
        )
    }
    return <div></div>
}

export default Notes;

Notes.propTypes = {
    tag: PropTypes.object,
    notes: PropTypes.object,
    loadingNotes: PropTypes.bool
};