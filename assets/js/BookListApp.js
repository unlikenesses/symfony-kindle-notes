import React, {Component} from 'react';
import BookList from './BookList';
import { getBooks, getNotesForBook } from './api/book_api';

export default class BookListApp extends Component {
    constructor(props) {
        super(props);
        this.state = {
            activeBook: null,
            books: [],
            notes: [],
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
    handleBookClick(bookId, event) {
        this.setState({
            loadingNotes: true
        });
        getNotesForBook(bookId).then((data) => {
            this.setState({
                activeBook: bookId,
                notes: data,
                loadingNotes: false
            });
        })
    }
    render() {
        return (
            <BookList
                {...this.state}
                handleBookClick={this.handleBookClick}
            />
        )
    }
}