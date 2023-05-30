import React, { Component } from 'react';
import TextField from '@mui/material/TextField';

interface State {
    email: string;
    error: string;
}

class EmailField extends Component<{}, State> {
    constructor(props: {} | Readonly<{}>) {
        super(props);
        this.state = {
            email: '',
            error: '',
        };
    }

    handleChange = (event: {
        [x: string]: any; preventDefault: () => void;
    }) => {
        event.preventDefault();
        this.setState({ email: event.target.value });
        const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.state.email);
        if (isValidEmail) {
            this.setState({ error: '' });
        } else {
            this.setState({ error: 'Please enter a valid email address' });
        }
    };

    render() {
        return (
                <TextField
                    required
                    fullWidth
                    id="email"
                    label="Email Address"
                    name="email"
                    autoComplete="email"
                    value={this.state.email}
                    onChange={this.handleChange}
                    error={Boolean(this.state.error)}
                    helperText={this.state.error}
                />
        );
    }
}

export default EmailField;