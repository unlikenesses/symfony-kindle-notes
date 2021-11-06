import React, {useState, useEffect} from 'react';
import PropTypes from 'prop-types';

const EditableAuthor = (props) => {
    const [editing, setEditing] = useState(false);
    const [editingFirstname, setEditingFirstname] = useState(props.firstName);
    const [editingLastname, setEditingLastname] = useState(props.lastName);
    useEffect(() => {
        const handleKeydown = (e) => {
            if (editing) {
                if (e.keyCode === 13) {
                    props.saveAuthor(editingFirstname, editingLastname)
                    setEditing(false);
                }
                if (e.keyCode === 27) {
                    setEditingFirstname(props.firstName);
                    setEditingLastname(props.lastName);
                    setEditing(false);
                }
            }
        }
        window.addEventListener('keydown', handleKeydown);

        return () => {
            window.removeEventListener('keydown', handleKeydown);
        }
    }, [props, editing, editingFirstname, editingLastname]);
    useEffect(() => {
        setEditingFirstname(props.firstName);
        setEditingLastname(props.lastName);
    }, [props.firstName, props.lastName]);
    return (
        <div>
            <div
                onClick={() => setEditing(true)}
                className={`editable-text-${editing ? 'hidden' : 'active'}`}
            >
                {props.firstName} {props.lastName}
            </div>
            <input
                type="text"
                value={editingFirstname}
                onChange={(e) => setEditingFirstname(e.target.value)}
                className={`editable-text-input-${editing ? 'active' : 'hidden'}`}
                style={{ width: Math.ceil(props.firstName.length * 1.2) + "ex", border: "1px solid gray", padding: "2px", margin: "0 5px 0 0" }}
            />
            <input
                type="text"
                value={editingLastname}
                onChange={(e) => setEditingLastname(e.target.value)}
                className={`editable-text-input-${editing ? 'active' : 'hidden'}`}
                style={{ width: Math.ceil(props.lastName.length * 1.2) + "ex", border: "1px solid gray", padding: "2px" }}
            />
        </div>
    )
}

export default EditableAuthor;

EditableAuthor.propTypes = {
    firstName: PropTypes.string.isRequired,
    lastName: PropTypes.string.isRequired,
    saveAuthor: PropTypes.func.isRequired
};