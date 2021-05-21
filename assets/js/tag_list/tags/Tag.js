import React from 'react';
import PropTypes from 'prop-types';

const Tag = (props) => {
    return (
        <div className="border-bottom p-3 book" onClick={props.onClick}>
            <h5>{props.name}</h5>
        </div>
    )
}

export default Tag;

Tag.propTypes = {
    name: PropTypes.string,
    active: PropTypes.bool,
    onClick: PropTypes.func
};