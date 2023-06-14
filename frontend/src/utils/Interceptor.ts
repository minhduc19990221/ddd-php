import axios, { AxiosInstance, InternalAxiosRequestConfig } from "axios";

const axios_instance: AxiosInstance = axios.create({
  baseURL: "http://localhost:3000",
  timeout: 1000,
});

// Setting default headers to avoid CORS, CSRF and XSS attacks
axios_instance.interceptors.request.use(
  async (config: InternalAxiosRequestConfig<any>) => {
    const token = localStorage.getItem("token");
    const csrf_token = sessionStorage.getItem("csrf_token");
    if (token) {
      config.headers["Authorization"] = `Bearer ${token}`;
      config.headers["Content-Type"] = "application/json";
      config.headers["X-CSRF-TOKEN"] = csrf_token;
      console.log(csrf_token);
    }
    return config;
  },
  (error) => {
    console.log(error);
    Promise.reject(error);
  }
);

export default axios_instance;