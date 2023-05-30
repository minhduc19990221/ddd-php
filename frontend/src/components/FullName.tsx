import { Component } from 'react';
import TextField from '@mui/material/TextField';

class FullNameField extends Component {
  render() {
    return (
      <TextField
        autoComplete="name"
        name="fullName"
        required
        fullWidth
        id="fullName"
        label="Full Name"
        autoFocus
      />
    );
  }
}

export default FullNameField;