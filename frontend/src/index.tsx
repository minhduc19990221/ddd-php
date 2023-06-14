import ReactDOM from "react-dom/client";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import SignIn from "./pages/SignIn";
import reportWebVitals from "./reportWebVitals";
import SignUp from "./pages/SignUp";
import Profile from "./pages/Profile";
import BoardPage from "./pages/Board";

const root = document.getElementById("root");
if (root) {
  const rootElement = ReactDOM.createRoot(root);
  rootElement.render(
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<SignIn />} />
        <Route path="/signup" element={<SignUp />} />
        <Route
          path="/profile"
          element={
            <Profile
              email={localStorage.getItem("email")}
              onLogout={() => {
                localStorage.clear();
                window.location.href = "/";
              }}
            />
          }
        />
        <Route path="/demo" element={<BoardPage />} />
        {/* <Route path="/contact" element={<Contact />} /> */}
      </Routes>
    </BrowserRouter>
  );
}

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
