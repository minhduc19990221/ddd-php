import { useEffect, useState } from "react";
import { Box, Grid, Typography, TextField, Button } from "@mui/material";
import Zod from "zod";
import axios_instance from "../utils/Interceptor";
import AlertModal from "../components/BasicModal";

interface User {
  fullname: string;
}

interface ProfileProps {
  email: string;
  onLogout: () => void;
}

function Profile({ email, onLogout }: ProfileProps) {
  const [name, setName] = useState("");
  const [editing, setEditing] = useState(false);
  const [newName, setNewName] = useState("");
  const [open, setOpen] = useState(false);
  const [message, setMessage] = useState('');

  useEffect(() => {
    getUser();
  }, [name]);

  const schema = Zod.object({
    name: Zod.string().min(1),
  });

  const handleEdit = () => {
    setEditing(true);
    setNewName(name);
  };

  const handleOpen = (msg: string) => {
    setMessage(msg);
    setOpen(true);
  };

  const handleClose = () => setOpen(false);


  const handleCancel = () => {
    setEditing(false);
    setNewName("");
  };

  const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();

    // using zod to validate newName
    try {
      schema.parse({ name: newName });
    } catch (error) {
      return;
    }

    try {
      const email_params = localStorage.getItem("email");

      const response = await axios_instance.put<User>("users", {
        fullname: newName,
        email: email_params,
      });
      setName(response.data.fullname);
      setEditing(false);
      setNewName("");
      handleOpen("Name updated successfully!");
    } catch (error) {
      console.log(error);
      handleOpen(error.response.data.message);
    }
  };

  const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    setNewName(event.target.value);
  };

  const getUser = async () => {
    try {
      const email_params = localStorage.getItem("email");
      const response = await axios_instance.get<{ user: User }>("users", {
        params: { email: email_params },
      });
      setName(response.data.user.fullname);
    } catch (error) {
      if (error.response && error.response.status === 401) {
        onLogout();
        handleOpen(error.response.data.message);
      }
    }
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
                <Button
                  onClick={onLogout}
                  sx={{ ml: 2 }}
                  variant="outlined"
                  color="error"
                >
                  Log out
                </Button>
              </>
            )}
            <AlertModal open={open} onClose={handleClose} message={message} />
          </Typography>
        </Grid>
      </Grid>
    </Box>
  );
}

export default Profile;
