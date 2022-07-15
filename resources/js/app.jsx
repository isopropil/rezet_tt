import React from 'react';
import { createRoot } from 'react-dom/client';

import RootPage from './root';
import SignupPage from './signup';
import HomePage from './home';

import {
    BrowserRouter as Router,
    Routes,
    Route,
    Link
} from 'react-router-dom';

import {
    Layout,
    Menu,
    Button
} from 'antd';
import {Root} from "postcss";


class App extends React.Component {
    constructor(props, ctx) {
        super(props, ctx);
    }

    render() {
        return(
            <Layout>
                <Layout>
                    <Layout.Content style={{ height: '100vh' }}>
                        <Router>
                            <Routes>
                                <Route path="/" element={ <RootPage /> } />
                                <Route path="/signup" element={ <SignupPage /> } />
                                <Route path="/home" element={ <HomePage /> } />
                            </Routes>
                        </Router>
                    </Layout.Content>
                </Layout>
            </Layout>
        );
    }
}

if (document.getElementById('container')) {
    const root = createRoot(document.getElementById('container'));
    root.render(<App />);
}
