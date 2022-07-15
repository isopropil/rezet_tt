import React from 'react';
import { Link } from 'react-router-dom';
import {
    Form,
    Input,
    Checkbox,
    Button,
    Space,
    message
} from 'antd';
import axios from 'axios';
import Breadcrumb from './breadcrumb';
import errorHandler from './errorHandler';

class Root extends React.Component {
    constructor(props, ctx) {
        super(props, ctx);
    }

    _googleLogin = () => {
        window.location = '/api/login/google';
    }

    onFinish = (data) => {
        const { updateUserData } = this.props;
        const hide = message.loading('Logging in...', 0);
        axios.post('/api/login', data)
            .then((response) => {
                const { data } = response;
                hide();
                if (data && data.status === 'error') {
                    message.error('Credentials are invalid');
                } else if (data && data.status === 'ok') {
                    message.success('Authenticated');
                    updateUserData();
                }
            }).catch(error => errorHandler(error, () => hide()));
    }

    onFinishFailed = (data) => {
        // ...
    }

    render() {
        return(
            <>
                <Breadcrumb items={ [['/', 'Root']] } />
                <Form
                    name="basic"
                    labelCol={{ span: 8 }}
                    wrapperCol={{ span: 4 }}
                    initialValues={{ remember: true }}
                    onFinish={this.onFinish}
                    onFinishFailed={this.onFinishFailed}
                >
                    <Form.Item
                        label="E-Mail"
                        name="email"
                        rules={[{ required: true, message: 'Please input your username!' }]}
                    >
                        <Input />
                    </Form.Item>

                    <Form.Item
                        label="Password"
                        name="password"
                        rules={[{ required: true, message: 'Please input your password!' }]}
                    >
                        <Input.Password />
                    </Form.Item>

                    <Form.Item name="remember" valuePropName="checked" wrapperCol={{ offset: 8, span: 16 }}>
                        <Checkbox>Remember me</Checkbox>
                    </Form.Item>

                    <Form.Item wrapperCol={{ offset: 8, span: 16 }}>
                        <Space>
                            <Button type="primary" htmlType="submit">
                                Log In
                            </Button>
                            <Button htmlType="button" onClick={this._googleLogin}>
                                Log In with Google
                            </Button>
                            <Link to="/signup">Sign Up</Link>
                        </Space>
                    </Form.Item>
                </Form>
            </>
        );
    }
}

export default Root;
