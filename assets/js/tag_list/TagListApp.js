import React, {useState, useEffect} from 'react';
import Tag from "./tags/Tag";
import Notes from "./notes/Notes";
import {getNotesForTag, getTags} from "../api/book_api";

const TagListApp = () => {
    const [activeTag, setActiveTag] = useState({});
    const [tags, setTags] = useState([]);
    const [notes, setNotes] = useState({});
    const [loadingTags, setLoadingTags] = useState(true);
    const [loadingNotes, setLoadingNotes] = useState(false);
    useEffect(() => {
        getTags().
        then((data) => {
            setTags(data.tags);
            setLoadingTags(false);
        });
    }, []);
    const getNotes = (tag) => {
        setLoadingNotes(true);
        getNotesForTag(tag.id).then((data) => {
            setNotes(data);
            setLoadingNotes(false);
            window.scrollTo({
                top: 0,
                left: 0,
                behavior: 'smooth'
            });
        });
    }
    const handleTagClick = (tag, event) => {
        setActiveTag(tag);
        getNotes(tag);
    }
    if (loadingTags) {
        return (
            <div className="d-flex justify-content-center mt-5">
                <div className="spinner-border" role="status">
                    <span className="sr-only">Loading...</span>
                </div>
            </div>
        )
    }
    return (
        <div className="container-fluid p-0">
            <div className="row no-gutters">
                <div className="col-2 border-right">
                    {tags.map((row) => (
                        <Tag
                            key={row.id}
                            name={row.name}
                            active={activeTag != null && activeTag.id === row.id}
                            onClick={(event) => handleTagClick(row, event)}
                        />
                    ))}
                </div>
                <div className="col p-3">
                    <Notes
                        tag={activeTag}
                        notes={notes}
                        loadingNotes={loadingNotes}
                    />
                </div>
            </div>
        </div>
    );
}

export default TagListApp;