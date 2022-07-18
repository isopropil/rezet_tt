import React from 'react';
import { createRoot } from 'react-dom/client';
import axios from 'axios';

import RootPage from './root';
import SignupPage from './signup';
import HomePage from './home';

import {
    BrowserRouter as Router,
    Routes,
    Route,
    Link,
    Navigate,
    message
} from 'react-router-dom';

import {
    Layout,
    Menu,
    Breadcrumb,
    Spin,
    Button
} from 'antd';
import {Root} from "postcss";


class App extends React.Component {
    constructor(props, ctx) {
        super(props, ctx);
        this.state = {
            user: null
        }
    }

    componentDidMount() {
        this.updateUserData();
    }

    /**
     * Request user data from backend API
     */
    updateUserData = () => {
        axios.get('/api/user-data')
            .then(response => this.setState({ user: response.data.payload }))
            .catch(() => message.error('Error retrieving user data'));
    }

    renderRoot() {
        const { user } = this.state;
        return (
            <>
                {user && !user.authenticated &&
                    <RootPage
                        user={user}
                        updateUserData={this.updateUserData}
                    />
                }
                {user && user.authenticated &&
                    <Navigate to="/home" replace />
                }
            </>
        );
    }

    render() {
        const { user } = this.state;
        return(
            <Layout style={{ height: '100vh' }}>
                <Layout.Header>
                    <Menu theme="dark">Test App</Menu>
                </Layout.Header>
                <Layout.Content
                    style={{
                        padding: '0 50px',
                    }}
                >
                    <div className="site-layout-content">
                        {!user && <div style={{ textAlign: 'center' }}><Spin size="large" /></div>}
                        {user &&
                            <Router>
                                <Routes>
                                    <Route path="/" element={this.renderRoot()} />
                                    <Route path="/signup" element={
                                        <SignupPage
                                            user={user}
                                            updateUserData={this.updateUserData}
                                        />
                                    }/>
                                    <Route path="/home" element={<HomePage
                                        user={user}
                                        updateUserData={this.updateUserData}
                                    />}/>
                                </Routes>
                            </Router>
                        }
                    </div>
                </Layout.Content>
                <Layout.Footer
                    style={{
                        textAlign: 'center',
                    }}
                >
                    <br/>
                </Layout.Footer>
            </Layout>
        );
    }
}

if (document.getElementById('container')) {
    const root = createRoot(document.getElementById('container'));
    root.render(<App />);
}
