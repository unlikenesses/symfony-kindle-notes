import React from 'react';
import PropTypes from 'prop-types';

const Book = (props) => {
    return (
        <div className="border-bottom p-3 book" onClick={props.onClick}>
            <h5>{props.title}</h5>
            <h6>{props.author}</h6>
        </div>
    )
}

export default Book;

Book.propTypes = {
    title: PropTypes.string,
    author: PropTypes.string,
    active: PropTypes.bool,
    onClick: PropTypes.func
};