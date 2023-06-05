import { Component } from 'react';
import TextField from '@mui/material/TextField';

class FullNameField extends Component {
  render() {
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
  }
}

export default FullNameField;