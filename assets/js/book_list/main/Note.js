import React, {useRef} from 'react';
import PropTypes from 'prop-types';
import {apiErrors} from '../../api/book_api';
import Tags from '@yaireo/tagify/dist/react.tagify';

const Note = (props) => {
    const { tagWhitelist, note, deleteNote, deletingNote, handleTagChange } = props;
    const deletingText = deletingNote === note.id ? 'Deleting...' : 'Delete Note';
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
        <div className="rounded border border-gray-200 overflow-hidden shadow-md mb-6">
            <div className="bg-gray-50 border-b border-gray-200 p-4 flex justify-between text-gray-600 text-sm">
                <div>
                    {note.page ? 'Page ' + note.page : ''}
                    {note.page && note.location ? ' ' : ''}
                    {note.location ? 'Location ' + note.location: ''}
                </div>
                <div>
                    {note.date}
                </div>
            </div>
            <div className="p-4">
                <p className="note">{note.note}</p>
            </div>
            <div className="p-4 bg-gray-50 border-t border-gray-200 flex justify-between">
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
                <a className="cursor-pointer hover:underline text-blue-400"
                   onClick={(event) => deleteNote(note, event)}>
                    {deletingText}
                </a>
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