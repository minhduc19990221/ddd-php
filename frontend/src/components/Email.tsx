import React, { useState } from 'react';
import TextField from '@mui/material/TextField';
import Zod from 'zod';

const schema = Zod.object({
    email: Zod.string().email(),
});

const EmailField = () => {
    const [email, setEmail] = useState('');
    const [error, setError] = useState('');

    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        const emailValue = event.target.value;
        setEmail(emailValue);
        const isValidEmail = schema.parse({ email: emailValue });
        setError(isValidEmail ? '' : 'Please enter a valid email address');
    };

    return (
        <TextField
            required
            fullWidth
            id="email"
            label="Email Address"
            name="email"
            autoComplete="email"
            value={email}
            onChange={handleChange}
            error={Boolean(error)}
            helperText={error}
        />
    );
};

export default EmailField;