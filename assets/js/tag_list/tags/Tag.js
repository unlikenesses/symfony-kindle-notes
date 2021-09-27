import React from 'react';
import PropTypes from 'prop-types';

const Tag = (props) => {
    return (
        <div className="border-b border-gray-400 p-5 cursor-pointer" onClick={props.onClick}>
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