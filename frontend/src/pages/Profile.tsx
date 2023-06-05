import { SetStateAction, useState } from 'react';
import { Box, Grid, Typography, TextField, Button } from '@mui/material';
import axios from 'axios';
import z from 'zod';

function Profile() {
  const [name, setName] = useState('');
  const [editing, setEditing] = useState(false);
  const [newName, setNewName] = useState('');

  const schema = z.object({
    name: z.string().min(1),
  });


  const handleEdit = () => {
    setEditing(true);
    setNewName(name);
  };

  const handleCancel = () => {
    setEditing(false);
    setNewName('');
  };

  const handleSubmit = async (event: { preventDefault: () => void; }) => {
    event.preventDefault();

    // using zod to validate newName
    try {
      schema.parse({ name: newName });
    } catch (error: any) {
      alert("Invalid name");
      return;
    }
    await axios.put('users', { fullname: newName, email: localStorage.getItem('email') })
    .then(response => {
      console.log(response);
      setName(newName);
      setEditing(false);
      setNewName('');
    })
    .catch(error => {
      console.log(error);
    }
    );
  };

  const handleChange = (event: { target: { value: SetStateAction<string>; }; }) => {
    setNewName(event.target.value);
  };

  const handleLogout = () => {
    localStorage.removeItem('token');
    localStorage.removeItem('email');
    window.location.href = '/signin';
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

async function get_user_name() {
  const user_fullname = await axios.get('users', { params: { email: localStorage.getItem('email') } })
  console.log("user_fullname: ", user_fullname.data.fullname);
  return user_fullname.data.fullname;
}

export default Profile;