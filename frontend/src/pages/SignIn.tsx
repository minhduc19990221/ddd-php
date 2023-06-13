import { useState } from "react";
import { useNavigate } from "react-router-dom";
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
import PasswordField from "../components/Password";
import Zod from "zod";
import axios_instance from "../utils/Interceptor";
import AlertModal from "../components/BasicModal";

function Copyright(props: {
  sx: {
    mt: number;
    mb: number;
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
      <Link color="inherit" href="https://cybozu.vn/">
        Cybozu Vietnam
      </Link>{" "}
      {new Date().getFullYear()}
      {"."}
    </Typography>
  );
}

// TODO remove, this demo shouldn't need to reset the theme.
const defaultTheme = createTheme();

export default function SignIn() {
  const navigate = useNavigate();
  const [isLogin, setIsLogin] = useState(false);
  const [error, setError] = useState("");
  const [open, setOpen] = useState(false);
  const [message, setMessage] = useState("");

  const handleOpen = (msg: string) => {
    setMessage(msg);
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
    handleNavigate(isLogin);
  };

  const handleNavigate = (isLogin: boolean) => {
    if (isLogin) navigate("/demo", { replace: true });
  };

  const handleSubmit = async (event: {
    currentTarget: HTMLFormElement | undefined;
    preventDefault: () => void;
  }) => {
    event.preventDefault();
    const user_input = new FormData(event.currentTarget);
    const email = user_input.get("email") as string;
    const password = user_input.get("password") as string;
    // validate input
    const schema = Zod.object({
      email: Zod.string().email(),
      password: Zod.string(),
    });
    try {
      schema.parse({ email, password });
    } catch (error) {
      handleOpen("Invalid input!");
      setIsLogin(false);
      return;
    }
    try {
      await axios_instance
        .post("login", { email, password })
        .then((response) => {
          localStorage.setItem("token", response.data.token);
          localStorage.setItem("email", email);
          handleOpen("Login successfully!");
          setIsLogin(true);
        })
        .catch((error) => {
          console.log(error);
          setError(error.response.data.message);
          setIsLogin(false);
          handleOpen(error);
        });
    } catch (error) {
      console.log(error);
      setIsLogin(false);
      setError(error.response.data.message);
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
            Sign in
          </Typography>
          <Box
            component="form"
            noValidate
            onSubmit={handleSubmit}
            sx={{ mt: 3 }}
          >
            <Grid container spacing={2}>
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
              Sign In
            </Button>
            <AlertModal open={open} onClose={handleClose} message={message} />
            <Grid container justifyContent="center">
              <Grid item>
                <Link href="/signup" variant="body2">
                  Don't have an account? Sign up
                </Link>
              </Grid>
            </Grid>
          </Box>
        </Box>
        <Copyright sx={{ mt: 8, mb: 4 }} />
      </Container>
    </ThemeProvider>
  );
}
