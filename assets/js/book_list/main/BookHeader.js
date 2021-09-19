import React from 'react';
import EditableText from "../../editable_fields/EditableText";

const BookHeader = (props) => {
    const { book, saveBookTitle } = props;
    const handleBookTitleSave = (title) => {
        saveBookTitle(book, title);
    }
    if (book) {
        return (
            <div>
                <h2>
                    <EditableText
                        text={book.title}
                        saveText={handleBookTitleSave}
                    />
                </h2>
                <h3>{book.author}</h3>
            </div>
        )
    } else {
        return null;
    }
}

export default BookHeader;