import React from 'react';
import EditableText from "../../editable_fields/EditableText";
import EditableAuthor from "../../editable_fields/EditableAuthor";

const BookHeader = (props) => {
    const { book, saveBookTitle, saveBookAuthor } = props;
    const handleBookTitleSave = (title) => {
        saveBookTitle(book, title);
    }
    const handleBookAuthorSave = (firstName, lastName) => {
        saveBookAuthor(book, firstName, lastName);
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
                <h3 className="text-lg text-gray-600">
                    <EditableAuthor
                        firstName={book.firstName}
                        lastName={book.lastName}
                        saveAuthor={handleBookAuthorSave}
                    />
                </h3>
            </div>
        )
    } else {
        return null;
    }
}

export default BookHeader;