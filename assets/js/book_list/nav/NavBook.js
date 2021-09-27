import React from 'react';
import PropTypes from 'prop-types';

const NavBook = (props) => {
    return (
        <div className="border-b border-gray-400 p-5 cursor-pointer" onClick={props.onClick}>
            <h5>{props.title}</h5>
            <h6 className="text-gray-600 mb-2">{props.author}</h6>
            <div className="flex justify-start flex-wrap">
            {props.categories.map(category =>
                <div
                    key={category.id}
                    className="bg-blue-500 hover:bg-blue-700 text-sm text-white py-1 px-3 rounded-full mr-1 mb-1"
                    onClick={e => props.onCategoryPillClick(category.name)}
                >
                    {category.name}
                </div>
            )}
            </div>
        </div>
    )
}

export default NavBook;

NavBook.propTypes = {
    title: PropTypes.string,
    author: PropTypes.string,
    categories: PropTypes.array,
    active: PropTypes.bool,
    onClick: PropTypes.func,
    onCategoryPillClick: PropTypes.func
};