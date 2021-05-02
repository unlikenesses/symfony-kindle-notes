import React, {Component} from 'react';
import Books from './books/Books';
import { getBooks, getNotesForBook, deleteNote } from './api/book_api';

export default class BookListApp extends Component {
    constructor(props) {
        super(props);
        this.state = {
            activeBook: null,
            books: [],
            notes: {},
            loadingBooks: true,
            loadingNotes: false,
            deletingNote: 0,
        };
        this.handleBookClick = this.handleBookClick.bind(this);
        this.getNotes = this.getNotes.bind(this);
        this.deleteNote = this.deleteNote.bind(this);
    }
    componentDidMount() {
        getBooks().then((data) => {
            this.setState({
                books: data,
                loadingBooks: false
            });
        });
    }
    getNotes(book) {
        this.setState({
            loadingNotes: true
        });
        getNotesForBook(book.id).then((data) => {
            this.setState({
                notes: data,
                loadingNotes: false
            });
            window.scrollTo({
                top: 0,
                left: 0,
                behavior: 'smooth'
            });
        });
    }
    handleBookClick(book, event) {
        this.setState({
            activeBook: book
        });
        this.getNotes(book);
    }
    deleteNote(note, event) {
        if (confirm('Delete this note?')) {
            this.setState({
                deletingNote: note.id
            });
            deleteNote(note.id).then((data) => {
                console.log(data);
                this.setState({
                    deletingNote: 0
                });
                this.getNotes(this.state.activeBook);
            });
        }
    }
    render() {
        return (
            <Books
                {...this.state}
                handleBookClick={this.handleBookClick}
                deleteNote={this.deleteNote}
            />
        )
    }
}