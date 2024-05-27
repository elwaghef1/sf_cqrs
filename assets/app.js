import React from 'react';
import { createRoot } from 'react-dom/client';
import './styles/app.css'
import {createBrowserRouter, RouterProvider} from "react-router-dom";
import PostList from "./components/PostList";


const router = createBrowserRouter([
    {
        path: '/',
        element: <PostList />
    }
])

function App() {
    return <RouterProvider router={router} />
}

const container = document.getElementById('root');
const root = createRoot(container);
root.render(<App/>);