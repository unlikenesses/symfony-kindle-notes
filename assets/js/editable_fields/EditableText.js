import React, {useState, useEffect} from 'react';
import PropTypes from 'prop-types';

const EditableText = (props) => {
    const [editing, setEditing] = useState(false);
    const [editingText, setEditingText] = useState(props.text);
    useEffect(() => {
        const handleKeydown = (e) => {
            if (editing) {
                if (e.keyCode === 13) {
                    props.saveText(editingText)
                    setEditing(false);
                }
                if (e.keyCode === 27) {
                    setEditingText(props.text);
                    setEditing(false);
                }
            }
        }
        window.addEventListener('keydown', handleKeydown);

        return () => {
            window.removeEventListener('keydown', handleKeydown);
        }
    }, [props, editing, editingText]);
    useEffect(() => {
        setEditingText(props.text)
    }, [props.text]);
    return (
        <div>
            <div
                onClick={() => setEditing(true)}
                className={`editable-text-${editing ? 'hidden' : 'active'}`}
            >
                {props.text}
            </div>
            <input
                type="text"
                value={editingText}
                onChange={(e) => setEditingText(e.target.value)}
                className={`editable-text-input-${editing ? 'active' : 'hidden'}`}
                style={{ width: Math.ceil(props.text.length * 1.2) + "ex" }}
            />
        </div>
    )
}

export default EditableText;

EditableText.propTypes = {
    text: PropTypes.string.isRequired,
    saveText: PropTypes.func.isRequired
};