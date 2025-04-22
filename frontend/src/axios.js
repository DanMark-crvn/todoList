import axios from 'axios';
import router from './router';

// Create an axios instance with a base URL
const axiosClient = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL, 
  withCredentials: true,
  withXSRFToken: true,
})

//FOR API TOKEN AUTHENTICATION ONLY
// axiosClient.interceptors.request.use(
//   config => {
//     config.headers.Authorization = `Bearer ${localStorage.getItem('token')}`
//     return config;
//   }
// )

axiosClient.interceptors.response.use(
    (response) => {
      return response
    },
    error => {
        if (error.response  && error.response.status === 401) {
            router.push({name: 'Login'});
        }
        throw error;
    }
)

export default axiosClient