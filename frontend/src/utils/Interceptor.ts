import axios, { AxiosInstance, InternalAxiosRequestConfig } from "axios";

const axios_instance: AxiosInstance = axios.create({
    baseURL: "http://localhost:3000",
    timeout: 1000,
    headers: { "X-Custom-Header": "foobar" },
});

// Setting default headers to avoid CORS, and XSS attacks
axios_instance.interceptors.request.use(
    async (config: InternalAxiosRequestConfig<any>) => {
        const token = localStorage.getItem("token");
        if (token) {
            config.headers["Authorization"] = `Bearer ${token}`;
            config.headers["Content-Type"] = "application/json";
        }
        return config;
      },
      (error) => {
        console.log(error);
        Promise.reject(error);
      }
);

export default axios_instance;