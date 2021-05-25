import React, {useRef} from 'react';
import PropTypes from 'prop-types';
import {apiErrors} from '../../api/book_api';
import Tags from '@yaireo/tagify/dist/react.tagify';

const Note = (props) => {
    const { tagWhitelist, note, deleteNote, deletingNote, handleTagChange } = props;
    const deletingText = deletingNote === note.id ? 'Deleting...' : 'Delete';
    const tagifyRef = useRef();
    const tagChanged = (e) => {
        handleTagChange(note.id, e.detail.value)
            .catch((err) => {
                if (err.message in apiErrors) {
                    tagifyRef.current.removeTags();
                    alert(apiErrors[err.message]);
                } else {
                    console.error('Unknown API error occurred');
                }
            });
    }
    const invalidTag = (e) => {
        const message = e.detail.message;
        let displayMessage = message;
        if (message === 'pattern mismatch') {
            displayMessage = 'Tags must be between 2 and 20 characters long and may only contain letters and numbers.';
        }
        alert(displayMessage);
    }
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
                <div className="note-footer">
                    <Tags
                        tagifyRef={tagifyRef}
                        value={note.tags.map(tag => tag.name).join(',')}
                        onChange={e => tagChanged(e)}
                        settings={{
                            placeholder: 'Tags...',
                            maxTags: 3,
                            backspace: 'edit',
                            pattern: /^[a-zA-Z0-9\s]{3,20}$/
                        }}
                        whitelist={tagWhitelist}
                        onInvalid={e => invalidTag(e)}
                    />
                    <a className="btn btn-link p-0"
                       onClick={(event) => deleteNote(note, event)}>
                        {deletingText}
                    </a>
                </div>
            </div>
        </div>
    )
}

export default Note;

Note.propTypes = {
    tagWhitelist: PropTypes.array.isRequired,
    note: PropTypes.object.isRequired,
    deleteNote: PropTypes.func.isRequired,
    deletingNote: PropTypes.number.isRequired,
    handleTagChange: PropTypes.func.isRequired
};