import React, {Component} from 'react';
import Books from './books/Books';
import { getBooks, getNotesForBook } from './api/book_api';

export default class BookListApp extends Component {
    constructor(props) {
        super(props);
        this.state = {
            activeBook: null,
            books: [],
            notes: {},
            loadingBooks: true,
            loadingNotes: false
        };
        this.handleBookClick = this.handleBookClick.bind(this);
    }
    componentDidMount() {
        getBooks().then((data) => {
            this.setState({
                books: data,
                loadingBooks: false
            });
        });
    }
    handleBookClick(book, event) {
        this.setState({
            loadingNotes: true
        });
        getNotesForBook(book.id).then((data) => {
            this.setState({
                activeBook: book,
                notes: data,
                loadingNotes: false
            });
        })
    }
    render() {
        return (
            <Books
                {...this.state}
                handleBookClick={this.handleBookClick}
            />
        )
    }
}