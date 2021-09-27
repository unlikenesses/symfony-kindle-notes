import React from 'react';
import PropTypes from 'prop-types';
import Note from './Note';
import Spinner from "../../common/Spinner";

const Notes = (props) => {
    const { tag, loadingNotes, notes } = props;
    if (loadingNotes) {
        return (
            <div>
                <h2 className="text-lg">{tag.name}</h2>
                <Spinner marginTop={12} />
            </div>
        )
    }
    if (notes.data) {
        return (
            <div>
                <h2 className="text-lg">{tag.name}</h2>
                <p className="mt-2 mb-4 flex justify-between">
                    <span className="text-gray-600">
                        {notes.numHighlights} Highlights | {notes.numNotes} Notes
                    </span>
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