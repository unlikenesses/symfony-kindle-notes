import React from 'react';
import EditableText from "../../editable_fields/EditableText";

const BookHeader = (props) => {
    const { book, saveBookTitle } = props;
    const handleBookTitleSave = (title) => {
        saveBookTitle(book, title);
    }
    if (book) {
        return (
            <div className="mb-2">
                <h2 className="text-xl">
                    <EditableText
                        text={book.title}
                        saveText={handleBookTitleSave}
                    />
                </h2>
                <h3 className="text-lg text-gray-600">{book.author}</h3>
            </div>
        )
    } else {
        return null;
    }
}

export default BookHeader;