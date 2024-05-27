import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { Link } from 'react-router-dom';

const PostList = () => {
    const [posts, setPosts] = useState([]);

    useEffect(() => {
        const fetchPosts = async () => {
            try {
                const response = await axios.get('http://localhost:8001/posts');
                setPosts(response.data);
            } catch (error) {
                alert('Error fetching posts: ' + error.response.data);
            }
        };

        fetchPosts();
    }, []);

    return (
        <div>
            <h1>All Posts</h1>
            <ul>
                {posts.map((post) => (
                    <li key={post.id}>
                        <h2>{post.title}</h2>
                        <p>{post.content}</p>
                        <p>{new Date(post.createdAt).toLocaleString()}</p>
                        <Link to={`/post/${post.id}`}>View Details</Link>
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default PostList;
