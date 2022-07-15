import React from 'react';
import Breadcrumb from './breadcrumb';
import axios from 'axios';
import map from 'lodash/map';
import errorHandler from './errorHandler';

import {
    Form,
    Input,
    Checkbox,
    Button,
    Space,
    Spin,
    message
} from 'antd';
import { Link, Navigate } from "react-router-dom";

class Signup extends React.Component {
    constructor(props, ctx) {
        super(props, ctx);
        this.state = {
            step: 'form',
            formData: {}
        }
    }

    _signup(data) {
        axios.post('/api/signup', data)
            .then((response) => {
                this.setState({ step: 'finish' }, (this.props.updateUserData()));
            }).catch(error => errorHandler(error, () => this.setState({ step: 'form' })));
    }

    onFinish = (data) => {
        this.setState({ step: 'wait', formData: data },
            () => this._signup(data)
        );
    }

    onFinishFailed = (data) => {
        console.log('Failed', data);
    }

    renderForm() {
        return (
            <Form
                name="signup"
                labelCol={{ span: 8 }}
                wrapperCol={{ span: 4 }}
                initialValues={{ remember: true, ...this.state.formData }}
                onFinish={this.onFinish}
                onFinishFailed={this.onFinishFailed}
            >
                <Form.Item
                    label="E-Mail"
                    name="email"
                    rules={[{ required: true, type: 'email', message: 'Please input your email!' }]}
                >
                    <Input />
                </Form.Item>
                <Form.Item
                    label="Username"
                    name="name"
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

                <Form.Item wrapperCol={{ offset: 8, span: 16 }}>
                    <Space>
                        <Button type="primary" htmlType="submit">
                            Sign Up and Login
                        </Button>
                    </Space>
                </Form.Item>
            </Form>
        );
    }

    render() {
        const { step } = this.state;
        const { user } = this.props;
        if (user && user.authenticated) {
            return (<Navigate to="/home" replace />);
        }
        return(
            <>
                <Breadcrumb items={[['/', 'Root'], ['/signup', 'Sign Up']]} />
                {step === 'form' && this.renderForm()}
                {step === 'wait' && <div style={{ textAlign: 'center' }}><Spin size="large" /></div>}
                {step === 'finish' && <Navigate to="/home" replace />}
            </>
        );
    }
}

export default Signup;
