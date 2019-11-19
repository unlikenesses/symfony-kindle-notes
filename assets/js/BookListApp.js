import React, {Component} from 'react';
import BookList from './BookList';
import { getBooks } from './api/book_api';

export default class BookListApp extends Component {
    constructor(props) {
        super(props);
        this.state = {
            highlightedRowId: null,
            books: [],
            isLoaded: false
        };
        this.handleRowClick = this.handleRowClick.bind(this);
    }
    componentDidMount() {
        getBooks().then((data) => {
            this.setState({
                books: data,
                isLoaded: true
            });
        });
    }
    handleRowClick(rowId, event) {
        this.setState({highlightedRowId: rowId});
    }
    render() {
        return (
            <BookList
                {...this.state}
                handleRowClick={this.handleRowClick}
            />
        )
    }
}