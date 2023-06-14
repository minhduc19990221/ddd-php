import * as React from "react";
import Avatar from "@mui/material/Avatar";
import Button from "@mui/material/Button";
import CssBaseline from "@mui/material/CssBaseline";
import Link from "@mui/material/Link";
import Grid from "@mui/material/Grid";
import Box from "@mui/material/Box";
import LockOutlinedIcon from "@mui/icons-material/LockOutlined";
import Typography from "@mui/material/Typography";
import Container from "@mui/material/Container";
import { createTheme, ThemeProvider } from "@mui/material/styles";
import EmailField from "../components/Email";
import FullNameField from "../components/FullName";
import PasswordField from "../components/Password";
import Zod from "zod";
import { useNavigate } from "react-router-dom";
import { useState } from "react";
import axios_instance from "../utils/Interceptor";
import AlertModal from "../components/BasicModal";

function Copyright(props: {
  sx: {
    mt: number;
  };
}) {
  return (
    <Typography
      variant="body2"
      color="text.secondary"
      align="center"
      {...props}
    >
      {"Copyright © "}
      <Link color="inherit" href="https://mui.com/">
        Your Website
      </Link>{" "}
      {new Date().getFullYear()}
      {"."}
    </Typography>
  );
}

const defaultTheme = createTheme();

export default function SignUp() {
  const navigate = useNavigate();
  const [error, setError] = useState("");
  const [open, setOpen] = useState(false);
  const [message, setMessage] = useState("");
  const [allowNavigate, setAllowNavigate] = useState(false);

  const handleOpen = (msg: string) => {
    setMessage(msg);
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
    handleNavigate(allowNavigate);
  };

  const handleNavigate = (allowNavigate: boolean) => {
    if (allowNavigate) navigate("/", { replace: true });
  };

  const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    const user_input = new FormData(event.currentTarget);
    const email = user_input.get("email") as string;
    const fullname = user_input.get("fullname") as string;
    const password = user_input.get("password") as string;
    // validate email, fullname, and password
    const schema = Zod.object({
      email: Zod.string().email(),
      fullname: Zod.string(),
      password: Zod.string(),
    });
    try {
      schema.parse({ email, password, fullname });
    } catch (error) {
      handleOpen("Invalid email, fullname, or password");
      setAllowNavigate(false);
      return;
    }
    try {
      await axios_instance
        .post("register", { email, fullname, password })
        .then((response) => {
          console.log(response);
          handleOpen("Register successfully");
          setAllowNavigate(true);
        })
        .catch((error) => {
          console.log(error);
          handleOpen("Register failed");
          setAllowNavigate(false);
        });
    } catch (error) {
      setError(error.response.data.message);
      console.log(error);
    }
  };

  return (
    <ThemeProvider theme={defaultTheme}>
      <Container component="main" maxWidth="xs">
        <CssBaseline />
        <Box
          sx={{
            marginTop: 8,
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
          }}
        >
          <Avatar sx={{ m: 1, bgcolor: "secondary.main" }}>
            <LockOutlinedIcon />
          </Avatar>
          <Typography component="h1" variant="h5">
            Sign up
          </Typography>
          <Box
            component="form"
            noValidate
            onSubmit={handleSubmit}
            sx={{ mt: 3 }}
          >
            <Grid container spacing={2}>
              <Grid item xs={12}>
                <FullNameField />
              </Grid>
              <Grid item xs={12}>
                <EmailField />
              </Grid>
              <Grid item xs={12}>
                <PasswordField />
              </Grid>
            </Grid>
            <Button
              type="submit"
              fullWidth
              variant="contained"
              sx={{ mt: 3, mb: 2 }}
            >
              Sign Up
            </Button>
            <AlertModal open={open} onClose={handleClose} message={message} />
            <Grid container justifyContent="center">
              <Grid item>
                <Link href="/" variant="body2">
                  Already have an account? Sign in
                </Link>
              </Grid>
            </Grid>
          </Box>
        </Box>
        <Copyright sx={{ mt: 5 }} />
      </Container>
    </ThemeProvider>
  );
}
