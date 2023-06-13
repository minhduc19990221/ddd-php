import TextField from '@mui/material/TextField';

const FullNameField = () => {
    return (
        <TextField
            autoComplete="name"
            name="fullname"
            required
            fullWidth
            id="fullname"
            label="Full Name"
            autoFocus
        />
    );
};

export default FullNameField;