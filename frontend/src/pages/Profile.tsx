import { SetStateAction, useState } from 'react';
import { Box, Grid, Typography, TextField, Button } from '@mui/material';

function Profile() {
  const [name, setName] = useState('John Doe');
  const [editing, setEditing] = useState(false);
  const [newName, setNewName] = useState('');

  const handleEdit = () => {
    setEditing(true);
    setNewName(name);
  };

  const handleCancel = () => {
    setEditing(false);
    setNewName('');
  };

  const handleSubmit = (event: { preventDefault: () => void; }) => {
    event.preventDefault();
    setName(newName);
    setEditing(false);
    setNewName('');
  };

  const handleChange = (event: { target: { value: SetStateAction<string>; }; }) => {
    setNewName(event.target.value);
  };

  const handleLogout = () => {
    // Handle logout logic here
  };

  return (
    <Box sx={{ p: 4 }}>
      <Grid container spacing={4}>
        <Grid item xs={12}>
          <Typography variant="h4" sx={{ mb: 2 }}>
            {editing ? (
              <form onSubmit={handleSubmit}>
                <TextField
                  required
                  fullWidth
                  name="name"
                  label="Name"
                  value={newName}
                  onChange={handleChange}
                  sx={{ mb: 2 }}
                />
                <Button type="submit" variant="contained" sx={{ mr: 2 }}>
                  Save
                </Button>
                <Button onClick={handleCancel}>Cancel</Button>
              </form>
            ) : (
              <>
                {name}
                <Button onClick={handleEdit} sx={{ ml: 2 }} variant="outlined">
                  Edit
                </Button>
                <Button onClick={handleLogout} sx={{ ml: 2 }} variant="contained" color="error">
                  Log out
                </Button>
              </>
            )}
          </Typography>
        </Grid>
      </Grid>
    </Box>
  );
}

export default Profile;